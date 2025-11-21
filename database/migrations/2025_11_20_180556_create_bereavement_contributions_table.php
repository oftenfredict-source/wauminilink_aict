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
        if (Schema::hasTable('bereavement_contributions')) {
            return; // Table already exists, skip creation
        }
        
        Schema::create('bereavement_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bereavement_event_id')->constrained('bereavement_events')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->boolean('has_contributed')->default(false); // Yes/No
            $table->decimal('contribution_amount', 10, 2)->nullable(); // Amount contributed
            $table->date('contribution_date')->nullable(); // Date of contribution
            $table->enum('contribution_type', ['family_wide', 'individual'])->default('individual');
            $table->string('payment_method')->nullable(); // cash, bank_transfer, mobile_money, etc.
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('bereavement_event_id');
            $table->index('member_id');
            $table->index('has_contributed');
            $table->index('contribution_date');
            
            // Ensure one contribution record per member per event
            $table->unique(['bereavement_event_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bereavement_contributions');
    }
};
