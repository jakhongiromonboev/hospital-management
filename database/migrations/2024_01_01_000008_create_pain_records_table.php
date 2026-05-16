<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pain_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->string('area'); // head, neck, chest, upper_back, lower_back, left_shoulder, right_shoulder, left_knee, right_knee, left_wrist, right_wrist, left_ankle, right_ankle, stomach
            $table->string('severity'); // low, medium, high
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pain_records');
    }
};
