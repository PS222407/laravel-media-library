<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jensramakers\LaravelMediaLibrary\app\Models\FileCategory;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('file_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->nestedSet();
            $table->timestamps();
        });

        FileCategory::create([
            'name' => 'root',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('file_categories');
    }
};
