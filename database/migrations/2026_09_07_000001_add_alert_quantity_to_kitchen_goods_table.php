<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kitchen_goods', function (Blueprint $table) {
            $table->decimal('alert_quantity', 12, 2)->default(0.00)->after('cost_per_unit');
        });
    }

    public function down(): void
    {
        Schema::table('kitchen_goods', function (Blueprint $table) {
            $table->dropColumn('alert_quantity');
        });
    }
};
