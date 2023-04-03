# laravel media library

in controller use the trait: "FileRequestHelper"  
in model use trait: "Fileable"

Add this piece of HTML to a form, place it oustide the form tags, you can pass the form id
```php
<button data-modal-open="laptop-modal" role="button" class="btn-primary mb-3">{{ __('admin.open_gallery') }}</button>
<x-input-file-selector :files="$laptop->files" gallery-id="laptop-create-gallery" container-element-id="laptop-form-images" input-name="laptop_images" form="laptop-form" modal-name="laptop-modal" />
```
or when you dont want it inside of a form but to be static on one page you can you can use the livewire tag
```php
 @livewire('media-library')
```
```bash
php artisan vendor:publish --tag=laravel-media-library
php artisan migrate
```
add stackable-modals.js    
add medialibrary.js  
add stackable-modals.css 
THESE FILES ARE LISTED BELOW

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
## stackable-modal.js
```js
let activeModals = [];
let modalOpenButtons = document.querySelectorAll('[data-modal-open]');
let modalCloseButtons = document.querySelectorAll('[data-modal-close]');
let modalBackdrops = document.querySelectorAll('[data-modal-backdrop]');

modalOpenButtons.forEach(el => {
    el.addEventListener('click', function () {
        openModal(el.getAttribute('data-modal-open'));
    });
});
[...modalCloseButtons, ...modalBackdrops].forEach(el => {
    el.addEventListener('click', function () {
        modalClose();
    });
});

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.querySelector(`[data-modal-backdrop="${modalId}"]`);

    activeModals.push(modal);

    backdrop.style.display = 'inline-block';
    modal.style.display = 'inline-block';
    modal.style.animationName = 'modalOpen';
    modal.style.animationDuration = '.2s';
    modal.style.animationFillMode = 'both';
}

function modalClose() {
    const modal = activeModals[activeModals.length -1];
    const backdrop = document.querySelector(`[data-modal-backdrop="${modal.id}"]`);

    activeModals.pop();

    backdrop.style.display = 'none';
    modal.style.animationName = 'modalClose';
    modal.style.animationDuration = '.2s';
    modal.style.animationFillMode = 'both';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 200);
}

//    livewire events
Livewire.on('openEditModal', function() {
    openModal('edit-file-modal');
})
Livewire.on('openDeleteModal', function() {
    openModal('delete-file-modal');
})
Livewire.on('closeModal', function() {
    modalClose();
})
```
## medialibrary.js
```js
let $chkboxes = $('.chkbox');
let lastChecked = null;

constructFileGalleryCheckboxes = function () {
    $chkboxes = $('.chkbox');
    deselectAll();
}

deselectAll = function () {
    $chkboxes.each(function () {
        $(this).prop("checked", false);
    });
    highLightCheckBoxLabels();
}

chkboxClick = function(e, box) {
    if (!lastChecked) {
        lastChecked = box;
        highLightCheckBoxLabels();
        return;
    }

    if (e.shiftKey) {
        const start = $chkboxes.index(box);
        const end = $chkboxes.index(lastChecked);

        $chkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).prop('checked', lastChecked.checked);
    }

    lastChecked = box;
    highLightCheckBoxLabels();
};

function highLightCheckBoxLabels() {
    $chkboxes.each(function () {
        if ($(this).is(":checked")) {
            $(this).next().addClass('highlight');
        } else {
            $(this).next().removeClass('highlight');
        }
    })
}
```
## stackablemodal.css
```css
.modal-backdrop {
    display: none;
    position: absolute;
    background-color: rgba(0, 0, 0, 0.2);
    height: 100%;
    width: 100%;
}

.modal {
    width: 250px;
    position: absolute;
    transform: translate(-50%, -50%);
    left: 50%;
    top: 50%;
    display: none;
    border: white 1px solid;
}

@keyframes modalOpen {
    0%   {
        top: calc(50% - 100px);
        opacity: 0;
    }
    100% {
        opacity: 1;
        left: 50%;
        top: 50%;
    }
}
@keyframes modalClose {
    0%   {
        opacity: 1;
        left: 50%;
        top: 50%;
    }
    100% {
        top: calc(50% - 100px);
        opacity: 0;
    }
}
```
