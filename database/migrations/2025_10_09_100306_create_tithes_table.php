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
        Schema::create('tithes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('tithe_date');
            $table->string('payment_method')->default('cash'); // cash, check, bank_transfer, mobile_money
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('recorded_by')->nullable(); // User who recorded the tithe
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            $table->index(['member_id', 'tithe_date']);
            $table->index('tithe_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tithes');
    }
};
