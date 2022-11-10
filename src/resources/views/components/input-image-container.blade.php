<div id="{{ $containerElementId }}" class="image-form-container flex flex-wrap gap-4">
    @foreach($files as $file)
        <div>
            <input type="checkbox" form="{{ $form }}" name="{{ $inputName }}[{{ $file->id }}]" checked="checked" class="hidden">
            <button onclick="imgToLeft(this); return false;" class="bg-gray-400 p-2"><i class="fa-solid fa-arrow-left"></i></button>
            <label class="relative">
                <div class="img-tag-{{ $file->type }}-container">
                    @if(str_starts_with($file->mime, 'image'))
                        <img src="{{ $file->getAsset() }}" alt="product image">
                    @else
                        <div class="grid mb-auto">
                            <i class="fa-solid {{ $file->mime_icon }}" data-file-icon="{{ $file->mime_icon }}" data-file-type="other" style="align-self: end; font-size: 3rem"></i>
                            <span data-text class="overflow-hidden text-sm truncate max-w-[100px]">{{ substr($file->name, 12) }}</span>
                        </div>
                    @endif
                </div>
            </label>
            <button onclick="imgToRight(this); return false;" class="bg-gray-400 p-2"><i class="fa-solid fa-arrow-right"></i></button>
            <button onclick="imgDelete(this); return false;" class="bg-red-400 p-2">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    @endforeach
</div>
