<?php



use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;



class CreateEntriesTable extends Migration
{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()
    {

        Schema::create('entries', function (Blueprint $table) {

            $table->increments('id');



            // Debit Credit Entries

            $table->string('number', 255)->nullable();

            $table->date('voucher_date');

            $table->string('cheque_no')->nullable();

            $table->date('cheque_date')->nullable();

            $table->string('invoice_no')->nullable();

            $table->date('invoice_date')->nullable();
            $table->unsignedInteger('grnID')->nullable();

            $table->string('cdr_no')->nullable();

            $table->date('cdr_date')->nullable();

            $table->string('bdr_no')->nullable();

            $table->date('bdr_date')->nullable();

            $table->string('bank_name', 255)->nullable();

            $table->string('bank_branch', 255)->nullable();

            $table->date('drawn_date')->nullable();

            $table->decimal('dr_total', 11, 2)->default(0.00);
            $table->decimal('cr_total', 11, 2)->default(0.00);
            $table->decimal('other_dr_total', 11, 2)->default(0.00);
            $table->decimal('other_cr_total', 11, 2)->default(0.00);
            $table->decimal('rate', 11, 3)->default(0.000);

            $table->text('narration')->nullable();

            $table->text('remarks')->nullable();



            // Entry Types

            $table->unsignedInteger('entry_type_id');

            // Branching and Department Levels

            $table->unsignedInteger('employee_id')->nullable();

            $table->unsignedInteger('branch_id')->nullable();

            $table->unsignedInteger('department_id')->nullable();

            $table->unsignedInteger('customers_id')->nullable();

            $table->unsignedInteger('currence_type')->nullable();
            $table->unsignedInteger('other_currency_type')->nullable();

            $table->unsignedInteger('suppliers_id')->nullable();
            $table->unsignedSmallInteger('status')->default(1);

            $table->unsignedInteger('created_by')->nullable();

            $table->unsignedInteger('updated_by')->nullable();

            $table->unsignedInteger('deleted_by')->nullable();

            $table->timestamps();

            $table->softDeletes();



            // Foreign Key relationships

            //            $table->foreign('entry_type_id')->references('id')->on('entry_types');

            //            $table->foreign('employee_id')->references('id')->on('employees');

            //            $table->foreign('branch_id')->references('id')->on('branches');

            //            $table->foreign('department_id')->references('id')->on('departments');

            //            $table->foreign('created_by')->references('id')->on('users');

            //            $table->foreign('updated_by')->references('id')->on('users');

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

        Schema::dropIfExists('entries');

    }

}

