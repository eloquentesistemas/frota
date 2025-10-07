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
        Schema::table('abastecimento_veiculos', function (Blueprint $table) {
            $table->enum('tipo',['diesel','arla','diesel_mais_arla']);
            $table->text('descritivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abastecimento_veiculos', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('descritivo');
        });
    }
};
