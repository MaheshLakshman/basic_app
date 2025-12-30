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
        Schema::create('price_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->json('name');
            $table->string('billing_type', 50)->comment('monthly, usage, hourly, package');
            $table->decimal('price', 10, 2);
            $table->integer('duration_days');
            $table->integer('max_sessions')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        Schema::create('price_plan_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_plan_id');
            $table->string('rule_type', 50)->comment('attendance, time, slot, holiday');
            $table->decimal('min_value', 8, 2)->nullable(); // e.g., min hours, min attendance count
            $table->decimal('max_value', 8, 2)->nullable();
            $table->string('adjustment_type', 50)->comment('add, subtract, percentage');
            $table->decimal('adjustment_value', 10, 2);
            $table->timestamps();

            $table->foreign('price_plan_id')->references('id')->on('price_plans')->onDelete('cascade');
        });

        Schema::create('member_price_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('price_plan_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('price_plan_id')->references('id')->on('price_plans')->onDelete('cascade');
        });

        Schema::create('account_locks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('reason')->nullable();
            $table->timestamp('locked_at')->useCurrent();
            $table->timestamp('unlock_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('price_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('type', 50)->comment('discount, penalty, waiver');
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->date('applied_month')->nullable(); // For which month this applies
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_adjustments');
        Schema::dropIfExists('account_locks');
        Schema::dropIfExists('member_price_assignments');
        Schema::dropIfExists('price_plan_rules');
        Schema::dropIfExists('price_plans');
    }
};
