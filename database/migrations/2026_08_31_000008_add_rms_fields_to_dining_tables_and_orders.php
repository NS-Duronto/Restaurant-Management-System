<?php

use App\Enums\DiningTableStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dining_tables', function (Blueprint $table) {
            if (! Schema::hasColumn('dining_tables', 'table_status')) {
                $table->unsignedTinyInteger('table_status')->default(DiningTableStatus::AVAILABLE)->after('status');
            }
            if (! Schema::hasColumn('dining_tables', 'current_order_id')) {
                $table->foreignId('current_order_id')->nullable()->after('table_status')->constrained('orders')->nullOnDelete();
            }
            if (! Schema::hasColumn('dining_tables', 'capacity')) {
                $table->integer('capacity')->default(4)->after('size');
            }
            if (! Schema::hasColumn('dining_tables', 'serial_no')) {
                $table->integer('serial_no')->nullable()->after('id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'change_return')) {
                $table->decimal('change_return', 12, 2)->default(0.00)->after('pos_received_amount');
            }
            if (! Schema::hasColumn('orders', 'slip_type')) {
                $table->unsignedTinyInteger('slip_type')->nullable()->default(1)->after('change_return');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dining_tables', function (Blueprint $table) {
            if (Schema::hasColumn('dining_tables', 'current_order_id')) {
                $table->dropForeign(['current_order_id']);
                $table->dropColumn('current_order_id');
            }
            if (Schema::hasColumn('dining_tables', 'table_status')) {
                $table->dropColumn('table_status');
            }
            if (Schema::hasColumn('dining_tables', 'capacity')) {
                $table->dropColumn('capacity');
            }
            if (Schema::hasColumn('dining_tables', 'serial_no')) {
                $table->dropColumn('serial_no');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'change_return')) {
                $table->dropColumn('change_return');
            }
            if (Schema::hasColumn('orders', 'slip_type')) {
                $table->dropColumn('slip_type');
            }
        });
    }
};
