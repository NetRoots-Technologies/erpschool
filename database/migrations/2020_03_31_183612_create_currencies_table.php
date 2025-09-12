<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class CreateCurrenciesTable extends Migration
{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()
    {



        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('decimal');
            $table->string('decimal_fixed_point')->nullable();
            $table->string('symbols');
            $table->string('rate');
            $table->string('status');
            $table->string('is_default')->default(1)->nullable();
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

        Schema::dropIfExists('currencies');

    }

}

