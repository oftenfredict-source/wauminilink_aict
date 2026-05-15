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
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('receipt_type'); // Tithe, Offering, Michango, etc.
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('reference_number')->nullable();
            $table->string('file_path');
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('notes')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
