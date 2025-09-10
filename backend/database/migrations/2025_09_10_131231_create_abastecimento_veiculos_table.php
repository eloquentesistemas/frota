<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbastecimentoVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('abastecimento_veiculos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('veiculo_id')->index('fk_abastecimento_veiculos');
                $table->bigInteger('quilometragem');
                $table->decimal('litros', 10, 2);
                $table->decimal('valor', 10, 2);
                $table->timestamps();
            });
        }catch (\Exception $e){}

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abastecimento_veiculos');
    }
}
