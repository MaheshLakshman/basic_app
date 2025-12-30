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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            
            $table->string('attendance_type', 50)->comment('member | staff');
            $table->date('attendance_day')->index();
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('session_no')->default(1)->comment('1,2,3 for multi-entry');
            $table->string('source', 50)->default('manual')->comment('manual | qr | biometric | app');
            $table->string('status', 50)->default('present')->comment('present | absent | half_day');
            $table->boolean('is_billable')->default(true);
            
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('attendance_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            
            $table->string('rule_name');
            $table->string('applies_to', 50)->comment('member | staff');
            $table->string('rule_type', 50)->comment('minimum_duration | late_exit | absence');
            $table->integer('min_minutes')->nullable();
            $table->integer('grace_minutes')->nullable();
            $table->string('penalty_type', 50)->default('fixed')->comment('fixed | percentage');
            $table->decimal('penalty_value', 10, 2)->default(0);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        Schema::create('member_attendance_summary', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id'); // maps to users.id
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('branch_id');
            
            $table->string('billing_month', 7)->comment('YYYY-MM');
            $table->integer('total_present_days')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->integer('total_minutes')->default(0);
            $table->integer('billable_sessions')->default(0);
            $table->integer('extra_sessions')->default(0);
            $table->decimal('calculated_amount', 10, 2)->default(0);
            $table->boolean('is_locked')->default(false);
            
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_attendance_summary');
        Schema::dropIfExists('attendance_rules');
        Schema::dropIfExists('attendances');
    }
};
