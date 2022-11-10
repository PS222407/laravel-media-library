<div>
    <!-- modal backdrop -->
    <div style="z-index: 1000;" data-modal-backdrop="{{ $modalName }}" class="modal-backdrop"></div>
    <!-- modal1 -->
    <div style="z-index: 2000; width: 66%; max-width: 925px" id="{{ $modalName }}" class="modal modal-media-library shadow-lg bg-gray-100">
        <button data-modal-close type="button" class="bg-red-700 text-white px-3 py-2 absolute right-0"><i class="fa-solid fa-close"></i></button>
        @livewire('media-library', ['galleryId' => $galleryId, 'containerElementId' => $containerElementId, 'inputName' => $inputName, 'form' => $form])
    </div>

    <x-input-image-container :files="$files" container-element-id="{{ $containerElementId }}" form="{{ $form }}" input-name="{{ $inputName }}"/>

    <style>
        .modal-media-library {
            height: 680px;
        }
    </style>
</div>

