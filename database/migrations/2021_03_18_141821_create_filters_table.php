<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('location_in_city')->nullable();
            $table->integer('min_rooms')->nullable();
            $table->integer('max_rooms')->nullable();
            $table->integer('min_surface')->nullable();
            $table->integer('max_surface')->nullable();
            $table->integer('min_floor')->nullable();
            $table->integer('max_floor')->nullable();
            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();
            $table->integer('min_price_by_surface')->nullable();
            $table->integer('max_price_by_surface')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}
