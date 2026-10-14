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
        // 1. Resort Sections for dynamic sorting & enable/disable
        Schema::create('resort_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section_key')->unique();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 2. Resort Sliders
        Schema::create('resort_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('image_url')->nullable();
            $table->string('btn_text1')->nullable();
            $table->string('btn_link1')->nullable();
            $table->string('btn_text2')->nullable();
            $table->string('btn_link2')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 3. Resort Photo & Video Galleries
        Schema::create('resort_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('type')->default('photo'); // 'photo' or 'video'
            $table->string('category')->default('all'); // all, resort, rooms, nature, events
            $table->string('image_url')->nullable();
            $table->text('video_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 4. Resort Events & About Showcase
        Schema::create('resort_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tag')->nullable();
            $table->string('event_date')->nullable();
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('social_link')->nullable();
            $table->tinyInteger('is_about_slider')->default(0);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 5. Resort Team Members
        Schema::create('resort_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('image_url')->nullable();
            $table->string('bio')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 6. Resort Membership Plans
        Schema::create('resort_membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 13, 2)->default(0);
            $table->string('badge')->nullable();
            $table->string('duration')->nullable();
            $table->text('features')->nullable();
            $table->string('btn_text')->nullable()->default('এখনই যোগ দিন');
            $table->string('btn_link')->nullable()->default('#booking');
            $table->tinyInteger('is_featured')->default(0);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 7. Resort Partners
        Schema::create('resort_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->string('website_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 8. Resort Bookings (Resort & Party)
        Schema::create('resort_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('resort'); // resort, party
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('booking_date')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('room_type')->nullable();
            $table->string('event_type')->nullable();
            $table->integer('guests_count')->default(1);
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(5); // 5 = Pending, 10 = Confirmed, 15 = Cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resort_bookings');
        Schema::dropIfExists('resort_partners');
        Schema::dropIfExists('resort_membership_plans');
        Schema::dropIfExists('resort_teams');
        Schema::dropIfExists('resort_events');
        Schema::dropIfExists('resort_galleries');
        Schema::dropIfExists('resort_sliders');
        Schema::dropIfExists('resort_sections');
    }
};
