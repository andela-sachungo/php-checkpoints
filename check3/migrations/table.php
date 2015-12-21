<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

/**
 * Create the users, emojis and emoji_keywords tables if they don't exist
 * exist in the database.
 */

if (Capsule::schema()->hasTable('users') === false) {
    Capsule::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('token')->nullable();
            $table->timestamps();
    });
}

if (Capsule::schema()->hasTable('emojis') === false) {
    Capsule::schema()->create('emojis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('symbol');
            $table->string('category');
            $table->timestamps();
    });
}

if (Capsule::schema()->hasTable('emoji_keywords') === false) {
    Capsule::schema()->create('emoji_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emoji_id')->unsigned();
            $table->foreign('emoji_id')->references('id')->on('emojis')->onDelete('cascade');
            $table->string('keyword');
            $table->timestamps();
    });
}
