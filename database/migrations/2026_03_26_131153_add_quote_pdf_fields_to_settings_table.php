<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuotePdfFieldsToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('quote_sender_name', 100)->nullable()->after('copy');
            $table->string('quote_sender_phone', 50)->nullable()->after('quote_sender_name');
            $table->string('quote_sender_email', 100)->nullable()->after('quote_sender_phone');
            $table->string('quote_sender_website', 200)->nullable()->after('quote_sender_email');
            $table->text('quote_footer_text')->nullable()->after('quote_sender_website');
            $table->longText('quote_client_to_provide')->nullable()->after('quote_footer_text');
            $table->longText('quote_terms_conditions')->nullable()->after('quote_client_to_provide');
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'quote_sender_name',
                'quote_sender_phone',
                'quote_sender_email',
                'quote_sender_website',
                'quote_footer_text',
                'quote_client_to_provide',
                'quote_terms_conditions',
            ]);
        });
    }
}