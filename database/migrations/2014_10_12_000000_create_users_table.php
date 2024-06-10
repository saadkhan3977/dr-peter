<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            // $table->string('last_name')->nullable();
            // $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_code')->nullable();
            $table->string('password');
            // $table->string('address')->nullable();
            // $table->string('city')->nullable();
            // $table->string('state')->nullable();
            $table->string('country_code')->nullable();
            $table->string('photo')->nullable();
            $table->enum('role',['user','admin'])->default('user');
            $table->enum('status',['active','inactive'])->default('active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
