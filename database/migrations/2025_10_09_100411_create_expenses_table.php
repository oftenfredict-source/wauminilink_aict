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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->nullable()->constrained()->onDelete('set null');
            $table->string('expense_category'); // utilities, maintenance, salaries, supplies, missions, etc.
            $table->string('expense_name');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('payment_method')->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->string('vendor')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, paid
            $table->string('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('recorded_by')->nullable();
            $table->timestamps();
            
            $table->index(['expense_date', 'expense_category']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
