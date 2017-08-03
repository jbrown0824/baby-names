<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBabyNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('names', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('votes')->default(0);
            $table->integer('rank_last_week');
            $table->dateTime('last_rank_update')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('voters', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('votes_available')->default(10);
            $table->dateTime('refills_on')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('votes', function($table) {
            $table->increments('id');
            $table->integer('name_id');
            $table->integer('voter_id');
            $table->integer('num_votes_used');
            $table->string('note');
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
        Schema::drop('names');
        Schema::drop('votes');
        Schema::drop('voters');

    }
}
