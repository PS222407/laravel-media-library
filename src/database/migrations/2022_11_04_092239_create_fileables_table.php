<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('fileables', function (Blueprint $table) {
            $table->integer('file_id');
            $table->integer('fileable_id');
            $table->string('fileable_type');
            $table->unsignedInteger('order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fileables');
    }
};
