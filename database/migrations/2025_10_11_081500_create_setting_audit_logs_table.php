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
        Schema::create('setting_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('action'); // created, updated, deleted, reset, imported, exported
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional context data
            $table->timestamps();
            
            $table->index(['setting_key', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('action');
            $table->index('created_at');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_audit_logs');
    }
};
