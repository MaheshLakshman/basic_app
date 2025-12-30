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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('service_type', 50)->comment('gym, pt, class');
            $table->string('billing_type', 50)->comment('subscription, usage, hybrid');
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->json('name');
            $table->decimal('price', 10, 2);
            $table->string('duration_type', 50)->comment('day, month, year');
            $table->integer('duration_value');
            $table->integer('usage_limit')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });

        Schema::create('plan_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('rule_type', 50)->comment('holiday_skip, absence_freeze, overage');
            $table->string('rule_value');
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });

        Schema::create('member_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 50)->default('active')->comment('active, frozen, expired');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_plans');
        Schema::dropIfExists('plan_rules');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('services');
    }
};
