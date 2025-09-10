<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('pessoas', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('nome');
                $table->string('cpf_cnpj', 17);
                $table->enum('tipo', ['motorista', 'cliente', 'fornecedor']);
                $table->string('telefone', 20);
                $table->string('numero_cnh', 11)->nullable();
                $table->string('categoria_cnh', 5)->nullable();
                $table->date('vencimento_cnh')->nullable();
                $table->enum('situacao', ['ativo', 'inativo']);
                $table->bigInteger('cidade_id')->nullable()->index('fk_pessoa_cidade');
                $table->string('rua')->nullable();
                $table->string('numero', 10)->nullable();
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
        Schema::dropIfExists('pessoas');
    }
}
