<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Countries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default(NULL);
            $table->char('iso3')->default(NULL);
            $table->char('numeric_code')->default(NULL);
            $table->char('iso2')->default(NULL);
            $table->string('phonecode')->default(NULL);
            $table->string('capital')->default(NULL);
            $table->string('currency')->default(NULL);
            $table->string('currency_name')->default(NULL);
            $table->string('currency_symbol')->default(NULL);
            $table->string('tld')->default(NULL);
            $table->string('native')->default(NULL);
            $table->string('region')->default(NULL);
            $table->string('subregion')->default(NULL);
            $table->string('timezones')->default(NULL);
            $table->decimal('latitude')->default(NULL);
            $table->decimal('longitude')->default(NULL);
            $table->string('emoji')->default(NULL);
            $table->string('emojiU')->default(NULL);
            $table->integer('status')->default(1);
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
        //
    }
}
