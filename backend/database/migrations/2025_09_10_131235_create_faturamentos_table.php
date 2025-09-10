<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('faturamentos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('pessoa_motorista_id')->nullable()->index('fk_faturamento_motorista');
                $table->bigInteger('veiculo_id')->nullable()->index('fk_faturamento_veiculo');
                $table->dateTime('data_embarque')->nullable();
                $table->bigInteger('origem_cidade_id')->nullable()->index('fk_faturamento_origem');
                $table->text('origem_local')->nullable();
                $table->bigInteger('destino_cidade_id')->nullable()->index('fk_faturamento_destino');
                $table->text('destino_local')->nullable();
                $table->bigInteger('pessoa_cliente_id')->nullable()->index('fk_faturamento_cliente');
                $table->bigInteger('danfe')->nullable();
                $table->decimal('peso', 10, 2)->nullable();
                $table->decimal('valor_acerto_motorista', 10, 2)->nullable();
                $table->decimal('valor_total', 10, 2)->nullable();
                $table->string('DMT')->nullable();
                $table->text('carga')->nullable();
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
        Schema::dropIfExists('faturamentos');
    }
}
