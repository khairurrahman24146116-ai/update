<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('semester', 10)->default('ganjil');
            $table->string('academic_year', 20)->nullable();
            $table->decimal('value', 5, 2);
            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year'], 'scores_unique');
            $table->index(['classroom_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
