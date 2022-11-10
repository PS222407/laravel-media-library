<?php

namespace Jensramakers\LaravelMediaLibrary\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class FileCategory extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $fillable = [
        'name',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
