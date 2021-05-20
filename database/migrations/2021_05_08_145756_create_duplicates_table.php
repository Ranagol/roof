<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuplicatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duplicates', function (Blueprint $table) {
			$table->unsignedBigInteger('user_id')->index();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        	$table->unsignedBigInteger('ad_id_1')->index();
			$table->foreign('ad_id_1')->references('id')->on('ads')->onDelete('cascade');
			$table->unsignedBigInteger('ad_id_2')->index();
			$table->foreign('ad_id_2')->references('id')->on('ads')->onDelete('cascade');
			$table->primary(['ad_id_1', 'ad_id_2', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duplicates');
    }
}
