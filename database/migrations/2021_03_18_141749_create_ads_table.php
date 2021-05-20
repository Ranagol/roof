<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->float('price', 8, 2);
            $table->float('price_by_surface', 8, 2);
            $table->float('surface', 8, 2);
            $table->float('number_of_rooms', 8, 2);
            $table->float('floor', 8, 2);
            $table->string('city');
            $table->string('location_in_city');
            $table->string('ad_link');
            $table->string('image_link');
            $table->string('ad_source');
            $table->string('advertiser');
            $table->date('ad_date');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
