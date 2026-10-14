<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\ResortBooking;
use App\Models\ResortEvent;
use App\Models\ResortGallery;
use App\Models\ResortMembershipPlan;
use App\Models\ResortPartner;
use App\Models\ResortSection;
use App\Models\ResortSlider;
use App\Models\ResortTeam;
use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ResortTableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resort Settings (dipokhalder/settings)
        Settings::group('resort')->set([
            'resort_name'        => 'Garden Palace Resort Sylhet',
            'resort_tagline'     => 'প্রকৃতির কোলে একটি অনন্য আতিথেয়তা',
            'resort_phone'       => '+৮৮০ ১৭১১-১২৩৪৫৬',
            'resort_email'       => 'info@gardenpalaceresortsylhet.com',
            'resort_address'     => 'গার্ডেন প্যালেস রিসোর্ট সিলেট, বাংলাদেশ',
            'resort_checkin'     => 'দুপুর ১২টা',
            'resort_checkout'    => 'দুপুর ১২টা',
            'resort_facebook'    => 'https://facebook.com',
            'resort_instagram'   => 'https://instagram.com',
            'resort_youtube'     => 'https://youtube.com',
            'resort_whatsapp'    => '+8801711123456',
            'resort_map_iframe'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115612.5470919157!2d91.7990432!3d24.8949298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3750558b9c0e5c5f%3A0x9a6e5b1c5b1c5b1c!2sSylhet!5e0!3m2!1sen!2sbd!4v1700000000000',
            'resort_fb_page_url' => 'https://www.facebook.com/facebook',
            'resort_about_title' => 'আমাদের সম্পর্কে',
            'resort_about_sub'   => 'প্রকৃতির কোলে একটি অনন্য আতিথেয়তা',
            'resort_about_head'  => 'গার্ডেন প্যালেস রিসোর্ট সিলেট',
            'resort_about_p1'    => 'সিলেট শহরের প্রাণকেন্দ্র থেকে মাত্র কয়েক মিনিট দূরে অবস্থিত গার্ডেন প্যালেস রিসোর্ট সিলেট। চারপাশে সবুজ চা বাগান, পাহাড় ও নদীর মনোরম দৃশ্য। আমরা আধুনিক সুবিধা ও ঐতিহ্যবাহী আতিথেয়তার এক অনন্য মিশ্রণ প্রদান করি।',
            'resort_about_p2'    => 'আমাদের রিসর্টে রয়েছে বিলাসবহুল কক্ষ, সুইমিং পুল, রেস্টুরেন্ট, কনফারেন্স হল, পার্টি ভেন্যু এবং বিশাল সবুজ বাগান। পরিবার, দম্পতি বা কর্পোরেট ইভেন্টের জন্য এটি আদর্শ স্থান।',
            'resort_feat1'       => 'ফ্রি ওয়াইফাই',
            'resort_feat2'       => 'সুইমিং পুল',
            'resort_feat3'       => 'রেস্টুরেন্ট',
            'resort_feat4'       => 'পার্কিং',
            'resort_food_title'  => 'রেস্টুরেন্ট ফুড অর্ডার',
            'resort_food_sub'    => 'আমাদের জনপ্রিয় খাবারগুলো অর্ডার করুন',
            'resort_food_cta'    => 'খাবার মেনু দেখতে স্ক্যান করুন অথবা ক্লিক করুন',
        ]);

        // 2. Sections
        ResortSection::truncate();
        $sections = [
            ['name' => 'আমাদের সম্পর্কে (About Us)',      'section_key' => 'about',      'sort_order' => 1,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'ফটো গ্যালারি (Photo Gallery)',    'section_key' => 'gallery',    'sort_order' => 2,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'ভিডিও গ্যালারি (Video Gallery)',  'section_key' => 'videos',     'sort_order' => 3,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'ফেসবুক পেজ (Facebook Page)',    'section_key' => 'facebook',   'sort_order' => 4,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'আমাদের পার্টনার (Partners)',      'section_key' => 'partners',   'sort_order' => 5,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'আমাদের টিম (Our Team)',          'section_key' => 'team',       'sort_order' => 6,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'সর্বশেষ ইভেন্ট (Latest Events)',   'section_key' => 'events',     'sort_order' => 7,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'মেম্বারশিপ প্ল্যান (Membership)',  'section_key' => 'membership', 'sort_order' => 8,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'বুকিং করুন (Booking)',          'section_key' => 'booking',    'sort_order' => 9,  'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'খাবার অর্ডার (Food Order)',       'section_key' => 'food',       'sort_order' => 10, 'status' => \App\Enums\Status::ACTIVE],
            ['name' => 'যোগাযোগ ও ম্যাপ (Contact & Map)', 'section_key' => 'contact',    'sort_order' => 11, 'status' => \App\Enums\Status::ACTIVE],
        ];
        foreach ($sections as $s) {
            ResortSection::create($s);
        }

        // 3. Hero Sliders
        ResortSlider::truncate();
        $sliders = [
            [
                'title'      => 'গার্ডেন প্যালেস রিসোর্ট সিলেট',
                'subtitle'   => 'সিলেটের সবুজ-শ্যামল প্রকৃতির মাঝে একটি স্বর্গীয় আশ্রয়',
                'image_url'  => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1600&q=80',
                'btn_text1'  => 'এখনই বুক করুন',
                'btn_link1'  => '#booking',
                'btn_text2'  => 'আরও জানুন',
                'btn_link2'  => '#about',
                'sort_order' => 1,
                'status' => \App\Enums\Status::ACTIVE,
            ],
            [
                'title'      => 'বিলাসবহুল রিসর্ট অভিজ্ঞতা',
                'subtitle'   => 'আধুনিক সুবিধা ও প্রাকৃতিক সৌন্দর্যের নিখুঁত মিশ্রণ',
                'image_url'  => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1600&q=80',
                'btn_text1'  => 'রুম দেখুন',
                'btn_link1'  => '#booking',
                'btn_text2'  => null,
                'btn_link2'  => null,
                'sort_order' => 2,
                'status' => \App\Enums\Status::ACTIVE,
            ],
            [
                'title'      => 'সুস্বাদু খাবার ও রেস্টুরেন্ট',
                'subtitle'   => 'স্থানীয় ও আন্তর্জাতিক খাবারের অসাধারণ স্বাদ',
                'image_url'  => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80',
                'btn_text1'  => 'খাবার অর্ডার করুন',
                'btn_link1'  => '#food',
                'btn_text2'  => null,
                'btn_link2'  => null,
                'sort_order' => 3,
                'status' => \App\Enums\Status::ACTIVE,
            ],
            [
                'title'      => 'অসাধারণ সুইমিং পুল',
                'subtitle'   => 'স্বচ্ছ পানিতে সাঁতার কেটে প্রশান্তির অনুভূতি উপভোগ করুন',
                'image_url'  => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=1600&q=80',
                'btn_text1'  => 'গ্যালারি দেখুন',
                'btn_link1'  => '#gallery',
                'btn_text2'  => null,
                'btn_link2'  => null,
                'sort_order' => 4,
                'status' => \App\Enums\Status::ACTIVE,
            ],
            [
                'title'      => 'বিশেষ ইভেন্ট ও পার্টি ভেন্যু',
                'subtitle'   => 'জন্মদিন, বিবাহবার্ষিকী বা কর্পোরেট ইভেন্টের নিখুঁত আয়োজন',
                'image_url'  => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=1600&q=80',
                'btn_text1'  => 'পার্টি বুক করুন',
                'btn_link1'  => '#party',
                'btn_text2'  => null,
                'btn_link2'  => null,
                'sort_order' => 5,
                'status' => \App\Enums\Status::ACTIVE,
            ],
            [
                'title'      => 'প্রকৃতির মনোরম পরিবেশ',
                'subtitle'   => 'সবুজ বাগান, পাহাড় ও নদীর অপরূপ সৌন্দর্যে ঘেরা',
                'image_url'  => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&q=80',
                'btn_text1'  => 'মেম্বার হোন',
                'btn_link1'  => '#membership',
                'btn_text2'  => null,
                'btn_link2'  => null,
                'sort_order' => 6,
                'status' => \App\Enums\Status::ACTIVE,
            ],
        ];
        foreach ($sliders as $slide) {
            ResortSlider::create($slide);
        }

        // 4. Photo & Video Galleries
        ResortGallery::truncate();
        $galleries = [
            // Photos
            ['title' => 'সুইমিং পুল ভিউ', 'type' => 'photo', 'category' => 'resort', 'image_url' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=600&q=80', 'sort_order' => 1, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'বিলাসবহুল বেডরুম', 'type' => 'photo', 'category' => 'rooms', 'image_url' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80', 'sort_order' => 2, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'সবুজ বাগান ও ল্যান্ডস্কেপ', 'type' => 'photo', 'category' => 'nature', 'image_url' => 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=600&q=80', 'sort_order' => 3, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'রেস্টুরেন্ট ডাইনিং', 'type' => 'photo', 'category' => 'resort', 'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&q=80', 'sort_order' => 4, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'পার্টি ভেন্যু ডেকোরেশন', 'type' => 'photo', 'category' => 'events', 'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=600&q=80', 'sort_order' => 5, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'নাইট ভিউ লাইটিং', 'type' => 'photo', 'category' => 'nature', 'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80', 'sort_order' => 6, 'status' => \App\Enums\Status::ACTIVE],
            
            // Videos
            ['title' => 'রিসোর্ট ওভারভিউ ও ওয়াকথ্রু', 'type' => 'video', 'category' => 'all', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'image_url' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=600&q=80', 'sort_order' => 1, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'সুইমিং পুল ও রিল্যাক্সেশন এরিয়া', 'type' => 'video', 'category' => 'all', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'image_url' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=600&q=80', 'sort_order' => 2, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'রেস্টুরেন্ট ও ফুড প্রিপারেশন', 'type' => 'video', 'category' => 'all', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&q=80', 'sort_order' => 3, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'ইভেন্ট ও পার্টি ভেন্যু হাইলাইটস', 'type' => 'video', 'category' => 'all', 'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'image_url' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?w=600&q=80', 'sort_order' => 4, 'status' => \App\Enums\Status::ACTIVE],
        ];
        foreach ($galleries as $gal) {
            ResortGallery::create($gal);
        }

        // 5. Events (Both About Slider & Latest Events)
        ResortEvent::truncate();
        // 10 About Slider Items
        $aboutSliders = [
            ['title' => 'নববর্ষ সেলিব্রেশন', 'tag' => 'নববর্ষ ২০২৬', 'description' => 'সাংস্কৃতিক অনুষ্ঠান, লাইভ মিউজিক, বিশেষ ডিনার ও আতশবাজি সহ দুর্দান্ত নববর্ষ উদযাপন।', 'image_url' => 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 1, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'আন্তর্জাতিক ফুড ফেস্টিভ্যাল', 'tag' => 'ফুড ফেস্ট', 'description' => 'স্থানীয় ও আন্তর্জাতিক ৫০+ খাবারের স্বাদ গ্রহণের অনন্য সুযোগ, লাইভ কুকিং শো সহ।', 'image_url' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 2, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'সিলেট রয়্যাল ওয়েডিং', 'tag' => 'ওয়েডিং', 'description' => 'রিসোর্টের মনোরম পরিবেশে জমকালো বিয়ে ও বিবাহবার্ষিকী আয়োজনের বিশেষ আয়োজন।', 'image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 3, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'লাইভ মিউজিক নাইট', 'tag' => 'কনসার্ট', 'description' => 'দেশের জনপ্রিয় শিল্পীদের পরিবেশনায় জমকালো লাইভ কনসার্ট ও সাংস্কৃতিক সন্ধ্যা।', 'image_url' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 4, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'কর্পোরেট কনফারেন্স', 'tag' => 'কর্পোরেট', 'description' => 'আধুনিক সুবিধাসম্পন্ন কনফারেন্স হল, প্রজেক্টর, সাউন্ড সিস্টেম ও ক্যাটারিং সেবা সহ।', 'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 5, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'শিশুদের জন্মদিন উদযাপন', 'tag' => 'জন্মদিন', 'description' => 'রঙিন সাজসজ্জা, কেক, বেলুন, গেমস ও শিশুদের জন্য বিশেষ আয়োজনসহ স্মরণীয় জন্মদিন।', 'image_url' => 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 6, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'বার্ষিক স্পোর্টস ডে', 'tag' => 'স্পোর্টস', 'description' => 'বিভিন্ন ইনডোর ও আউটডোর খেলাধুলা, ট্রফি বিতরণ ও সবার জন্য মনোরম পুরস্কার আয়োজন।', 'image_url' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 7, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'সমার গার্ডেন পার্টি', 'tag' => 'গার্ডেন পার্টি', 'description' => 'সবুজ বাগানের মাঝে সন্ধ্যার নৈশভোজ, লাইভ মিউজিক ও তারার নিচে অনন্য পার্টির আয়োজন।', 'image_url' => 'https://images.unsplash.com/photo-1533105079780-92b9be482077?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 8, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'ফ্যামিলি গেট-টুগেদার', 'tag' => 'ফ্যামিলি', 'description' => 'পরিবারের সকল সদস্যের জন্য বারবিকিউ, গেমস, পুল অ্যাক্সেস ও সারাদিনের আনন্দ।', 'image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 9, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'গ্র্যাজুয়েশন সেলিব্রেশন', 'tag' => 'গ্র্যাজুয়েশন', 'description' => 'শিক্ষাজীবনের বিশেষ মুহূর্ত উদযাপনে বন্ধুবান্ধব ও পরিবারের সাথে স্মরণীয় ডিনার পার্টি।', 'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&q=80', 'is_about_slider' => 1, 'sort_order' => 10, 'status' => \App\Enums\Status::ACTIVE],
        ];
        foreach ($aboutSliders as $as) {
            ResortEvent::create($as);
        }

        // 3 Latest Events
        $latestEvents = [
            ['title' => 'নববর্ষ সেলিব্রেশন ২০২৬', 'tag' => '১৫ ডিসেম্বর', 'event_date' => '১৫ ডিসেম্বর - সন্ধ্যা ৭টা', 'description' => 'সাংস্কৃতিক অনুষ্ঠান, লাইভ মিউজিক, বিশেষ ডিনার ও আতশবাজি সহ নববর্ষ উদযাপন।', 'image_url' => 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=600&q=80', 'is_about_slider' => 0, 'sort_order' => 1, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'ফুড ফেস্টিভ্যাল', 'tag' => '২২ ডিসেম্বর', 'event_date' => '২২ ডিসেম্বর - দুপুর ১২টা', 'description' => 'স্থানীয় ও আন্তর্জাতিক ৫০+ খাবারের স্বাদ গ্রহণের অনন্য সুযোগ।', 'image_url' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80', 'is_about_slider' => 0, 'sort_order' => 2, 'status' => \App\Enums\Status::ACTIVE],
            ['title' => 'কাউন্টডাউন পার্টি', 'tag' => '৩১ ডিসেম্বর', 'event_date' => '৩১ ডিসেম্বর - রাত ৯টা', 'description' => 'DJ, ডান্স, লাইভ কনসার্ট ও বিশেষ নৈশভোজের মাধ্যমে নতুন বছর বরণ।', 'image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=600&q=80', 'is_about_slider' => 0, 'sort_order' => 3, 'status' => \App\Enums\Status::ACTIVE],
        ];
        foreach ($latestEvents as $le) {
            ResortEvent::create($le);
        }

        // 6. Our Team
        ResortTeam::truncate();
        $teams = [
            [
                'name'        => 'মোঃ রফিকুল ইসলাম',
                'designation' => 'জেনারেল ম্যানেজার',
                'bio'         => '১৫ বছরের অভিজ্ঞতাসম্পন্ন হসপিটালিটি বিশেষজ্ঞ।',
                'image_url'   => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&q=80',
                'facebook'    => 'https://facebook.com',
                'linkedin'    => 'https://linkedin.com',
                'email'       => 'manager@gardenpalaceresortsylhet.com',
                'sort_order'  => 1,
                'status'      => 1,
            ],
            [
                'name'        => 'ফারজানা আক্তার',
                'designation' => 'হেড শেফ',
                'bio'         => 'আন্তর্জাতিক ও দেশীয় রান্নায় দক্ষ অভিজ্ঞ শেফ।',
                'image_url'   => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80',
                'facebook'    => 'https://facebook.com',
                'instagram'   => 'https://instagram.com',
                'email'       => 'chef@gardenpalaceresortsylhet.com',
                'sort_order'  => 2,
                'status'      => 1,
            ],
            [
                'name'        => 'সাদিয়া হক',
                'designation' => 'ফ্রন্ট ডেস্ক ম্যানেজার',
                'bio'         => 'অতিথি সেবায় সর্বদা নিবেদিত ও আন্তরিক।',
                'image_url'   => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&q=80',
                'facebook'    => 'https://facebook.com',
                'linkedin'    => 'https://linkedin.com',
                'email'       => 'frontdesk@gardenpalaceresortsylhet.com',
                'sort_order'  => 3,
                'status'      => 1,
            ],
            [
                'name'        => 'আরিফুল হাসান',
                'designation' => 'ইভেন্ট কো-অর্ডিনেটর',
                'bio'         => 'পার্টি ও কর্পোরেট ইভেন্ট আয়োজনে বিশেষজ্ঞ।',
                'image_url'   => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80',
                'facebook'    => 'https://facebook.com',
                'instagram'   => 'https://instagram.com',
                'email'       => 'events@gardenpalaceresortsylhet.com',
                'sort_order'  => 4,
                'status'      => 1,
            ],
        ];
        foreach ($teams as $tm) {
            ResortTeam::create($tm);
        }

        // 7. Membership Plans
        ResortMembershipPlan::truncate();
        $plans = [
            [
                'title'       => 'বেসিক (Basic)',
                'price'       => 5000.00,
                'duration'    => 'বছর',
                'badge'       => 'জনপ্রিয়',
                'features'    => json_encode([
                    '১০% ছাড় সব রুম বুকিংয়ে',
                    'ফ্রি হাই-স্পিড ওয়াইফাই',
                    'মাসিক স্পেশাল অফার নিউজলেটার',
                    'সাধারণ পার্কিং সুবিধা',
                ]),
                'btn_text'    => 'এখনই যোগ দিন',
                'btn_link'    => '#booking',
                'is_featured' => 0,
                'sort_order'  => 1,
                'status'      => 1,
            ],
            [
                'title'       => 'প্রিমিয়াম (Premium)',
                'price'       => 12000.00,
                'duration'    => 'বছর',
                'badge'       => 'বেস্ট ভ্যালু',
                'features'    => json_encode([
                    '২৫% ছাড় সব রুম বুকিংয়ে',
                    'ফ্রি বুফে ব্রেকফাস্ট',
                    'ফ্রি সুইমিং পুল অ্যাক্সেস',
                    'VIP সংরক্ষিত পার্কিং',
                    'প্রায়োরিটি বুকিং সাপোর্ট',
                ]),
                'btn_text'    => 'এখনই যোগ দিন',
                'btn_link'    => '#booking',
                'is_featured' => 1,
                'sort_order'  => 2,
                'status'      => 1,
            ],
            [
                'title'       => 'ভিআইপি (VIP)',
                'price'       => 25000.00,
                'duration'    => 'বছর',
                'badge'       => 'এক্সক্লুসিভ',
                'features'    => json_encode([
                    '৪০% ছাড় সব রুম বুকিংয়ে',
                    'ফ্রি সব খাবার ও ওয়েলকাম ড্রিংকস',
                    'ফ্রি স্পা ও জিম সম্পূর্ণ অ্যাক্সেস',
                    'ব্যক্তিগত কনসিয়ার্জ অ্যাসিস্ট্যান্ট',
                    'বছরে ২টি সম্পূর্ণ ফ্রি রাত',
                ]),
                'btn_text'    => 'এখনই যোগ দিন',
                'btn_link'    => '#booking',
                'is_featured' => 0,
                'sort_order'  => 3,
                'status'      => 1,
            ],
        ];
        foreach ($plans as $p) {
            ResortMembershipPlan::create($p);
        }

        // 8. Partners
        ResortPartner::truncate();
        $partners = [
            ['name' => 'বাংলাদেশ পর্যটন কর্পোরেশন', 'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Government_Seal_of_Bangladesh.svg/200px-Government_Seal_of_Bangladesh.svg.png', 'website_url' => 'https://parjatan.portal.gov.bd', 'sort_order' => 1, 'status' => 1],
            ['name' => 'বিমান বাংলাদেশ এয়ারলাইন্স', 'image_url' => 'https://upload.wikimedia.org/wikipedia/en/thumb/e/ee/Biman_Bangladesh_Airlines_logo.svg/300px-Biman_Bangladesh_Airlines_logo.svg.png', 'website_url' => 'https://biman-airlines.com', 'sort_order' => 2, 'status' => 1],
            ['name' => 'সিলেট চেম্বার অফ কমার্স', 'image_url' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=300&q=80', 'website_url' => 'https://sylhetchamber.org', 'sort_order' => 3, 'status' => 1],
            ['name' => 'হোটেল অ্যান্ড রিসোর্ট অ্যাসোসিয়েশন', 'image_url' => 'https://images.unsplash.com/photo-1516876437184-593fda40c7ce?w=300&q=80', 'website_url' => '#', 'sort_order' => 4, 'status' => 1],
        ];
        foreach ($partners as $part) {
            ResortPartner::create($part);
        }

        // 9. Admin Menu & Permission
        // Menu item priority 125, just above Settings (priority 130)
        $existingMenu = Menu::where('url', 'resort-settings')->first();
        if (!$existingMenu) {
            Menu::create([
                'name'       => 'Resort Settings',
                'language'   => 'resort_settings',
                'url'        => 'resort-settings',
                'icon'       => 'fa-solid fa-building',
                'priority'   => 125,
                'status'     => 1,
            ]);
        }

        // Permissions
        $permission = Permission::where('name', 'resort_settings')->first();
        if (!$permission) {
            $permission = Permission::create([
                'name'       => 'resort_settings',
                'guard_name' => 'sanctum',
            ]);
        }

        // Give permission to Admin role (Role ID 1)
        $adminRole = Role::find(1);
        if ($adminRole && !$adminRole->hasPermissionTo('resort_settings')) {
            $adminRole->givePermissionTo($permission);
        }
    }
}
