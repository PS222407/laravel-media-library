<?php

namespace Jensramakers\LaravelMediaLibrary\app\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class InputFileSelector extends Component
{
    public function __construct(
        public string $galleryId,
        public string $containerElementId,
        public string $inputName,
        public string $form,
        public string $modalName,
        public $files = new Collection(),
    ) {
    }

    public function render()
    {
        return view('laravel-media-library::input-file-selector');
    }
}
