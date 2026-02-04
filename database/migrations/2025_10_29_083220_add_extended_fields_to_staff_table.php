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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('employment_type')->default('Full-time')->after('position');
            $table->date('date_joined')->nullable()->after('employment_type');
            $table->string('location')->nullable()->after('email');
            $table->string('security_clearance')->default('Level 1')->after('location');
            $table->date('last_verified')->default(now())->after('security_clearance');
            $table->boolean('background_check')->default(false)->after('last_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'employment_type',
                'date_joined',
                'location',
                'security_clearance',
                'last_verified',
                'background_check',
            ]);
        });
    }
};
