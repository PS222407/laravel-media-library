# laravel media library

in controller use the trait: "FileRequestHelper"  
in model use trait: "Fileable"

Add this piece of HTML to a form, place it oustide the form tags, you can pass the form id
```php
<button data-modal-open="laptop-modal" role="button" class="p-3 bg-blue-500 mb-2">{{ __('admin.open_gallery') }}</button>
<x-input-file-selector :files="$laptop->files" gallery-id="laptop-create-gallery" container-element-id="laptop-form-images" input-name="laptop_images" form="laptop-form" modal-name="laptop-modal" />
```
```bash
php artisan vendor:publish --tag=laravel-media-library
php artisan migrate
```
add stackable-modals.js    
add imagegallery.js  
add stackable-modals.css 

and dont forget storage:link and chmod -R 777 storage/  

You can save the files like this
```php
$laptop->files()->sync($this->getFilesInOrder($request->laptop_images));
```
To retrieve files or images use code below
```php
$laptop->files();
$laptop->images();
```
