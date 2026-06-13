<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('name');
            $table->enum('type', ['national_holiday', 'joint_leave'])->default('national_holiday');
            // national_holiday = Libur Nasional (Lebaran, HUT RI, dll)
            // joint_leave      = Cuti Bersama (ditetapkan pemerintah)
            $table->year('year')->index();
            $table->tinyInteger('month')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
