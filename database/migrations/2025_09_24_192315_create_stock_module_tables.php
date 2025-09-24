<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('stock_categories')->nullOnDelete();
            $table->string('unit', 32)->default('adet');
            $table->decimal('minimum_quantity', 12, 2)->default(0);
            $table->decimal('quantity', 12, 2)->default(0);
            $table->boolean('allow_negative')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['sku'], 'stock_items_sku_unique');
            $table->unique(['barcode'], 'stock_items_barcode_unique');
        });

        Schema::create('stock_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 32)->default('supplier');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('tax_number', 64)->nullable();
            $table->string('tax_office')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('stock_suppliers')->nullOnDelete();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('vat_total', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->string('payment_status', 24)->default('pending');
            $table->string('payment_method', 24)->nullable();
            $table->date('due_date')->nullable();
            $table->string('file_path')->nullable();
            $table->json('parsed_payload')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_invoice_id')->constrained('stock_purchase_invoices')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit', 32)->default('adet');
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('stock_expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('stock_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('stock_expense_categories')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('stock_suppliers')->nullOnDelete();
            $table->string('title');
            $table->date('expense_date')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(0);
            $table->decimal('vat_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->string('payment_status', 24)->default('pending');
            $table->string('payment_method', 24)->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_treatment_id')->nullable()->constrained('patient_treatments')->nullOnDelete();
            $table->dateTime('used_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_usage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usage_id')->constrained('stock_usages')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->string('direction', 16); // in, out, adjustment
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id'], 'stock_movements_reference_index');
        });

        Schema::create('stock_account_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('stock_suppliers')->nullOnDelete();
            $table->string('direction', 16); // debit, credit
            $table->decimal('amount', 14, 2)->default(0);
            $table->date('movement_date')->nullable();
            $table->string('payment_method', 24)->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['movement_date']);
            $table->index(['reference_type', 'reference_id'], 'stock_account_movements_reference_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_account_movements');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_usage_items');
        Schema::dropIfExists('stock_usages');
        Schema::dropIfExists('stock_expenses');
        Schema::dropIfExists('stock_expense_categories');
        Schema::dropIfExists('stock_purchase_items');
        Schema::dropIfExists('stock_purchase_invoices');
        Schema::dropIfExists('stock_suppliers');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('stock_categories');
    }
};
