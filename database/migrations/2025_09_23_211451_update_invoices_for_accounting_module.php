<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $validStatuses = ['unpaid', 'partial', 'paid', 'vadeli', 'taksitlendirildi', 'vadesi_gecmis'];

        DB::table('invoices')
            ->whereNotIn('status', $validStatuses)
            ->update(['status' => 'unpaid']);

        $newStatuses = "'unpaid','partial','paid','vadeli','taksitlendirildi','vadesi_gecmis'";
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM({$newStatuses}) NOT NULL DEFAULT 'unpaid'");

        if (! Schema::hasColumn('invoices', 'payment_method')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('payment_method')->nullable()->after('status');
            });
        }

        if (! Schema::hasColumn('invoices', 'paid_at')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->timestamp('paid_at')->nullable()->after('payment_method');
            });
        }

        if (! Schema::hasColumn('invoices', 'due_date')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->date('due_date')->nullable()->after('paid_at');
            });
        }

        if (! Schema::hasColumn('invoices', 'payment_details')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->json('payment_details')->nullable()->after('due_date');
            });
        }

        if (! Schema::hasColumn('invoices', 'deleted_at')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $originalStatuses = "'unpaid','partial','paid'";
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM({$originalStatuses}) NOT NULL DEFAULT 'unpaid'");

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'payment_details')) {
                $table->dropColumn('payment_details');
            }
            if (Schema::hasColumn('invoices', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('invoices', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('invoices', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('invoices', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};