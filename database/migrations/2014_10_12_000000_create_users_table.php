<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


//    Tweets INT,                      -- Total number of tweets by the user
//    ProfileImageURL VARCHAR(255),    -- URL of the user's profile image
//CoverImageURL VARCHAR(255)       -- URL of the user's cover image
//);

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->text("bio")->nullable();
            $table->text("location")->nullable();
            $table->string("website")->nullable();
            $table->string("profile_avatar")->nullable();
            $table->string("cover_avatar")->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum("role",["user","admin"])->default("user");
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
