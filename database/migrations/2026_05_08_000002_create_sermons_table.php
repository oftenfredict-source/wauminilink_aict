<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('preacher');
            $table->date('date');
            $table->text('summary')->nullable();
            $table->string('video_url')->nullable(); // YouTube link
            $table->string('audio_url')->nullable(); // Mp3 link
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};
