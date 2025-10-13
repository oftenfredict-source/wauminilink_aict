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
        Schema::create('pledges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('pledge_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->date('pledge_date');
            $table->date('due_date')->nullable();
            $table->string('pledge_type')->default('building'); // building, missions, special_project, general
            $table->string('payment_frequency')->default('monthly'); // monthly, quarterly, yearly, one_time
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, completed, cancelled, overdue
            $table->string('recorded_by')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'status']);
            $table->index(['pledge_date', 'pledge_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledges');
    }
};
