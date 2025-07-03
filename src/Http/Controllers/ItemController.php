<?php

namespace Osmanco\ComplexCollection\Http\Controllers;

use Osmanco\ComplexCollection\Models\Item;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Statamic\Support\Str;
use Statamic\Facades\Site;
use Statamic\Facades\Collection;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Support\Facades\Action;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Statamic\Entries\Entry as StatamicEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Statamic\Support\Facades\Toast;

class ItemController extends CpController
{
    /**
     * Get a configuration value with an optional default
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return config("complex-collection.{$key}", $default);
    }

    public function index()
    {
        // Get the selected main category from the request
        $selectedMainCategory = request('main_category');
        $selectedStaffCategory = request('staff_category');
        $collectionHandle = $this->getConfig('collection_handle', 'team_members');
        $mainCategoryField = $this->getConfig('main_category_field', 'main_staff_category');
        $subCategoryField = $this->getConfig('sub_category_field', 'staff_category');

        // Get all main staff categories
        $mainCategories = \Statamic\Facades\Term::query()
            ->where('taxonomy', $mainCategoryField)
            ->get()
            ->map(function ($term) {
                return [
                    'slug' => $term->slug(),
                    'title' => $term->title()
                ];
            });

        // Get staff categories based on selected main category
        $staffCategories = collect();
        if ($selectedMainCategory) {
            $staffCategories = \Statamic\Facades\Term::query()
                ->where('taxonomy', $subCategoryField)
                ->whereJsonContains('data->parent', $selectedMainCategory)
                ->get()
                ->map(function ($term) {
                    return [
                        'slug' => $term->slug(),
                        'title' => $term->title()
                    ];
                });
        }

        // Build the query
        $query = \Statamic\Entries\Entry::query()
            ->where('collection', 'team_members')
            ->where('published', true)
            ->orderBy('order', 'asc');

        // Apply filters if selected
        if ($selectedMainCategory) {
            $query->where($mainCategoryField, $selectedMainCategory);
        }

        if ($selectedStaffCategory) {
            $query->where($subCategoryField, $selectedStaffCategory);
        }

        $items = $query->paginate(100);

        $staffCategoryColors = [
            'category1' => 'bg-blue-100 text-blue-800',
            'category2' => 'bg-green-100 text-green-800',
            'category3' => 'bg-yellow-100 text-yellow-800',
            // Add more categories and colors as needed
        ];

        return view('complex-collection-ordering::index', [
            'items' => $items,
            'mainCategories' => $mainCategories,
            'staffCategories' => $staffCategories,
            'selectedMainCategory' => $selectedMainCategory,
            'selectedStaffCategory' => $selectedStaffCategory,
            'staffCategoryColors' => $staffCategoryColors,
        ]);
    }

    public function create()
    {
        return view('complex-collection-ordering::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'staff_category' => 'required|string',
            'main_staff_category' => 'required|string',
        ]);

        // Handle file upload
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('team-members', 'public');
            $validated['picture'] = $path;
        }

        Item::create($validated);

        Toast::success('Team member created successfully!');
        return redirect()->route('complex-collection-ordering.index');
    }

    public function edit($id)
    {
        $item = Item::find($id);

        if (!$item) {
            abort(404);
        }

        return view('complex-collection-ordering::edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'staff_category' => 'required|string',
            'main_staff_category' => 'required|string',
        ]);

        $item = Item::find($id);

        if (!$item) {
            abort(404);
        }

        // Handle file upload
        if ($request->hasFile('picture')) {
            // Delete old picture if exists
            if ($item->picture) {
                \Storage::disk('public')->delete($item->picture);
            }

            $path = $request->file('picture')->store('team-members', 'public');
            $validated['picture'] = $path;
        } else {
            // Keep the existing picture if no new one was uploaded
            unset($validated['picture']);
        }

        $item->update($validated);

        Toast::success('Team member updated successfully!');
        return redirect()->route('complex-collection-ordering.index');
    }

    public function destroy($id)
    {
        $entry = \Statamic\Facades\Entry::find($id);

        if ($entry) {
            // Delete the associated picture if it exists
            if ($picture = $entry->get('picture')) {
                $picturePath = is_array($picture) ? ($picture[0] ?? null) : $picture;
                if ($picturePath) {
                    Storage::disk('s3')->delete($picturePath);
                }
            }

            $entry->delete();
            return redirect(cp_route('complex-collection-ordering.index'))
                ->with('success', 'Team member deleted successfully.');
        }

        return redirect()->back()->with('error', 'Team member not found.');
    }

    /**
     * Get staff categories by main category
     */
    public function getStaffCategories($mainCategory)
    {
        $collectionHandle = $this->getConfig('collection_handle', 'team_members');
        $mainCategoryField = $this->getConfig('main_category_field', 'main_staff_category');
        $subCategoryField = $this->getConfig('sub_category_field', 'staff_category');

        $categories = Entry::query()
            ->where('collection', $collectionHandle)
            ->where('status', 'published')
            ->where($mainCategoryField, $mainCategory)
            ->get()
            ->pluck($subCategoryField)
            ->unique()
            ->values();

        return response()->json($categories);
    }

    public function updateOrder(Request $request)
    {
        $collectionHandle = $this->getConfig('collection_handle', 'team_members');
        $mainCategoryField = $this->getConfig('main_category_field', 'main_staff_category');
        $subCategoryField = $this->getConfig('sub_category_field', 'staff_category');

        // Get the current category from the request or session
        $mainCategory = $request->input('main_category') ?? session('current_main_category');
        $staffCategory = $request->input('staff_category') ?? session('current_staff_category');

        // Log the received data
        \Log::info('Updating order with data:', [
            'order' => $request->order,
            'main_category' => $mainCategory,
            'staff_category' => $staffCategory
        ]);

        // Start building the base query
        $query = DB::table('entries')
            ->where('collection', $collectionHandle)
            ->where('published', 1);

        // Add category filters if provided
        if ($mainCategory) {
            $query->where('data->' . $mainCategoryField, $mainCategory);

            if ($staffCategory) {
                $query->where('data->' . $subCategoryField, $staffCategory);
            }
        }

        // Get all entries with their raw data
        $entries = $query->get(['id', 'order', 'data']);

        // Debug: Log the first entry's raw data
        if ($entries->isNotEmpty()) {
            $firstEntry = $entries->first();
            $data = json_decode($firstEntry->data, true);

            \Log::info('Raw entry data from database:', [
                'id' => $firstEntry->id,
                'database_order' => $firstEntry->order,
                'data_order' => $data['order'] ?? null,
                'data_column' => $data
            ]);
        }

        // Process the entries and ensure all have an order
        $allEntries = collect();
        $orderCounter = 1;

        // First, process the entries in the order they were received from the UI
        foreach ($request->order as $id) {
            $entry = $entries->firstWhere('id', $id);
            if ($entry) {
                $data = json_decode($entry->data, true);
                $order = $orderCounter++;

                $entryData = (object) [
                    'id' => $entry->id,
                    'order' => $order,
                    'title' => $data['title'] ?? 'No Title',
                    'main_staff_category' => $data[$mainCategoryField] ?? null,
                    'staff_category' => $data[$subCategoryField] ?? null,
                    'raw_data' => $data
                ];

                $allEntries->push($entryData);
            }
        }

        // Then add any entries that weren't in the request (shouldn't happen, but just in case)
        // foreach ($entries as $entry) {
        //     if (!$allEntries->contains('id', $entry->id)) {
        //         $data = json_decode($entry->data, true);
        //         $order = $orderCounter++;

        //         $entryData = (object) [
        //             'id' => $entry->id,
        //             'order' => $order,
        //             'title' => $data['title'] ?? 'No Title',
        //             'main_staff_category' => $data['main_staff_category'] ?? null,
        //             'staff_category' => $data['staff_category'] ?? null,
        //             'raw_data' => $data
        //         ];

        //         $allEntries->push($entryData);
        //     }
        // }

        // Start a database transaction to ensure consistency
        DB::beginTransaction();

        try {
            // Update all entries with their new orders
            $updatedEntries = [];

            foreach ($allEntries as $entry) {
                $previousOrder = $entry->raw_data['order'] ?? null;
                $newOrder = $entry->order;

                // Update both the order column and the order in the data JSON
                $entry->raw_data['order'] = $newOrder;

                DB::table('entries')
                    ->where('id', $entry->id)
                    ->update([
                        'order' => $newOrder,
                        'data' => json_encode($entry->raw_data)
                    ]);

                $updatedEntries[] = [
                    'id' => $entry->id,
                    'title' => $entry->title,
                    'previous_order' => $previousOrder,
                    'new_order' => $newOrder,
                    'main_category' => $entry->main_staff_category,
                    'staff_category' => $entry->staff_category
                ];

                \Log::info("Updated order for entry {$entry->id} from " .
                    (is_null($previousOrder) ? 'null' : $previousOrder) .
                    " to $newOrder");
            }

            // Commit the transaction
            DB::commit();

            \Log::info('Successfully updated all entries with new orders', [
                'updated_entries' => $updatedEntries
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            \Log::error('Failed to update entry orders: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update orders: ' . $e->getMessage()
            ], 500);
        }

        // Get the updated entries to verify
        $updatedEntriesList = $allEntries->map(function($entry) {
            return [
                'id' => $entry->id,
                'title' => $entry->title,
                'new_order' => $entry->order,
                'main_category' => $entry->main_staff_category,
                'staff_category' => $entry->staff_category
            ];
        });

        \Log::info('Updated entries in category:', $updatedEntriesList->toArray());

        // Clear application cache including GraphQL cache
        //this works fine but we need to find optimized version of it
        \Artisan::call('cache:clear');
        \Log::info('Cleared application cache');

        \Log::info('Successfully updated all entries with new orders', [
            'updated_entries' => $updatedEntries
        ]);
        return response()->json([
            'success' => true,
            'debug' => [
                'updated_entries' => $updatedEntries,
                'current_category_entries' => $allEntries,
                'updated_entries_list' => $updatedEntriesList,
                'request_data' => [
                    'order' => $request->order,
                    'main_category' => $mainCategory,
                    'staff_category' => $staffCategory
                ]
            ]
        ]);
    }
}
