# Complex Collection Ordering Addon for Statamic

A powerful Statamic addon for managing the order of entries in collections with support for hierarchical categories and sub-categories. This addon provides an intuitive drag-and-drop interface for organizing your content.

## Features

- 🎯 Hierarchical ordering of entries within categories and sub-categories
- 🖱️ Intuitive drag-and-drop interface
- ⚙️ Fully configurable collection and field handles
- 🔄 Works with any Statamic collection
- 🚀 Easy to integrate with existing projects

## Requirements

- PHP 8.1+
- Statamic 4.0+
- Laravel 10.0+

## Installation

1. Add the repository to your `composer.json`:

   ```json
   {
       "repositories": [
           {
               "type": "vcs",
               "url": "https://github.com/Crasheng/complex-ordering-plugin.git"
           }
       ]
   }
   ```

2. Require the package:

   ```bash
   composer require osmanco/complex-collection:dev-main
   ```

3. Publish the configuration file:

   ```bash
   php artisan vendor:publish --tag=config --provider="OsmanCo\ComplexCollection\ServiceProvider"
   ```

## Configuration

After publishing the configuration file, you'll find it at `config/complex-collection.php`. Here's how to configure it:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Title
    |--------------------------------------------------------------------------
    |
    | The display name for the navigation item in the control panel.
    | Customize this to match your collection's purpose.
    |
    */
    'navigation_title' => 'Team Members Ordering',

    /*
    |--------------------------------------------------------------------------
    | Collection Handle
    |--------------------------------------------------------------------------
    |
    | The handle of the collection you want to manage with this addon.
    |
    | Example: 'team_members', 'products', 'portfolio_items'
    */
    'collection_handle' => 'team_members',

    /*
    |--------------------------------------------------------------------------
    | Main Category Field Handle
    |--------------------------------------------------------------------------
    |
    | The field handle for the main category in your collection's blueprint.
    | This is the top-level category for grouping entries.
    |
    | Example: 'main_staff_category', 'product_category', 'portfolio_section'
    */
    'main_category_field' => 'main_staff_category',

    /*
    |--------------------------------------------------------------------------
    | Sub Category Field Handle
    |--------------------------------------------------------------------------
    |
    | The field handle for the sub-category in your collection's blueprint.
    | This is the second-level category for more specific grouping.
    |
    | Example: 'staff_category', 'product_subcategory', 'portfolio_tags'
    */
    'sub_category_field' => 'staff_category',
];
```

## Usage

### Setting Up Your Collection

1. **Prepare Your Blueprint**
   - Ensure your collection has the category fields specified in the config
   - Add an `order` field (integer) to your collection's blueprint
   - The field should be visible in the entries table for reference

2. **Access the Ordering Interface**
   - Navigate to `/cp/complex-collection-ordering` in your admin panel
   - You'll see a hierarchical view of your categories and entries

3. **Reordering Entries**
   - Simply drag and drop entries to reorder them within their categories
   - The order will be automatically saved
   - The `order` field in your entries will be updated accordingly

## Development

### Building Assets

To compile the frontend assets, run:

```bash
npm install
npm run build
```

For development with hot-reloading:

```bash
npm run dev
```

## Troubleshooting

- **Missing Interface**: Ensure you've published the config and set the correct collection handle
- **Order Not Saving**: Verify that the `order` field exists in your blueprint
- **Categories Not Showing**: Check that your entries have values in the category fields specified in the config

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- [Your Name]
- [All Contributors](../../contributors)
