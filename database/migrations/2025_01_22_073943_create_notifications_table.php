<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notifications')) {

            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->integer('sender_id');
                $table->integer('reciver_id');
                $table->string('title', 50);
                $table->string('message', 500);
                $table->boolean('is_read')->default(0);
                $table->string('link', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
