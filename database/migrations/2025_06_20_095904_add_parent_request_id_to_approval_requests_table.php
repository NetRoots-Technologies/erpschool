<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentRequestIdToApprovalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('approval_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('approval_requests', 'parent_request_id')) {
                $table->unsignedBigInteger('parent_request_id')->nullable()->after('leave_request_id');
                $table->foreign('parent_request_id')->references('id')->on('approval_requests')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approval_requests', function (Blueprint $table) {
            $table->dropForeign(['parent_request_id']);
            $table->dropColumn('parent_request_id');
        });
    }
}
