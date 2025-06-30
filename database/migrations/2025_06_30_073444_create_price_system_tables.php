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
        Schema::create('price_dokters', function (Blueprint $table) {
            $table->id();
            $table->integer('id_dokter');
            $table->decimal('price', 15, 0);
            $table->string('desc')->nullable();
            $table->timestamps();
        });

        Schema::create('price_additionals', function (Blueprint $table) {
            $table->id();
            $table->integer('no_registrasi');
            $table->decimal('price', 15, 0);
            $table->string('desc')->nullable();
            $table->timestamps();
        });

        Schema::create('price_polis', function (Blueprint $table) {
            $table->id();
            $table->integer('id_poli');
            $table->decimal('price', 15, 0);
            $table->string('desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_dokters');
        Schema::dropIfExists('price_additionals');
        Schema::dropIfExists('price_polis');
    }
};
