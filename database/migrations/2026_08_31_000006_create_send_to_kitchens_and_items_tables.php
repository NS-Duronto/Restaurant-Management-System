<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('send_to_kitchens', function (Blueprint $table) {
            $table->id();
            $table->string('send_no', 50)->unique();
            $table->date('date');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->integer('total_items')->default(0);
            $table->string('creator_type')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->string('editor_type')->nullable();
            $table->bigInteger('editor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('send_to_kitchen_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('send_to_kitchen_id')->constrained('send_to_kitchens')->cascadeOnDelete();
            $table->foreignId('kitchen_goods_id')->constrained('kitchen_goods')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0.00);
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('send_to_kitchen_items');
        Schema::dropIfExists('send_to_kitchens');
    }
};
