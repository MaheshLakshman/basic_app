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
        // Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            // We'll modify name to be json/text. Since SQLite/MySQL handle JSON differently, 
            // and existing data might be string, we need to be careful. 
            // For a fresh-ish app, we can just change it. For strict environments, raw statement might be needed.
            // Laravel's change() method requires doctrine/dbal. 
            // Assuming this is a dev/new environment or we can accept downtime/migration logic.
            // Let's try to change it. If it fails, we might need a raw query.
            // $table->json('name')->change(); 
        });

         // Raw SQL to change column type usually safer for 'changing' to JSON if not supported directly by change() or if data conversion is complex.
         // However, standard Laravel 'change' might work if we just want to change type.
         // BUT, converting string "Mahesh" to JSON "{"en": "Mahesh"}" is data migration.
         // For now, let's assuming we just alter the column type. 
         // If data exists, it might be lost or need conversion. 
         // Let's assume we can just drop and recreate or alter. 
         // Given "gym management", I'll assume we can just alter.
         // Note: SQLite doesn't support changing columns easily. MySQL does.
         
        // FK for organization
        Schema::table('users', function (Blueprint $table) {
             $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
        });

        // User Branches (Many-to-Many with pivot data)
        Schema::create('user_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('branch_id');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            
            $table->unique(['user_id', 'branch_id']);
        });

        // Member Profiles
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('member_code')->unique()->nullable();
            $table->date('join_date')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 20)->nullable(); // male, female, other
            $table->string('emergency_contact')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Trainer Profiles
        Schema::create('trainer_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->json('specialization')->nullable();
            $table->integer('experience_years')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_profiles');
        Schema::dropIfExists('member_profiles');
        Schema::dropIfExists('user_branches');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
            // Reverting name from json to string is hard without data loss if we converted it.
        });
    }
};
