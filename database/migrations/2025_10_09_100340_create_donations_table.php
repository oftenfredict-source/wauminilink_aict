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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name')->nullable(); // For non-member donors
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('donation_date');
            $table->string('donation_type')->default('general'); // general, building, missions, charity, special_project
            $table->string('payment_method')->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('purpose')->nullable(); // Specific purpose for the donation
            $table->text('notes')->nullable();
            $table->string('recorded_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            $table->index(['donation_date', 'donation_type']);
            $table->index('donation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
