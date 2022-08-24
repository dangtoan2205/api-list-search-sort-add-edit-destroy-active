<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->comment('Username for login');
            $table->string('email', 50)->unique();
            $table->string('password', 60);
            $table->string('avatar_url', 255)->nullable();
            $table->datetime('birthday')->nullable();
            $table->tinyInteger('gender')->comment('0: female, 1: male, 2: other')->default(0);
            $table->tinyInteger('is_active')->comment('0: in active, 1: active')->default(0);
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('admins');
    }
}
