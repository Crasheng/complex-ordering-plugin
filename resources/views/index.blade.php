@extends('complex-collection-ordering::layout')

@section('heading', 'Team Members')

{{-- @section('actions')
    <a href="{{ cp_route('complex-collection-ordering.create') }}" class="btn-primary">
        Add Team Member
    </a>
@endsection --}}

@section('head')
    @parent
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .ui-sortable-helper {
            display: table;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .ui-state-highlight {
            height: 60px;
            background: #f3f4f6;
            border: 1px dashed #9ca3af;
        }
        #sortable tr {
            background: white;
            cursor: move;
            position: relative;
        }
        #sortable tr:hover {
            background: #f9fafb;
        }
        #sortable tr td:first-child {
            display: flex;
            align-items: center;
            position: relative;
            padding-left: 40px; /* Make space for drag handle */
        }
        .drag-handle {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: move;
            color: #6b7280;
            z-index: 10;
        }
        .drag-handle:hover {
            color: #374151;
        }
        .drag-handle svg {
            pointer-events: none; /* Make sure clicks go through to the handle */
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        // Submit form on page load to select first category by default
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('categoryFilterForm');
            const mainCategorySelect = document.getElementById('main_category');

            // If no category is selected yet, submit the form to select the first one
            if (form && mainCategorySelect && mainCategorySelect.options.length > 0) {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.has('main_category')) {
                    form.submit();
                }
            }
        });
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');

        // Make sure jQuery is loaded
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }
        if (typeof jQuery.ui === 'undefined' || !jQuery.ui.sortable) {
            console.error('jQuery UI Sortable is not loaded');
            return;
        }

        console.log('jQuery version:', jQuery.fn.jquery);
        console.log('jQuery UI version:', jQuery.ui ? jQuery.ui.version : 'not loaded');
        console.log('Initializing sortable...');

        // Initialize sortable with error handling
        try {
            var $sortable = $("#sortable");
            console.log('Sortable element found:', $sortable.length > 0);

            $sortable.sortable({
                handle: ".drag-handle",
                items: '> tr',
                placeholder: "ui-state-highlight",
                axis: "y",
                cursor: "move",
                opacity: 0.7,
                tolerance: "pointer",
                start: function(e, ui) {
                    console.log('Drag started');
                    ui.placeholder.height(ui.helper.outerHeight());
                },
                helper: function(e, tr) {
                    console.log('Creating helper');
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                update: function(event, ui) {
                    console.log('Update triggered');
                    const order = [];
                    $("#sortable tr").each(function() {
                        order.push($(this).data('id'));
                    });

                    console.log('Sending order:', order);
const mainCategory = $('#main_category').val();
                    const staffCategory = $('#staff_category').val();
                    $.ajax({
                        url: '{{ cp_route('complex-collection-ordering.update-order') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order,
                            main_category: mainCategory,
                            staff_category: staffCategory
                        },
                        success: function(response) {
                            console.log('Order updated successfully', response);
                            if (typeof Toast !== 'undefined') {
                                Toast.success('Order updated successfully');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating order:', error);
                            console.log('Response:', xhr.responseText);
                            if (typeof Toast !== 'undefined') {
                                Toast.error('Error updating order');
                            }
                        }
                    });
                }
            }).disableSelection();

            console.log('Sortable initialized successfully');

            // Test if sortable was initialized
            console.log('Sortable instance:', $sortable.data('ui-sortable'));

        } catch (e) {
            console.error('Error initializing sortable:', e);
        }
    });
    </script>
@endsection

@push('styles')
<style>
    .ui-sortable-helper {
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        display: table;
    }
    .ui-state-highlight {
        height: 60px;
        background: #f3f4f6;
        border: 1px dashed #9ca3af;
    }
    #sortable tr {
        background: white;
    }
    #sortable tr:hover {
        background: #f9fafb;
    }
    #sortable tr:first-child td:first-child svg {
        cursor: move;
    }
    #sortable tr td:first-child {
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('main')
    {{-- <div class="flex items-center justify-between mb-6">
        <h1>Team Members</h1>
        <a href="{{ cp_route('complex-collection-ordering.create') }}" class="btn-primary">Add Team Member</a>
    </div> --}}

    {{-- Taxonomy Filters --}}
    {{-- <div class="card p-0 mb-6"> --}}
        {{-- <div class="p-4 border-b">
            <h2 class="text-lg font-medium">Filter Team Members</h2>
        </div> --}}

        <form action="{{ cp_route('complex-collection-ordering.index') }}" method="GET" class="p-4" id="categoryFilterForm">
            <div class="flex flex-wrap gap-6">
                {{-- Main Staff Category Dropdown --}}
                <div class="flex-1">
                    <label for="main_category" class="text-sm font-medium text-gray-700 mb-1 block">Main Category</label>
                    @php
                        $firstCategory = $mainCategories->first();
                        $selectedMainCategory = $selectedMainCategory ?? $firstCategory['slug'] ?? '';
                    @endphp
                    <select name="main_category" id="main_category" class="input-text" >
                        @foreach($mainCategories as $category)
                            <option value="{{ $category['slug'] }}" {{ $selectedMainCategory == $category['slug'] ? 'selected' : '' }}>
                                {{ $category['title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Staff Category Dropdown (will be shown when a main category is selected) --}}
                <div class="flex-1">
                    <label for="staff_category" class="text-sm font-medium text-gray-700 mb-1 block">Staff Category</label>
                    <select name="staff_category" id="staff_category" class="input-text" onchange="this.form.submit()" {{ empty($selectedMainCategory) ? 'disabled' : '' }}>
                        <option value="">All Staff</option>
                        @foreach($staffCategories as $category)
                            <option value="{{ $category['slug'] }}" {{ $selectedStaffCategory == $category['slug'] ? 'selected' : '' }}>
                                {{ $category['title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset Filters Button --}}
                @if($selectedMainCategory || $selectedStaffCategory)
                    <div class="flex items-end">
                        <a href="{{ cp_route('complex-collection-ordering.index') }}" class="btn">
                            Reset Filters
                        </a>
                    </div>
                @endif
            </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mainCategorySelect = document.getElementById('main_category');
                const staffCategorySelect = document.getElementById('staff_category');
                const form = document.getElementById('categoryFilterForm');
                console.log("osman");
                console.log(mainCategorySelect.value);
                console.log(staffCategorySelect.value);
                console.log(form);
                if (mainCategorySelect) {
                    mainCategorySelect.addEventListener('change', function(e) {
                        e.preventDefault();
                        staffCategorySelect.value = '';
                        // Only reset staff category if main category actually changed
                         console.log(mainCategorySelect.value);
                        form.submit();
                    });
                }

                if (staffCategorySelect) {
                    staffCategorySelect.addEventListener('change', function(e) {
                        e.preventDefault();
                        // Just submit the form without resetting any values
                        form.submit();
                    });
                }
            });
        </script>
        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mainCategorySelect = document.getElementById('main_category');
                const staffCategorySelect = document.getElementById('staff_category');
                const form = document.getElementById('categoryFilterForm');
                console.log("osman");
                console.log(mainCategorySelect);
                console.log(staffCategorySelect);
                console.log(form);
                if (mainCategorySelect) {
                    mainCategorySelect.addEventListener('change', function() {
                        // Reset staff category when main category changes
                        if (staffCategorySelect) {
                            staffCategorySelect.value = '';
                        }
                        form.submit();
                    });
                }
            });
        </script> --}}
    <div class="p-0">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Category</th>
                    {{-- <th>Actions</th> --}}
                </tr>
            </thead>
           <tbody id="sortable" class="divide-y divide-gray-200">
        @forelse($items as $item)
        <tr class=" border-b cursor-move hover:bg-gray-50" data-id="{{ $item->id() }}" >
            <td class="flex items-center">
                <div class="drag-handle">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
                @php
                    $picture = is_array($item->picture) ? ($item->picture->url()?? null) : $item->picture;
                    $pictureUrl = isset($picture) ? $picture->url() : null;
                @endphp
                @if($pictureUrl)
                    <img src="{{ $pictureUrl }}"
                         alt="{{ $item->title }}"
                         class="w-20 h-20 rounded-full mr-3 object-cover"
                         style="width: 60px; height: 60px;"
                         loading="lazy">
                @else
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-3" style="width: 40px; height: 40px;">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @endif
                <span>{{ $item->title }}</span>
            </td>
            <td>{{ $item->get('position') }}</td>

            <td > 
    <span class="px-2 py-1 rounded-full text-xs font-medium ">
        {{ $item->get('staff_category') }}
    </span> </td>
            {{-- <td class="flex space-x-2">
                <a href="{{ cp_route('complex-collection-ordering.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                <form action="{{ cp_route('complex-collection-ordering.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team member?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </td> --}}
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center py-4">
                No team members found.
                <a href="{{ cp_route('complex-collection-ordering.create') }}" class="text-blue-500">Add one</a>.
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="mt-4 flex justify-center">
            <div class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if ($items->onFirstPage())
                    <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">
                        &laquo; Previous
                    </span>
                @else
                    <a href="{{ $items->previousPageUrl() }}" class="px-3 py-1 rounded bg-white border border-gray-300 hover:bg-gray-50">
                        &laquo; Previous
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    @if ($page == $items->currentPage())
                        <span class="px-3 py-1 rounded bg-blue-500 text-white">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded bg-white border border-gray-300 hover:bg-gray-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($items->hasMorePages())
                    <a href="{{ $items->nextPageUrl() }}" class="px-3 py-1 rounded bg-white border border-gray-300 hover:bg-gray-50">
                        Next &raquo;
                    </a>
                @else
                    <span class="px-3 py-1 rounded bg-gray-100 text-gray-400 cursor-not-allowed">
                        Next &raquo;
                    </span>
                @endif
            </div>
        </div>
    @endif

@endsection
