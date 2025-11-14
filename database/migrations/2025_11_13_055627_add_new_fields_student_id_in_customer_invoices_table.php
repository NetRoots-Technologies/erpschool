<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsStudentIdInCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
                // $table->unsignedBigInteger('customer_id')->nullable()->change();
                $table->unsignedBigInteger('student_id')->nullable()->after('customer_id');
                $table->index('student_id', 'student_idx');
                $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_invoices', function (Blueprint $table) {
            //    $table->dropForeign(['customer_id']);
               $table->dropForeign('student_id');
                $table->dropIndex('student_idx');
                $table->dropColumn('student_id');
        });
    }
}
