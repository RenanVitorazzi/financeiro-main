<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarFormaPgtoDespesas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->date('data_pagamento')->nullable();
            $table->longText('comprovante_id')->nullable();
            $table->enum('forma_pagamento', ['Dinheiro', 'Cheque', 'Cartão de Crédito', 'Cartão de Débito', 'Depósito', 'Boleto', 'TED', 'DOC', 'Pix'])->default('Boleto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
