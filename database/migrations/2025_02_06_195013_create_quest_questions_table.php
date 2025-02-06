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
        Schema::create('quest_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quest_id');
            $table->unsignedBigInteger('question_id');
            $table->foreign('quest_id')->references('id')->on('quests');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_questions');
    }
};
