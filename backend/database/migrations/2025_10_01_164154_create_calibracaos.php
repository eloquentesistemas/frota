<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calibracaos', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->bigInteger('pessoa_id');
            $table->bigInteger('veiculo_id');
            $table->string('servico');
            $table->bigInteger('km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibracaos');
    }
};
