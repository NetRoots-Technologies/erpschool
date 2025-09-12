<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class CreateLedgersTable extends Migration
{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()
    {
        if (!Schema::hasTable('ledgers')) {

            Schema::create('ledgers', function (Blueprint $table) {

                $table->increments('id');
                $table->string('name', 255);
                $table->string('number', 255)->nullable();
                $table->string('parent_type')->nullable();
                $table->unsignedInteger('branch_id')->default(0)->nullable();
                $table->string('code', 255)->nullable();
                $table->unsignedInteger('group_id')->default(0);
                $table->string('group_number', 255)->nullable();
                $table->unsignedInteger('opening_balance')->nullable()->default(0);
                $table->unsignedInteger('closing_balance')->nullable()->default(0);
                $table->enum('balance_type', ['c', 'd'])->default('c');
                $table->unsignedInteger('dl_opening_balance')->nullable()->default(0);
                $table->unsignedInteger('dl_closing_balance')->nullable()->default(0);
                $table->enum('dl_balance_type', ['c', 'd'])->default('c');
                $table->unsignedInteger('gl_opening_balance')->nullable()->default(0);
                $table->unsignedInteger('gl_closing_balance')->nullable()->default(0);
                $table->enum('gl_balance_type', ['c', 'd'])->default('c');
                $table->unsignedInteger('account_type_id')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->unsignedBigInteger('deleted_by')->nullable();
                $table->timestamps();
                $table->softDeletes();


                // Foreign Key relationships

                $table->foreign('account_type_id')->references('id')->on('account_types');

                $table->foreign('created_by')->references('id')->on('users');

                $table->foreign('updated_by')->references('id')->on('users');

                //    $table->foreign('deleted_by')->references('id')->on('users');
                //latest
                // $table->id();
                // $table->string('name');
                // $table->string('code')->unique();// Same as CoA 4th level
                // $table->unsignedBigInteger('group_id')->nullable();// From Chart of Accounts
                // $table->foreign('group_id')->references('id')->on('groups');
                // $table->unsignedBigInteger('sourceable_id')->nullable();
                // $table->string('sourceable_type')->nullable();// For Vendor, Item, FeeHead, etc.
                // $table->decimal('balance', 15, 2)->default(0);//opening/closing
                // $table->enum('balance_type', ['d', 'c'])->default('d');
                // $table->enum('ledger_type', ['general', 'subsidiary'])->default('general');// subsidery/general
                // $table->timestamps();
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

        Schema::dropIfExists('ledgers');

    }

}

