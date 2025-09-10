<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePneusVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('pneus_veiculos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->bigInteger('veiculo_id')->index('fk_pneus_veiculos');
                $table->bigInteger('quilometragem');
                $table->bigInteger('quantidade');
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
        Schema::dropIfExists('pneus_veiculos');
    }
}
