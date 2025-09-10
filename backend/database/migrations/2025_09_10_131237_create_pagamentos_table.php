<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('pagamentos', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->date('data_ocorrido')->nullable();
                $table->decimal('valor', 10, 2)->nullable();
                $table->bigInteger('parcela')->nullable();
                $table->text('descritivo')->nullable();
                $table->timestamps();
                $table->bigInteger('conta_id')->nullable();
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
        Schema::dropIfExists('pagamentos');
    }
}
