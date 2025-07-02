<?php

namespace Osmanco\ComplexCollection\Models;

use Statamic\Facades\Entry;
use Statamic\Entries\Entry as StatamicEntry;

class Item
{
    protected static $collection = 'team_members';

    public $id;
    public $title;
    public $picture;
    public $position;
    public $staff_category;
    public $main_staff_category;
    public $created_at;
    public $updated_at;

    public static function all()
    {
        return Entry::query()
            ->where('collection', self::$collection)
            ->get()
            ->map(function (StatamicEntry $entry) {
                return self::fromEntry($entry);
            });
    }

    public static function find(string $id): ?self
    {
        $entry = Entry::find($id);
        
        if (!$entry || $entry->collectionHandle() !== self::$collection) {
            return null;
        }

        return self::fromEntry($entry);
    }

    public static function create(array $attributes): self
    {
        $entry = Entry::make()
            ->collection(self::$collection)
            ->blueprint('team_member')
            ->data([
                'title' => $attributes['title'],
                'picture' => $attributes['picture'] ?? null,
                'position' => $attributes['position'] ?? null,
                'staff_category' => $attributes['staff_category'] ?? null,
                'main_staff_category' => $attributes['main_staff_category'] ?? null,
            ]);

        $entry->save();

        return self::fromEntry($entry);
    }

    public function update(array $attributes): self
    {
        $entry = Entry::find($this->id);
        
        if (!$entry) {
            throw new \Exception("Entry not found");
        }

        $entry->merge([
            'title' => $attributes['title'] ?? $this->title,
            'picture' => $attributes['picture'] ?? $this->picture,
            'position' => $attributes['position'] ?? $this->position,
            'staff_category' => $attributes['staff_category'] ?? $this->staff_category,
            'main_staff_category' => $attributes['main_staff_category'] ?? $this->main_staff_category,
        ]);

        $entry->save();

        // Refresh the instance
        return self::fromEntry($entry);
    }

    public function delete(): bool
    {
        $entry = Entry::find($this->id);
        
        if ($entry) {
            $entry->delete();
            return true;
        }
        
        return false;
    }

    public static function paginate($perPage = 10)
    {
        return Entry::query()
            ->where('collection', self::$collection)
            ->orderBy('title')
            ->paginate($perPage)
            ->through(function (StatamicEntry $entry) {
                return self::fromEntry($entry);
            });
    }

    protected static function fromEntry(StatamicEntry $entry): self
    {
        $item = new self();
        $item->id = $entry->id();
        $item->title = $entry->get('title');
        
        // Handle picture field which might be an array
        $picture = $entry->get('picture');
        $item->picture = is_array($picture) ? ($picture[0] ?? null) : $picture;
        
        $item->position = $entry->get('position');
        $item->staff_category = $entry->get('staff_category');
        $item->main_staff_category = $entry->get('main_staff_category');
        $item->created_at = $entry->date();
        $item->updated_at = $entry->lastModified();
        
        return $item;
    }
}
