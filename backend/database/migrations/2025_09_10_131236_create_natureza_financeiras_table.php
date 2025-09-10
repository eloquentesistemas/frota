<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaturezaFinanceirasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {

            Schema::create('natureza_financeiras', function (Blueprint $table) {
                $table->bigInteger('id')->primary();
                $table->string('nome')->nullable();
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
        Schema::dropIfExists('natureza_financeiras');
    }
}
