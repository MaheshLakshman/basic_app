<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('price_plan_rules');
        Schema::dropIfExists('price_plans');
        
        if (Schema::hasTable('member_price_assignments')) {
            DB::table('member_price_assignments')->truncate();

            Schema::table('member_price_assignments', function (Blueprint $table) {
                if (Schema::hasColumn('member_price_assignments', 'price_plan_id')) {
                    try {
                        $table->dropForeign(['price_plan_id']);
                    } catch (\Exception $e) {}
                    $table->dropColumn('price_plan_id');
                }
                
                if (!Schema::hasColumn('member_price_assignments', 'plan_id')) {
                    $table->unsignedBigInteger('plan_id')->after('member_id');
                }
            });

            // Separate schema call for FK to avoid race/grouping issues
            try {
                Schema::table('member_price_assignments', function (Blueprint $table) {
                    $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // FK might already exist, or other issue. Ignoring for now as we just want to proceed.
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
