<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoaVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('pessoa_veiculos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('pessoa_id')->index('fk_pessoa_veiculos_pessoa');
                $table->bigInteger('veiculo_id')->index('fk_pessoa_veiculos_veiculo');
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
        Schema::dropIfExists('pessoa_veiculos');
    }
}
