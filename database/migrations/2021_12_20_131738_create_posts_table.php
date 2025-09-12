<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('link')->nullable();
            $table->string('video_link')->nullable();
            $table->integer('cat_id')->nullable();
            $table->integer('sub_cat_id')->nullable();



            //            $table->Text('text');
            $table->string('alt_text');

            $table->longText('post');
            $table->string('meta');
            $table->string('meta_description');
            $table->integer('user_id');
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
        Schema::dropIfExists('posts');
    }
}
