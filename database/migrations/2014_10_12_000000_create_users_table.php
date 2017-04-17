<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname', 150)->unique();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('photo')->nullable()->default(null);
            $table->string('photo_mini')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE users ADD searchable tsvector");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
