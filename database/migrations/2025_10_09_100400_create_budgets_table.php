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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name');
            $table->string('budget_type')->default('annual'); // annual, monthly, quarterly, project
            $table->year('fiscal_year');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_budget', 12, 2);
            $table->decimal('allocated_amount', 12, 2)->default(0);
            $table->decimal('spent_amount', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, completed, cancelled, draft
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            $table->index(['fiscal_year', 'budget_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
