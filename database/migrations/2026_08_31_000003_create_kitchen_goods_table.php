<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_goods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('kitchen_goods_category_id')->nullable()->constrained('kitchen_goods_categories')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('current_stock', 12, 2)->default(0.00);
            $table->decimal('cost_per_unit', 12, 2)->default(0.00);
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE);
            $table->string('creator_type')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->string('editor_type')->nullable();
            $table->bigInteger('editor_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_goods');
    }
};
