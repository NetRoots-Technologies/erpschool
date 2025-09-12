<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('requisitions')) {

            Schema::create('requisitions', function (Blueprint $table) {
                $table->id();
                $table->integer('requester_id');
                $table->integer('item_id');
                $table->integer('branch_id');
                $table->enum('type', ['F', 'P', 'S']);
                $table->integer('quantity')->defaultValue(1);
                $table->enum('priority', ['HIGH', 'MEDIUM', 'LOW'])->defaultValue('LOW');
                $table->text('justification');
                $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'FULFILLED'])->defaultValue('PENDING');
                $table->boolean('is_approved')->defaultValue(0);
                $table->string('requisition_to')->nullable();
                $table->integer('approved_by')->nullable();
                $table->text('comments')->nullable();
                $table->date('approved_date')->nullable();
                $table->date('requested_date')->nullable();
                $table->date('fulfilled_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('requisitions');
    }
}
