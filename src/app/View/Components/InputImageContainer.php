<?php

namespace Jensramakers\LaravelMediaLibrary\app\View\Components;

use Illuminate\View\Component;

class InputImageContainer extends Component
{
    public function __construct(
        public string $containerElementId,
        public string $form,
        public string $inputName,
        public $files = [],
    ) {
    }

    public function render()
    {
        return view('laravel-media-library::input-image-container');
    }
}
