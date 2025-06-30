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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('discount')->default('0');
            $table->timestamps();
        });
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('no_registrasi');
            $table->string('unicode');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_types');
        Schema::dropIfExists('registrations');
    }
};
