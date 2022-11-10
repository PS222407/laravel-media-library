<?php

namespace Jensramakers\LaravelMediaLibrary\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'label',
        'mime',
        'mime_icon',
        'file_category_id',
    ];

    public function getAsset()
    {
        return asset(Storage::url($this->path . $this->name));
    }
}
