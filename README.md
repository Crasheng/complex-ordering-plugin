# Complex Collection Ordering Addon for Statamic

A flexible addon for managing the order of entries in Statamic collections with support for categories and sub-categories.

## Features

- Order entries within categories and sub-categories
- Drag-and-drop reordering
- Configurable collection and field handles
- Works with any Statamic collection

## Installation

1. Install the addon:
   ```bash
   composer require osmanco/complex-collection
   ```

2. Publish the configuration file:
   ```bash
   php artisan vendor:publish --tag=config --provider="Osmanco\ComplexCollection\ServiceProvider"
   ```

## Configuration

Edit the published config file at `config/complex-collection.php`:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Collection Handle
    |--------------------------------------------------------------------------
    |
    | The handle of the collection you want to manage with this addon.
    |
    */
    'collection_handle' => 'team_members',

    /*
    |--------------------------------------------------------------------------
    | Main Category Field Handle
    |--------------------------------------------------------------------------
    |
    | The field handle for the main category in your collection's blueprint.
    |
    */
    'main_category_field' => 'main_staff_category',

    /*
    |--------------------------------------------------------------------------
    | Sub Category Field Handle
    |--------------------------------------------------------------------------
    |
    | The field handle for the sub-category in your collection's blueprint.
    |
    */
    'sub_category_field' => 'staff_category',
];
```

## Usage

1. Ensure your collection has the category fields specified in the config
2. Add an `order` field to your collection's blueprint
3. Access the ordering interface at `/cp/complex-collection-ordering`

## Requirements

- PHP 8.1+
- Statamic 4.0+
- Laravel 10.0+

## License

MIT

## Credits

[Your Name]
