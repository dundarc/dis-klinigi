<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only create table if it doesn't exist
        if (!Schema::hasTable('stock_payment_schedules')) {
            Schema::create('stock_payment_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_invoice_id')->constrained('stock_purchase_invoices')->onDelete('cascade');
                $table->integer('installment_number');
                $table->decimal('amount', 10, 2);
                $table->date('due_date');
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
                $table->date('paid_date')->nullable();
                $table->string('payment_method')->nullable();
                $table->text('notes')->nullable();
                $table->string('receipt_path')->nullable();
                $table->timestamps();

                $table->index(['purchase_invoice_id', 'installment_number'], 'sps_invoice_installment_idx');
                $table->index(['due_date', 'status'], 'sps_due_status_idx');
            });
        }

        // Add payment_schedule columns to stock_purchase_invoices if they don't exist
        Schema::table('stock_purchase_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_purchase_invoices', 'payment_schedule')) {
                $table->json('payment_schedule')->nullable()->after('payment_history');
            }
            if (!Schema::hasColumn('stock_purchase_invoices', 'is_installment')) {
                $table->boolean('is_installment')->default(false)->after('payment_schedule');
            }
            if (!Schema::hasColumn('stock_purchase_invoices', 'total_installments')) {
                $table->integer('total_installments')->nullable()->after('is_installment');
            }
        });

        // Update enum values to include installment
        try {
            DB::statement("ALTER TABLE stock_purchase_invoices MODIFY COLUMN payment_status ENUM('pending', 'partial', 'paid', 'overdue', 'installment') DEFAULT 'pending'");
        } catch (Exception $e) {
            // Already updated or error, continue
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_payment_schedules');
        
        Schema::table('stock_purchase_invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_schedule', 'is_installment', 'total_installments']);
        });

        // Revert enum values
        Schema::table('stock_purchase_invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE stock_purchase_invoices MODIFY COLUMN payment_status ENUM('pending', 'partial', 'paid', 'overdue') DEFAULT 'pending'");
        });
    }
};