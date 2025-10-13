<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json, text
            $table->string('category')->default('general'); // general, membership, finance, notifications, security, appearance
            $table->string('group')->default('basic'); // basic, advanced, system
            $table->text('description')->nullable();
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_public')->default(false); // Can be accessed without authentication
            $table->json('validation_rules')->nullable(); // Store validation rules as JSON
            $table->json('options')->nullable(); // For select/radio options
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category', 'group']);
            $table->index('is_editable');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};