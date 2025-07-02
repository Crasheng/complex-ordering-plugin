@extends('statamic::layout')

@section('title', $title)

@section('content')
    <div class="flex flex-col lg:flex-row gap-4">
        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <div class="publish-tab-outer">
                <div class="publish-tab-wrapper w-full min-w-0">
                    <div data-tab-handle="main" tabindex="0" class="publish-tab tab-panel w-full">
                        <div class="publish-sections">
                            <div class="publish-sections-section">
                                <div class="p-0 card">
                                    <div class="publish-fields @container">
                                        <!-- Name Field -->
                                        <div class="form-group publish-field publish-field__title text-fieldtype w-full has-field-label">
                                            <div class="field-inner">
                                                <label for="field_title" class="publish-field-label">
                                                    <span class="rtl:ml-1 ltr:mr-1">Name</span>
                                                    <i class="required rtl:ml-1 ltr:mr-1">*</i>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="input-group">
                                                    <input id="field_title" name="title" type="text"
                                                        value="{{ old('title', $item->title ?? '') }}"
                                                        class="input-text" required>
                                                </div>
                                            </div>
                                            @error('title')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Position Field -->
                                        <div class="form-group publish-field publish-field__position text-fieldtype w-full has-field-label">
                                            <div class="field-inner">
                                                <label for="field_position" class="publish-field-label">
                                                    <span class="rtl:ml-1 ltr:mr-1">Position</span>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="input-group">
                                                    <input id="field_position" name="position" type="text"
                                                        value="{{ old('position', $item->position ?? '') }}"
                                                        class="input-text">
                                                </div>
                                            </div>
                                            @error('position')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Picture Field -->
                                        <div class="form-group publish-field publish-field__picture assets-fieldtype w-full has-field-label">
                                            <div class="field-inner">
                                                <label for="field_picture" class="publish-field-label">
                                                    <span class="rtl:ml-1 ltr:mr-1">Picture</span>
                                                </label>
                                            </div>
                                            <div class="@container">
                                                <div>
                                                    <div class="">
                                                        <div class="assets-fieldtype-drag-container">
                                                            <div class="assets-fieldtype-picker gap-x-4 gap-y-2 is-expanded">
                                                                <button type="button" tabindex="0" class="btn btn-with-icon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4 text-gray-800">
                                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 9V6a1.5 1.5 0 0 0-1.5-1.5h-12V3a1.5 1.5 0 0 0-1.5-1.5h-4.5A1.5 1.5 0 0 0 .75 3v17.8a1.7 1.7 0 0 0 3.336.438l2.351-11.154A1.5 1.5 0 0 1 7.879 9H21.75a1.5 1.5 0 0 1 1.45 1.886l-2.2 10.5a1.5 1.5 0 0 1-1.45 1.114H2.447"></path>
                                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 12.375a.375.375 0 1 1-.375.375.375.375 0 0 1 .375-.375m8.629 7.125-2.911-4.365a.75.75 0 0 0-1.238-.015l-2 2.851-1.23-.989a.75.75 0 0 0-1.092.17L8.34 19.5"></path>
                                                                    </svg>
                                                                    <span>Browse</span>
                                                                </button>
                                                                <p class="flex-1 asset-upload-control">
                                                                    <button type="button" class="upload-text-button">Upload file</button>
                                                                    <span class="drag-drop-text">or drag & drop here to replace.</span>
                                                                </p>
                                                            </div>
                                                            @if(isset($item->picture) && $item->picture)
                                                                <div class="asset-table-listing">
                                                                    <table class="table-fixed">
                                                                        <tbody>
                                                                            <tr class="cursor-grab bg-white hover:bg-gray-100 asset-row">
                                                                                <td class="flex items-center h-full">
                                                                                    <button class="w-7 h-7 cursor-pointer whitespace-nowrap flex items-center justify-center">
                                                                                        <img src="{{ $item->picture }}" alt="{{ $item->title }}" class="asset-thumbnail max-h-full max-w-full rounded w-7 h-7 object-cover">
                                                                                    </button>
                                                                                    <button class="flex items-center flex-1 rtl:mr-3 ltr:ml-3 text-xs rtl:text-right ltr:text-left truncate w-full">
                                                                                        {{ basename($item->picture) }}
                                                                                    </button>
                                                                                    <button type="button" class="asset-set-alt text-blue px-4 text-sm hover:text-black">
                                                                                        Set Alt
                                                                                    </button>
                                                                                    <div class="hidden @xs:inline asset-filesize text-xs text-gray-600 px-2">
                                                                                        {{ round(filesize(public_path($item->picture)) / 1024, 2) }} KB
                                                                                    </div>
                                                                                </td>
                                                                                <td class="p-0 w-8 rtl:text-left ltr:text-right align-middle">
                                                                                    <button type="button" class="remove-asset flex items-center p-1 w-6 h-8 text-lg antialiased text-gray-600 hover:text-gray-900">
                                                                                        ×
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('picture')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Hidden Order Field -->
                                        <div class="form-group publish-field publish-field__order text-fieldtype w-full has-field-label" style="display: none;">
                                            <div class="field-inner">
                                                <label for="field_order" class="publish-field-label">
                                                    <span class="rtl:ml-1 ltr:mr-1">Order</span>
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="input-group">
                                                    <input id="field_order" name="order" type="number"
                                                        value="{{ old('order', $item->order ?? 0) }}"
                                                        class="input-text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="publish-sidebar">
            <div class="publish-tab">
                <!-- Published Toggle -->
                <div class="publish-tab-actions as-sidebar">
                    <div>
                        <div class="card p-0 mb-5">
                            <div class="flex items-center justify-between px-4 py-2">
                                <label class="publish-field-label font-medium">Published</label>
                                <button type="button" aria-pressed="true" aria-label="Toggle Button" class="toggle-container on">
                                    <div class="toggle-slider">
                                        <div tabindex="0" class="toggle-knob"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="publish-sections">
                    <div class="publish-sections-section">
                        <div class="p-0 card">
                            <div class="publish-fields @container">
                                <!-- Slug Field -->
                                <div class="form-group publish-field publish-field__slug slug-fieldtype w-full has-field-label">
                                    <div class="field-inner">
                                        <label for="field_slug" class="publish-field-label">
                                            <span class="rtl:ml-1 ltr:mr-1">Slug</span>
                                        </label>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <div class="input-group">
                                                <input id="field_slug" name="slug" type="text"
                                                    value="{{ old('slug', $item->slug ?? '') }}"
                                                    dir="ltr" class="input-text font-mono text-xs">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Staff Category -->
                                <div class="form-group publish-field publish-field__main_staff_category relationship-fieldtype w-full has-field-label">
                                    <div class="field-inner">
                                        <label for="field_main_staff_category" class="publish-field-label">
                                            <span class="rtl:ml-1 ltr:mr-1">Main Staff Category</span>
                                        </label>
                                    </div>
                                    <div class="relationship-input">
                                        <select name="main_staff_category" id="main_staff_category" class="w-full" required>
                                            <option value="">Select a main category</option>
                                            @php
                                                $mainCategories = \Statamic\Facades\Term::query()
                                                    ->where('taxonomy', 'main_staff_category')
                                                    ->get()
                                                    ->map(function ($term) {
                                                        return [
                                                            'slug' => $term->slug(),
                                                            'title' => $term->title()
                                                        ];
                                                    });
                                            @endphp
                                            @foreach($mainCategories as $category)
                                                <option value="{{ $category['slug'] }}"
                                                    {{ old('main_staff_category', (isset($item) && $item->main_staff_category) ? (is_object($item->main_staff_category) ? $item->main_staff_category->slug() : $item->main_staff_category) : '') == $category['slug'] ? 'selected' : '' }}>
                                                    {{ $category['title'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('main_staff_category')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Staff Category -->
                                <div class="form-group publish-field publish-field__staff_category relationship-fieldtype w-full has-field-label">
                                    <div class="field-inner">
                                        <label for="field_staff_category" class="publish-field-label">
                                            <span class="rtl:ml-1 ltr:mr-1">Staff Category</span>
                                        </label>
                                    </div>
                                    <div class="relationship-input">
                                        <select name="staff_category" id="staff_category" class="w-full" required
                                            @if(isset($item) && $item->staff_category)
                                                data-current-value="{{ is_object($item->staff_category) ? $item->staff_category->slug() : $item->staff_category }}"
                                            @endif>
                                            <option value="">Select a main category first</option>
                                        </select>
                                        @error('staff_category')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Parent (if needed) -->
                                <div class="form-group publish-field publish-field__parent relationship-fieldtype w-full has-field-label">
                                    <div class="field-inner">
                                        <label for="field_parent" class="publish-field-label">
                                            <span class="rtl:ml-1 ltr:mr-1">Parent</span>
                                        </label>
                                    </div>
                                    <div class="relationship-input">
                                        <input type="text" id="field_parent" class="input-text" placeholder="No parent" readonly>
                                        <input type="hidden" name="parent" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200 mt-6">
        <a href="{{ cp_route('complex-collection-ordering.index') }}" class="btn">
            Cancel
        </a>
        <button type="submit" class="btn-primary">
            {{ $submitText ?? 'Save Team Member' }}
        </button>
    </div>
</form>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mainCategorySelect = document.getElementById('main_staff_category');
                const staffCategorySelect = document.getElementById('staff_category');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const form = document.querySelector('form');

                // Prevent form submission when changing main category
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Only prevent if the event was triggered by the main category select
                        if (e.submitter && e.submitter.id === 'main_staff_category') {
                            e.preventDefault();
                            return false;
                        }
                        return true;
                    });
                }

                if (mainCategorySelect) {
                    // Load staff categories when main category changes
                    mainCategorySelect.addEventListener('change', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const mainCategory = this.value;
                        console.log('Main category selected:', mainCategory);

                        // Clear and disable staff category select
                        staffCategorySelect.innerHTML = '<option value="">Loading categories...</option>';
                        staffCategorySelect.disabled = true;

                        if (!mainCategory) {
                            staffCategorySelect.innerHTML = '<option value="">Select a main category first</option>';
                            staffCategorySelect.disabled = false;
                            return;
                        }

                        console.log(mainCategory);
                        // Fetch staff categories for the selected main category
                        console.log("osman");
                        fetch(`/cp/complex-collection-ordering/staff-categories/${mainCategory}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(categories => {
                            staffCategorySelect.innerHTML = '<option value="">Select a category</option>';

                            if (categories && categories.length > 0) {
                                categories.forEach(category => {
                                    const option = document.createElement('option');
                                    option.value = category.slug;
                                    option.textContent = category.title;

                                    // Check if this option should be selected
                                    const currentValue = staffCategorySelect.getAttribute('data-current-value');
                                    if (currentValue && currentValue === category.slug) {
                                        option.selected = true;
                                    }

                                    staffCategorySelect.appendChild(option);
                                });
                            } else {
                                staffCategorySelect.innerHTML = '<option value="">No categories found</option>';
                            }

                            staffCategorySelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error loading staff categories:', error);
                            staffCategorySelect.innerHTML = '<option value="">Error loading categories</option>';
                            staffCategorySelect.disabled = false;
                        });
                    });

                    // Trigger change event if main category is already selected
                    if (mainCategorySelect.value) {
                        const event = new Event('change');
                        mainCategorySelect.dispatchEvent(event);
                    }
                }
            });
        </script>
    @endpush
@endsection
