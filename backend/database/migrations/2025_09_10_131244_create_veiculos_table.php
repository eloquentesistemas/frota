<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('veiculos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('nome');
                $table->string('placa', 8);
                $table->enum('cor', ['branco', 'prata', 'cinza', 'preto', 'azul', 'vermelho', 'amarelo', 'verde', 'laranja', 'bicolor'])->nullable();
                $table->date('vencimento_documento');
                $table->tinyInteger('ativo')->nullable();
                $table->text('descritivo')->nullable();
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
        Schema::dropIfExists('veiculos');
    }
}
