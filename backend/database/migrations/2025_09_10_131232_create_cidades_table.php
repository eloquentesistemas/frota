<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('cidades', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('codigo', 8);
                $table->string('nome', 40);
                $table->string('uf', 2);
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
        Schema::dropIfExists('cidades');
    }
}
