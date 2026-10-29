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
        Schema::create('tb_pais', function (Blueprint $table) {
            $table->string('pais_codi', 3)->primary();
            $table->string('pais_nomb', 80)->unique();
            // Municipio capital. Se guarda sin restricción física para evitar
            // la dependencia circular pais -> municipio -> departamento -> pais.
            $table->unsignedBigInteger('pais_capi')->nullable();
        });

        Schema::create('tb_departamento', function (Blueprint $table) {
            $table->id('depa_codi');
            $table->string('depa_nomb', 80);
            $table->string('pais_codi', 3);
            $table->foreign('pais_codi')->references('pais_codi')->on('tb_pais')->restrictOnDelete();
        });

        Schema::create('tb_municipio', function (Blueprint $table) {
            $table->id('muni_codi');
            $table->string('muni_nomb', 80);
            $table->unsignedBigInteger('depa_codi');
            $table->unsignedInteger('muni_pobl')->nullable();
            $table->foreign('depa_codi')->references('depa_codi')->on('tb_departamento')->restrictOnDelete();
        });

        Schema::create('tb_comuna', function (Blueprint $table) {
            $table->id('comu_codi');
            $table->string('comu_nomb', 80);
            $table->unsignedBigInteger('muni_codi');
            $table->foreign('muni_codi')->references('muni_codi')->on('tb_municipio')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_comuna');
        Schema::dropIfExists('tb_municipio');
        Schema::dropIfExists('tb_departamento');
        Schema::dropIfExists('tb_pais');
    }
};
