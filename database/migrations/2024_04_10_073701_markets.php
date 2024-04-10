<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Markets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('asset');
            $table->string('interval');
            $table->double('o');
            $table->double('h');
            $table->double('l');
            $table->double('c');
            $table->double('volume');
            $table->double('quote_volume');
            $table->double('buy_base_volume');
            $table->double('buy_quote_volume');
            $table->integer('time');

            $table->index(['asset', 'time', 'interval']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markets');
    }
}
