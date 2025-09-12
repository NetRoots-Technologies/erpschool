<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherImageInCompanyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            if (!Schema::hasColumn('company', 'voucher_image')) {
                $table->string('voucher_image')->nullable()->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('voucher_image');
        });
    }
}
