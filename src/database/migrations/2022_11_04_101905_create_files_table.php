<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_category_id')->constrained();
            $table->string('name');
            $table->string('path');
            $table->boolean('is_external')->default(false);
            $table->string('label')->nullable();
            $table->string('mime');
            $table->string('mime_icon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
