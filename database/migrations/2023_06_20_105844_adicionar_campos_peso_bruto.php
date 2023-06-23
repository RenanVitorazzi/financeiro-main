<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdicionarCamposPesoBruto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conta_corrente', function (Blueprint $table) {
            $table->decimal('peso_bruto', 11, 3)->nullable();
        });
    }

}
