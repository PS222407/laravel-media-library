<?php

namespace Jensramakers\LaravelMediaLibrary\app\Traits;

use Jensramakers\LaravelMediaLibrary\app\Models\File;

trait Fileable
{
    public function files()
    {
        return $this->morphToMany(File::class, 'fileable')->orderBy('order');
    }

    public function images()
    {
        return $this->morphToMany(File::class, 'fileable')->where('mime', 'like','image%')->orderBy('order');
    }

    public static function boot () {
        parent::boot();

        self::deleting(function ($value) {
            $value->files()->sync([]);
        });
    }
}