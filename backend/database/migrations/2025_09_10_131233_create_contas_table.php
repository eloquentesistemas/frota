<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('contas', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->date('data_ocorrido')->nullable();
                $table->string('nome')->nullable();
                $table->enum('modalidade', ['pagar', 'receber'])->nullable();
                $table->bigInteger('natureza_financeira_id')->nullable()->index('fk_contas_natureza');
                $table->decimal('valor', 10, 2)->nullable();
                $table->bigInteger('parcelas')->nullable();
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
        Schema::dropIfExists('contas');
    }
}
