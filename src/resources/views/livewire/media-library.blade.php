<div class="bg-white h-full library">
    @inject('fileIconBladeHelper','Jensramakers\LaravelMediaLibrary\app\Classes\FileIconBladeHelper')

    {{--  tabbed navigation  --}}
    <div class="flex border-b border-black bg-gray-100">
        <div wire:click="changeTab(1)" style="margin-bottom: -2px" class="{{ $currentTab !== 1 ? 'bg-gray-200 border-x-0 border-t-0' : 'bg-white border-b-0' }} border border-black cursor-pointer rounded-t-lg px-10 py-2">Galerij</div>
        <div wire:click="changeTab(2)" style="margin-bottom: -2px" class="{{ $currentTab !== 2 ? 'bg-gray-200 border-x-0 border-t-0' : 'bg-white border-b-0' }} border border-black cursor-pointer rounded-t-lg px-10 py-2">Upload</div>
    </div>
    {{--  breadcrumbs  --}}
    <div class="flex">
        @foreach($this->previousParentCategory as $key => $breadcrumb)
            <button wire:click="changeParentDirByHistoryCollection({{ $key }})" class="underline">{{ $breadcrumb->name }}</button>&nbsp;>&nbsp;
        @endforeach
        <div class="font-bold">{{ $parentCategory->name }}</div>
    </div>

    {{--  children folder list  --}}
    <div wire:click="backToPreviousDir" class="p-1 bg-gray-200 {{ $previousParentCategory->count() !== 0 ? 'cursor-pointer hover:bg-blue-200' : '' }} r">
        {{ $previousParentCategory->count() !== 0 ? '<-- back' : '...' }}
    </div>
    <div class="overflow-y-auto mb-4 border-b">
        @foreach($childrenOfCategory as $child)
            <div wire:click="changeParentDir({{ $child->id }})" class="border-b border-t cursor-pointer hover:bg-blue-200 flex">
                <i class="fa-solid fa-folder"></i>
                <div>{{ $child->name }}</div>
                <div class="ml-10">({{ $child->files_count }})</div>
            </div>
        @endforeach
    </div>

    {{--  tab 1  --}}
    <div class="{{ $currentTab !== 1 ? 'hidden' : 'grid' }} library-tab-1">
        <div class="m-5 p-1 shadow-inner border overflow-y-auto">
            <div id="{{ $galleryId }}" class="flex flex-wrap gap-2">
                @foreach($files as $file)

                    @if ($containerElementId)
                        <input type="checkbox" class="chkbox hidden" id="{{ $inputName }}[{{ $file->id }}]" onclick="chkboxClick(event, this)">
                    @endif
                    <label for="{{ $inputName }}[{{ $file->id }}]" class="chkboxlbl relative h-min w-20 h-20 overflow-hidden" style="user-select: none; display: flex; justify-content: center">
                        <div class="absolute right-0 flex">
                            <button wire:click="edit({{ $file->id }})" type="button"
                                    class="bg-yellow-300 hover:bg-yellow-400 rounded text-white px-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button wire:click="deleteConfirmation({{ $file->id }})" type="button"
                                    class="bg-red-400 hover:bg-red-500 rounded text-white px-2">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <div class="bg-black/40 text-white px-1 absolute bottom-0 ucfirst font-bold">{{ $file->type }}</div>

                        @if(str_starts_with($file->mime, 'image'))
                            <img src="{{ $file->getAsset() }}" data-file-type="img" data-image-type="{{ $file->type }}" alt="file from uploads" class="object-cover h-full w-full">
                        @else
                            <div class="grid mb-auto">
                                <i class="fa-solid {{ $file->mime_icon }}" data-file-icon="{{ $file->mime_icon }}" data-file-type="other" style="align-self: end; font-size: 3rem"></i>
                                <span data-text class="overflow-hidden text-sm">{{ substr($file->name, 12) }}</span>
                            </div>
                        @endif
                    </label>

                @endforeach
            </div>
        </div>

        @if($form)
            <button type="button" onclick="formImagesExtr('{{ $containerElementId }}'); return false;" class="px-3 py-1 bg-input_color rounded">
                add to form
            </button>
        @endif
    </div>

    {{--  tab 2  --}}
    <div class="{{ $currentTab !== 2 ? 'hidden' : '' }} p-5">
        @error('createCategoryName') <span class="text-red-500">{{ $message }}</span>@enderror
        <form wire:submit.prevent="createCategory" class="mb-4">
            <label for="upload-iteration-{{ $uploadIteration }}">Create new directory</label>
            <div class="flex gap-x-1">
                <input type="text" wire:model.defer="createCategoryName" class="focus:outline-none focus:ring-0 focus:border-input_color" />
                <button class="px-3 py-1 bg-input_color rounded" type="submit">submit</button>
            </div>
        </form>
        @error('uploadFiles') <span class="text-red-500">{{ $message }}</span> <br>@enderror
        @error('uploadFiles.*') <span class="text-red-500">{{ $message }}</span> <br>@enderror
        <form wire:submit.prevent="store" class="flex gap-x-1 mb-4">
            <input wire:model="uploadFiles" type="file" multiple id="upload-iteration-{{ $uploadIteration }}" class="block bg-gray-50 rounded-lg border cursor-pointer focus:outline-none focus:ring-0 focus:border-input_color">
            @if(config('medialibrary.label'))
                <select wire:model.defer="uploadLabel" class="focus:outline-none focus:ring-0 focus:border-input_color">
                    <option value="" selected="selected">--- select option ---</option>
                    @foreach(config('medialibrary.label_options') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            @endif
            <button class="px-3 py-1 bg-input_color rounded" type="submit">submit</button>
        </form>
        <div class="p-1 shadow-inner border overflow-y-auto h-[292.5px]">
            @if ($uploadFiles)
                <div class="flex flex-wrap gap-2">
                    @foreach($uploadFiles as $uploadFile)
                        <div class="w-20 h-20 overflow-hidden">
                            @if(str_starts_with($uploadFile->getMimeType(), 'image'))
                                <img src="{{ $uploadFile->temporaryUrl() }}" alt="uploaded file" class="object-cover h-full w-full">
                            @else
                                <div class="grid mb-auto">
                                    <i class="fa-solid {{ $fileIconBladeHelper->getIconByMimeTYpe($uploadFile->getMimeType()) }}" data-file-icon="{{ $fileIconBladeHelper->getIconByMimeTYpe($uploadFile->getMimeType()) }}" data-file-type="other" style="font-size: 4rem; width: 100%; text-align: center"></i>
                                    <span data-text class="overflow-hidden text-sm">{{ $uploadFile->getClientOriginalName() }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- edit modal  --}}
    <div style="z-index: 3000;" data-modal-backdrop="edit-file-modal" class="modal-backdrop"></div>
    <div style="z-index: 4000;" id="edit-file-modal" class="modal bg-white">
        <button data-modal-close type="button" style="position: absolute" class="bg-red-700 text-white px-3 py-2 absolute right-0"><i class="fa-solid fa-close"></i></button>
        <form wire:submit.prevent="update" class="mt-10">
            <div class="flex flex-col px-2 mb-2">
                <label for="imgLabel">Label</label>
                <select wire:model.defer="imgLabel" id="imgLabel" class="focus:outline-none focus:ring-0 focus:border-input_color">
                    <option value="FRONT">front</option>
                    <option value="BACK">back</option>
                </select>
            </div>
            <div class="flex flex-col px-2 mb-2">
                <label for="imgCategory">Category</label>
                <select wire:model.defer="imgCategory" id="imgCategory" class="focus:outline-none focus:ring-0 focus:border-input_color">
                    {!! $options !!}
                </select>
            </div>
            <div class="px-2">
                <button class="px-3 py-1 bg-input_color rounded ml-auto block" type="submit">submit</button>
            </div>
        </form>

        <div class="m-4">
            @if(str_starts_with($editFile?->mime, 'image'))
                <img src="{{ $editFile?->getAsset() }}" data-image-type="{{ $editFile->type }}" alt="edit file" class="object-cover h-full w-full">
            @else
                <i class="fa-solid text-center w-full {{ $editFile?->mime_icon }}" style="align-self: end; font-size: 7rem"></i>
                <span>{{ substr($editFile?->name, 12) }}</span>
            @endif
        </div>
    </div>

    {{-- delete modal  --}}
    <div style="z-index: 3000;" data-modal-backdrop="delete-file-modal" class="modal-backdrop"></div>
    <div style="z-index: 4000; width: auto; border-color: black; border-radius: 5px" id="delete-file-modal" class="modal">
        <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-gray-200 rounded-t-md bg-white">
            <div class="mr-5 w-12 h-12 capitalize text-red-500 bg-red-200 border rounded-full relative">
                <i class="fa-solid fa-triangle-exclamation absolute left-1/2 top-1/2 text-3xl" style="transform: translate(-50%, -50%);"></i>
            </div>
            <h5 class="text-xl font-medium leading-normal text-gray-800" id="deletionModal_ScrollableLabel">
                {{ __('admin.delete_confirmation') }}
            </h5>
            <button type="button"
                    class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                    data-modal-close></button>
        </div>
        <div class="modal-body relative">

        </div>
        <div class="modal-footer bg-gray-100 flex flex-shrink-0 flex-wrap items-center justify-end p-4 rounded-b-md">
            <form wire:submit.prevent="deleteFile">
                <button type="button"
                        class="inline-block border border-gray-500 px-6 py-2.5 bg-white font-medium text-xs leading-tight capitalize rounded shadow-md hover:bg-gray-100 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-200 transition duration-150 ease-in-out"
                        data-modal-close>
                    {{ __('admin.cancel') }}
                </button>
                <button type="submit" class="inline-block px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight capitalize rounded shadow-md hover:bg-red-800 hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:bg-red-900 transition duration-150 ease-in-out ml-1">
                    {{ __('admin.delete') }}
                </button>
            </form>
        </div>
    </div>


    <style>
        .library {
            display: grid;
            grid-template-rows: 40px 25px 30px 135px 450px;
        }

        .library-tab-1 {
            grid-template-rows: 1fr 40px;
        }


        .highlight {
            outline: 40px solid rgba(0, 187, 255, 0.6) !important;
            outline-offset: -40px;
            overflow: hidden;
            position: relative;
        }

        .image-form-container img {
            height: 100%;
            width: 100px;
            object-fit: cover;
        }

        .image-form-container > div {
            display: flex;
        }

        .image-form-container label > div {
            height: 100%;
        }
    </style>

    <script>
        // =================================================================================
        // BUTTON
        // =================================================================================
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                deselectAll();
            }
        });
        window.addEventListener('imgDirChanged', event => {
            constructFileGalleryCheckboxes();
        });

        function formImagesExtr(containerElementId, fmName, imgGalleryId = '{{ $galleryId }}') {
            setTimeout(function () {
                modalClose();
            }, 250);
            const checkboxes = document.getElementById(imgGalleryId).getElementsByTagName('input');
            const files = document.querySelectorAll('[data-file-type]');
            const container = document.getElementById(containerElementId);

                    {{--// when only 1 image needs to be selected--}}
                    {{--@if($inputElementId)--}}
                    {{--    formThumbnail.innerHTML = '';--}}
                    {{--@endif--}}

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    let div = document.createElement('div');

                    let input = document.createElement('input');
                    input.setAttribute('type', 'checkbox');
                    input.style.display = 'none';
                    input.checked = true;
                    input.setAttribute('name', `${checkboxes[i].id}`);
                    @if($form)
                    input.setAttribute('form', '{{ $form }}');
                    @endif

                    let label = document.createElement('label');
                    label.classList.add('relative');

                    let imgContainer = document.createElement('div');
                    // imgContainer.classList.add('img-tag-'+ images[i].getAttribute('data-image-type') +'-container');

                    console.log()
                    let cpImage;
                    if (files[i].getAttribute('data-file-type') === 'img') {
                        cpImage = document.createElement('img');
                        cpImage.src = files[i].src;
                    } else {
                        cpImage = document.createElement('div');
                        cpImage.classList.add('grid', 'mb-auto');

                        let iIcon = document.createElement('i');
                        iIcon.classList.add('fa-solid', files[i].getAttribute('data-file-icon'));
                        iIcon.setAttribute('data-file-icon', files[i].getAttribute('data-file-icon'));
                        iIcon.setAttribute('data-file-type', 'other');
                        iIcon.style.fontSize = '3rem';

                        let spanTruncate = document.createElement('span');
                        spanTruncate.classList.add('overflow-hidden', 'text-sm', 'truncate', 'max-w-[100px]');
                        spanTruncate.innerHTML = files[i].parentElement.querySelector('[data-text]').innerHTML;

                        cpImage.appendChild(iIcon);
                        cpImage.appendChild(spanTruncate);
                    }

                    let buttonLeft = document.createElement('button');
                    let buttonRight = document.createElement('button');
                    buttonLeft.innerHTML = '<i class="fa-solid fa-arrow-left"></i>';
                    buttonRight.innerHTML = '<i class="fa-solid fa-arrow-right"></i>';
                    buttonLeft.classList.add('bg-gray-400', 'p-2');
                    buttonRight.classList.add('bg-gray-400', 'p-2');
                    buttonLeft.onclick = function () {
                        imgToLeft(this);
                        return false;
                    }
                    buttonRight.onclick = function () {
                        imgToRight(this);
                        return false;
                    }

                    let buttonDelete = document.createElement('button');
                    buttonDelete.classList.add('bg-red-400', 'p-2');
                    buttonDelete.onclick = function () {
                        imgDelete(this);
                        return false;
                    };
                    let trashIcon = document.createElement('i');
                    trashIcon.classList.add('fa-solid', 'fa-trash');

                    label.appendChild(imgContainer);
                    imgContainer.appendChild(cpImage);
                    div.appendChild(input);
                    // when only 1 image needs to be selected
                    @if($multiple === true)
                    div.appendChild(buttonLeft);
                    @endif
                    div.appendChild(label);
                    // when only 1 image needs to be selected
                    @if($multiple === true)
                    div.appendChild(buttonRight);
                    @endif
                    div.appendChild(buttonDelete);
                    buttonDelete.appendChild(trashIcon);
                    container.appendChild(div);

                    // when only 1 image needs to be selected
                    @if($multiple === false)
                        break;
                    @endif
                }
                checkboxes[i].checked = false;
            }
            deselectAll();
        }

        function imgToLeft(x) {
            const parentEl = x.parentElement;
            const parentParentEl = parentEl.parentElement;
            const leftSib = parentEl.previousElementSibling;
            if (!leftSib) return;

            parentParentEl.insertBefore(parentEl, leftSib);
        }

        function imgToRight(x) {
            const parentEl = x.parentElement;
            const parentParentEl = parentEl.parentElement;
            const rightSib = parentEl.nextElementSibling;
            if (!rightSib) return;

            parentParentEl.insertBefore(rightSib, parentEl);
        }

        function imgDelete(x) {
            const parentEl = x.parentElement;
            parentEl.remove();
        }
    </script>
</div>
