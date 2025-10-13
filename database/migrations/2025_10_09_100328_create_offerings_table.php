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
        Schema::create('offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null'); // Can be anonymous
            $table->decimal('amount', 10, 2);
            $table->date('offering_date');
            $table->string('offering_type')->default('general'); // general, special, thanksgiving, building_fund, missions
            $table->string('service_type')->nullable(); // sunday_service, special_event, etc.
            $table->foreignId('service_id')->nullable(); // Link to specific service/event
            $table->string('payment_method')->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('recorded_by')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            
            $table->index(['offering_date', 'offering_type']);
            $table->index('offering_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerings');
    }
};
