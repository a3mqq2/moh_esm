<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_hospital', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('beds')->default(0);
            $table->timestamps();
            $table->unique(['hospital_id', 'department_id']);
        });

        Schema::create('hospital_ward', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('beds')->default(0);
            $table->timestamps();
            $table->unique(['hospital_id', 'ward_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_ward');
        Schema::dropIfExists('department_hospital');
    }
};
