<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wastages', function (Blueprint $table) {
            $table->id();
            $table->string('wastage_no')->unique();
            $table->date('date');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_loss_amount', 12, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('wastage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wastage_id')->constrained('wastages')->cascadeOnDelete();
            $table->foreignId('kitchen_goods_id')->constrained('kitchen_goods')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('quantity', 12, 3)->default(0.000);
            $table->decimal('cost_per_unit', 12, 2)->default(0.00);
            $table->decimal('total_cost', 12, 2)->default(0.00);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wastage_items');
        Schema::dropIfExists('wastages');
    }
};
