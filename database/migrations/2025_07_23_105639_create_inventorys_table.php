<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('inventorys')) {

            Schema::create('inventorys', function (Blueprint $table) {
                $table->id();

                // Foreign keys for relationships
                $table->unsignedBigInteger('account_type_id');
                $table->foreign('account_type_id')->references('id')->on('inventory_categorys')->onDelete('restrict'); // Assuming 'inventory_categorys' table

                $table->unsignedBigInteger('detail_type_id');
                $table->foreign('detail_type_id')->references('id')->on('inventory_categorys')->onDelete('restrict'); // Assuming 'inventory_categorys' table

                $table->unsignedBigInteger('category_id');
                $table->foreign('category_id')->references('id')->on('b_category')->onDelete('restrict');

                // Inventory Details
                $table->string('inventory_type');
                $table->string('item_name');
                $table->text('additional_description')->nullable();
                $table->text('remarks')->nullable();

                // Account Relationships (using the 'id' of the related accounts)
                $table->unsignedBigInteger('asset_account_id'); // Changed from 'asset_account' to 'asset_account_id' for consistency
                $table->foreign('asset_account_id')->references('id')->on('groups')->onDelete('restrict'); // Assuming a generic 'accounts' table for financial accounts

                $table->unsignedBigInteger('sale_account_id');
                $table->foreign('sale_account_id')->references('id')->on('groups')->onDelete('restrict'); // Assuming 'accounts' table

                $table->unsignedBigInteger('cost_account_id');
                $table->foreign('cost_account_id')->references('id')->on('groups')->onDelete('restrict'); // Assuming 'accounts' table

                // The form doesn't have these, so they are kept nullable or removed if not needed.
                $table->string('commission_account')->nullable(); // No corresponding field in form
                $table->string('payable_account')->nullable();   // No corresponding field in form

                // Sales and Tax Information
                $table->string('sale_type')->nullable();
                $table->string('sales_tax_percentage')->nullable(); // Storing as string as per form options
                $table->string('further_sale_tax')->nullable();     // Storing as string as per form options

                // HS Code Details
                $table->string('hs_code')->nullable();
                $table->string('hs_code_description')->nullable();

                // Unit Information
                $table->integer('packing_unit')->default(1);
                $table->string('packing_unit_type');
                $table->integer('base_sale_unit')->default(1);
                $table->string('base_sale_unit_type');

                // Quantity and Dates
                $table->integer('qty_in_hand')->default(0);
                $table->date('as_on_date')->nullable();
                $table->date('as_of_date')->nullable();

                // Pricing and Financials
                $table->decimal('cost_price', 10, 2)->default(0.00);
                $table->decimal('sale_price', 10, 2)->default(0.00);
                $table->decimal('min_sale_price', 10, 2)->default(0.00);

                // Other fields
                $table->string('image')->nullable();
                $table->integer('reorder_level')->default(0);
                $table->decimal('margin_percentage', 5, 2)->default(0.00);
                $table->decimal('commission_percentage', 5, 2)->default(0.00);
                $table->integer('due_expiry_date')->nullable();
                $table->string('code')->nullable()->unique();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventorys');
    }
};