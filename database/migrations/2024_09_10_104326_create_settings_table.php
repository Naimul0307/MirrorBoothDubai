<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_title', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('facebook_url', 200)->nullable();
            $table->string('twitter_url', 200)->nullable();
            $table->string('whatsapp_url', 200)->nullable();
            $table->string('instagram_url', 200)->nullable();
            $table->string('tiktok_url', 200)->nullable();
            $table->string('linkedin_url', 200)->nullable();
            $table->string('youtube_url', 200)->nullable();
            $table->text('contact_card_one')->nullable();
            $table->text('contact_card_two')->nullable();
            $table->text('contact_card_three')->nullable();
            $table->string('copy', 100);
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
        Schema::dropIfExists('settings');
    }
}
