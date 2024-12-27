<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email');
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('need_update');
            $table->integer('admin');
            $table->integer('temp_id');
            $table->string('full_name');
            $table->string('resetPass');
            $table->dateTime('last_login');
            $table->integer('deleted');
            $table->string('donor_name');
            $table->timestamp('email_verified_at');
            $table->string('remember_token');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
