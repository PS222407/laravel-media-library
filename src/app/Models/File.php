<?php

namespace Jensramakers\LaravelMediaLibrary\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Database\Factories\FileFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'is_external',
        'label',
        'mime',
        'mime_icon',
        'file_category_id',
    ];

    public function getAsset()
    {
        if ($this->is_external) {
            return $this->path . $this->name;
        }

        return asset(Storage::url($this->path . $this->name));
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return FileFactory::new();
    }
}
