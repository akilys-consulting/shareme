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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->json('recurrence');
            $table->json('adresse');
            $table->string('url');
            $table->json('categories');
            $table->unsignedBigInteger('event_id');
            $table->json('auteur');
            $table->boolean('actif')->default(false);
            $table->foreign('event_id')->references('id')->on('evenements');           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
