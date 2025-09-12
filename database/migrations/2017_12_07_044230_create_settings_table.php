<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



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

            $table->increments('id');

            $table->string('name', 255);

            $table->text('description');

            $table->unsignedSmallInteger('status')->default(1);

            $table->unsignedInteger('created_by')->nullable();

            $table->unsignedInteger('updated_by')->nullable();

            $table->unsignedInteger('deleted_by')->nullable();

            $table->timestamps();

            $table->softDeletes();



            // Foreign Key relationships

            //            $table->foreign('created_by')->references('id')->on('users');
//
//            $table->foreign('updated_by')->references('id')->on('users');
//
//            $table->foreign('deleted_by')->references('id')->on('users');

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

