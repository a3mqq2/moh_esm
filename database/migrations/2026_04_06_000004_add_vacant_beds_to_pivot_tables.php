<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department_hospital', function (Blueprint $table) {
            $table->unsignedInteger('vacant_beds')->default(0)->after('beds');
        });

        Schema::table('hospital_ward', function (Blueprint $table) {
            $table->unsignedInteger('vacant_beds')->default(0)->after('beds');
        });
    }

    public function down(): void
    {
        Schema::table('department_hospital', function (Blueprint $table) {
            $table->dropColumn('vacant_beds');
        });
        Schema::table('hospital_ward', function (Blueprint $table) {
            $table->dropColumn('vacant_beds');
        });
    }
};
