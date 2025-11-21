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
        Schema::create('budget_offering_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->string('offering_type'); // general, building_fund, special, etc.
            $table->decimal('allocated_amount', 12, 2); // Amount allocated from this offering type
            $table->decimal('used_amount', 12, 2)->default(0); // Amount actually used from this offering type
            $table->decimal('available_amount', 12, 2); // Available amount in this offering type at time of allocation
            $table->boolean('is_primary')->default(false); // Whether this is the primary funding source
            $table->text('notes')->nullable(); // Notes about why this allocation was made
            $table->timestamps();
            
            $table->index(['budget_id', 'offering_type']);
            $table->index('offering_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_offering_allocations');
    }
};