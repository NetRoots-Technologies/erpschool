<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('card_path')->nullable();
            $table->string('card_url')->nullable();

            $table->string('service_icon_url')->nullable();
            $table->string('service_icon_path')->nullable();
            $table->string('service_name')->nullable();

            $table->string('title')->nullable();
            $table->string('tag_line')->nullable();
            $table->string('slug')->nullable();
            $table->string('link')->nullable();
            $table->string('video_link')->nullable();
            //            $table->Text('text');
            $table->string('alt_text')->nullable();

            $table->longText('post')->nullable();
            $table->string('meta')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('user_id')->nullable();
            $table->tinyInteger('follow')->default(0);
            $table->integer('status')->default(0);
            $table->integer('views')->default(0);
            $table->dateTime('publish_time')->nullable();
            $table->integer('publish')->default(1);
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
        Schema::dropIfExists('services');
    }
}
