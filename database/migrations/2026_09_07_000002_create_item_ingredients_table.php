<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('kitchen_goods_id')->constrained('kitchen_goods')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('quantity', 12, 3)->default(0.000);
            $table->timestamps();

            $table->unique(['item_id', 'kitchen_goods_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_ingredients');
    }
};
