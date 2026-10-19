<template>
    <div class="resort-app" :class="currentLang">
        <!-- ========== SITE HEADER (FIXED & STICKY) ========== -->
        <header class="site-header" :class="{ 'scrolled': isScrolled }">
            <!-- TOP BAR -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <a :href="'tel:' + (settings.resort_phone || '+8801711123456')">
                        <i class="fas fa-phone-alt"></i> {{ settings.resort_phone || '+৮৮০ ১৭১১-১২৩৪৫৬' }}
                    </a>
                    <a :href="'mailto:' + (settings.resort_email || 'info@gardenpalaceresortsylhet.com')">
                        <i class="fas fa-envelope"></i> {{ settings.resort_email || 'info@gardenpalaceresortsylhet.com' }}
                    </a>
                    <span>
                        <i class="fas fa-map-marker-alt"></i> {{ settings.resort_address || 'সিলেট, বাংলাদেশ' }}
                    </span>
                </div>
                <div class="top-bar-right">
                    <span class="social-label">{{ t('followUs') }}:</span>
                    <a v-if="settings.resort_facebook" :href="settings.resort_facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a v-if="settings.resort_instagram" :href="settings.resort_instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a v-if="settings.resort_youtube" :href="settings.resort_youtube" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a v-if="settings.resort_whatsapp" :href="'https://wa.me/' + settings.resort_whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- NAVBAR -->
            <nav class="navbar">
                <div class="logo">
                    <router-link to="/">
                        <i class="fas fa-hotel"></i> {{ settings.resort_name || 'Garden Palace Resort' }}
                    </router-link>
                </div>
                <ul class="nav-links" :class="{ 'active': mobileMenuOpen }">
                    <li><a href="#home" @click="mobileMenuOpen = false">{{ t('navHome') }}</a></li>
                    <li v-if="isSectionActive('about')"><a href="#about" @click="mobileMenuOpen = false">{{ t('navAbout') }}</a></li>
                    <li v-if="isSectionActive('gallery')"><a href="#gallery" @click="mobileMenuOpen = false">{{ t('navGallery') }}</a></li>
                    <li v-if="isSectionActive('videos')"><a href="#videos" @click="mobileMenuOpen = false">{{ t('navVideos') }}</a></li>
                    <li v-if="isSectionActive('team')"><a href="#team" @click="mobileMenuOpen = false">{{ t('navTeam') }}</a></li>
                    <li v-if="isSectionActive('events')"><a href="#events" @click="mobileMenuOpen = false">{{ t('navEvents') }}</a></li>
                    <li v-if="isSectionActive('membership')"><a href="#membership" @click="mobileMenuOpen = false">{{ t('navMembership') }}</a></li>
                    <li v-if="isSectionActive('booking')"><a href="#booking" @click="mobileMenuOpen = false">{{ t('navBooking') }}</a></li>
                    <li v-if="isSectionActive('food')">
                        <a href="#food" class="nav-highlight" @click="mobileMenuOpen = false">
                            <i class="fas fa-utensils"></i> {{ t('navFood') }}
                        </a>
                    </li>
                    <li><a href="#contact" @click="mobileMenuOpen = false">{{ t('navContact') }}</a></li>
                    <li class="mobile-auth-item">
                        <router-link v-if="isLoggedIn" to="/admin/dashboard" @click="mobileMenuOpen = false" class="mobile-dash-link">
                            <i class="fas fa-gauge-high mr-1"></i> {{ t('navDashboard') }}
                        </router-link>
                        <router-link v-else to="/login" @click="mobileMenuOpen = false" class="mobile-login-link">
                            <i class="fas fa-sign-in-alt mr-1"></i> {{ t('navLogin') }}
                        </router-link>
                    </li>
                </ul>

                <div class="nav-actions">
                    <button type="button" class="lang-toggle" @click="toggleLanguage">
                        <i class="fas fa-globe"></i>
                        <span>{{ currentLang === 'bn' ? 'English' : 'বাংলা' }}</span>
                    </button>

                    <!-- Login / Dashboard direct link -->
                    <router-link v-if="isLoggedIn" to="/admin/dashboard" class="nav-auth-btn dashboard-btn" title="Go to Admin Dashboard">
                        <i class="fas fa-gauge-high"></i>
                        <span>{{ t('navDashboard') }}</span>
                    </router-link>
                    <router-link v-else to="/login" class="nav-auth-btn login-btn" title="Staff / Admin Login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ t('navLogin') }}</span>
                    </router-link>

                    <div class="menu-toggle" @click="mobileMenuOpen = !mobileMenuOpen">
                        <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'"></i>
                    </div>
                </div>
            </nav>
        </header>

        <!-- ========== 1. HERO SLIDER ========== -->
        <div class="slider" id="home">
            <div class="slide" v-for="(slide, index) in sliders" :key="slide.id"
                :class="{ 'active': activeSlide === index }"
                :style="{ backgroundImage: 'linear-gradient(rgba(10,26,14,0.65), rgba(10,26,14,0.75)), url(' + slide.image + ')' }">
                <div class="slide-content">
                    <h1>{{ slide.title }}</h1>
                    <p>{{ slide.subtitle }}</p>
                    <div class="hero-btns">
                        <a v-if="slide.btn_text1" :href="slide.btn_link1 || '#booking'" class="btn btn-primary">{{ slide.btn_text1 }}</a>
                        <a v-if="slide.btn_text2" :href="slide.btn_link2 || '#about'" class="btn btn-outline">{{ slide.btn_text2 }}</a>
                    </div>
                </div>
            </div>

            <button class="slider-arrow prev" @click="prevSlide"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-arrow next" @click="nextSlide"><i class="fas fa-chevron-right"></i></button>
            <div class="slider-dots">
                <span v-for="(slide, index) in sliders" :key="slide.id"
                    class="dot" :class="{ 'active': activeSlide === index }"
                    @click="activeSlide = index"></span>
            </div>
        </div>

        <!-- DYNAMIC SECTIONS CONTAINER (Sorted from Admin: 2 to 12) -->
        <div class="dynamic-sections">
            <template v-for="sec in sortedSections" :key="sec.section_key">
                <!-- 2. ABOUT US -->
                <section v-if="sec.section_key === 'about' && sec.status === 5" id="about">
                    <h2 class="section-title">{{ settings.resort_about_title || t('aboutTitle') }}</h2>
                    <p class="section-sub">{{ settings.resort_about_sub || t('aboutSub') }}</p>

                    <div class="about-container">
                        <div class="about-text">
                            <h3>{{ settings.resort_about_head || t('aboutHead') }}</h3>
                            <p>{{ settings.resort_about_p1 || t('aboutP1') }}</p>
                            <p>{{ settings.resort_about_p2 || t('aboutP2') }}</p>
                            <div class="about-features">
                                <div class="feature-item"><i class="fas fa-wifi"></i> {{ settings.resort_feat1 || t('feat1') }}</div>
                                <div class="feature-item"><i class="fas fa-swimming-pool"></i> {{ settings.resort_feat2 || t('feat2') }}</div>
                                <div class="feature-item"><i class="fas fa-utensils"></i> {{ settings.resort_feat3 || t('feat3') }}</div>
                                <div class="feature-item"><i class="fas fa-parking"></i> {{ settings.resort_feat4 || t('feat4') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Merged About Event Showcase Slider -->
                    <div class="about-showcase-wrap" v-if="aboutSliders.length > 0">
                        <div class="about-showcase-header">
                            <h4><i class="fas fa-star text-gold"></i> {{ t('special_events_features') }}</h4>
                            <div class="showcase-nav">
                                <button @click="prevAboutEvent"><i class="fas fa-chevron-left"></i></button>
                                <span>{{ activeAboutIndex + 1 }} / {{ aboutSliders.length }}</span>
                                <button @click="nextAboutEvent"><i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                        <div class="showcase-card" v-if="aboutSliders[activeAboutIndex]">
                            <img :src="aboutSliders[activeAboutIndex].image" alt="showcase" class="showcase-img" />
                            <div class="showcase-content">
                                <span class="showcase-tag">{{ aboutSliders[activeAboutIndex].tag || t('tag_special') }}</span>
                                <h4>{{ aboutSliders[activeAboutIndex].title }}</h4>
                                <p>{{ aboutSliders[activeAboutIndex].description }}</p>
                                <a href="#booking" class="btn btn-outline-sm">{{ t('bookNow') }}</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. PHOTO GALLERY -->
                <section v-if="sec.section_key === 'gallery' && sec.status === 5" id="gallery" class="bg-alt">
                    <h2 class="section-title">{{ t('galleryTitle') }}</h2>
                    <p class="section-sub">{{ t('gallerySub') }}</p>

                    <div class="gallery-filters">
                        <button v-for="cat in ['all', 'resort', 'rooms', 'nature', 'events']" :key="cat"
                            :class="{ 'active': activeGalleryCat === cat }"
                            @click="activeGalleryCat = cat">
                            {{ galleryCatLabel(cat) }}
                        </button>
                    </div>

                    <div class="gallery-grid">
                        <div v-for="item in filteredPhotos" :key="item.id" class="gallery-item" @click="previewImage(item.image)">
                            <img :src="item.image" :alt="item.title" />
                            <div class="gallery-overlay">
                                <h4>{{ item.title }}</h4>
                                <span><i class="fas fa-search-plus"></i></span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4. VIDEO GALLERY -->
                <section v-if="sec.section_key === 'videos' && sec.status === 5" id="videos">
                    <h2 class="section-title">{{ t('videoTitle') }}</h2>
                    <p class="section-sub">{{ t('videoSub') }}</p>

                    <div class="video-grid">
                        <div v-for="v in videos" :key="v.id" class="video-card">
                            <div class="video-thumbnail" @click="activeVideoUrl = v.video_url">
                                <img :src="v.image" :alt="v.title" />
                                <div class="play-btn"><i class="fas fa-play"></i></div>
                            </div>
                            <div class="video-body">
                                <h4>{{ v.title }}</h4>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 5. FACEBOOK PAGE -->
                <section v-if="sec.section_key === 'facebook' && sec.status === 5" id="facebook" class="bg-alt">
                    <h2 class="section-title">{{ t('fbSectionTitle') }}</h2>
                    <p class="section-sub">{{ t('fbSectionSub') }}</p>

                    <div class="fb-wrapper">
                        <div class="fb-box">
                            <div class="fb-header">
                                <i class="fab fa-facebook text-primary text-3xl"></i>
                                <div>
                                    <h4>{{ settings.resort_name || 'Garden Palace Resort Sylhet' }}</h4>
                                    <p>{{ t('official_fb_page') }}</p>
                                </div>
                            </div>
                            <p class="fb-text">{{ t('fb_page_cta') }}</p>
                            <a :href="settings.resort_facebook || 'https://facebook.com'" target="_blank" class="btn btn-primary">
                                <i class="fab fa-facebook"></i> {{ t('visit_fb_page') }}
                            </a>
                        </div>
                    </div>
                </section>

                <!-- 6. PARTNERS -->
                <section v-if="sec.section_key === 'partners' && sec.status === 5" id="partners">
                    <h2 class="section-title">{{ t('partnersTitle') }}</h2>
                    <p class="section-sub">{{ t('partnersSub') }}</p>

                    <div class="partners-grid">
                        <div v-for="p in partners" :key="p.id" class="partner-card">
                            <img :src="p.image" :alt="p.name" />
                            <h5>{{ p.name }}</h5>
                        </div>
                    </div>
                </section>

                <!-- 7. OUR TEAM -->
                <section v-if="sec.section_key === 'team' && sec.status === 5" id="team" class="bg-alt">
                    <h2 class="section-title">{{ t('teamTitle') }}</h2>
                    <p class="section-sub">{{ t('teamSub') }}</p>

                    <div class="team-grid">
                        <div v-for="tm in teams" :key="tm.id" class="team-card">
                            <img :src="tm.image" :alt="tm.name" />
                            <h4>{{ tm.name }}</h4>
                            <div class="role">{{ tm.designation }}</div>
                            <p v-if="tm.bio">{{ tm.bio }}</p>
                            <div class="team-social">
                                <a v-if="tm.facebook" :href="tm.facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a v-if="tm.linkedin" :href="tm.linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <a v-if="tm.instagram" :href="tm.instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 8. LATEST EVENTS -->
                <section v-if="sec.section_key === 'events' && sec.status === 5" id="events">
                    <h2 class="section-title">{{ t('eventsTitle') }}</h2>
                    <p class="section-sub">{{ t('eventsSub') }}</p>

                    <div class="events-grid">
                        <div v-for="ev in latestEvents" :key="ev.id" class="event-card">
                            <div class="event-img">
                                <img :src="ev.image" :alt="ev.title" />
                                <div class="event-badge">{{ ev.tag || t('tag_event') }}</div>
                            </div>
                            <div class="event-body">
                                <h4>{{ ev.title }}</h4>
                                <div class="event-meta" v-if="ev.event_date">
                                    <i class="fas fa-clock"></i> {{ ev.event_date }}
                                </div>
                                <p>{{ ev.description }}</p>
                                <a href="#booking" class="btn btn-outline-sm">{{ t('bookNow') }}</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 9. MEMBERSHIP PLANS -->
                <section v-if="sec.section_key === 'membership' && sec.status === 5" id="membership" class="bg-alt">
                    <h2 class="section-title">{{ t('membershipTitle') }}</h2>
                    <p class="section-sub">{{ t('membershipSub') }}</p>

                    <div class="membership-grid">
                        <div v-for="pl in plans" :key="pl.id" class="membership-card" :class="{ 'featured': pl.is_featured }">
                            <span v-if="pl.badge" class="plan-badge">{{ pl.badge }}</span>
                            <div class="plan-icon"><i class="fas fa-crown"></i></div>
                            <h4>{{ pl.title }}</h4>
                            <div class="price">৳{{ pl.price }} <small>/{{ pl.duration || t('year') }}</small></div>
                            <ul>
                                <li v-for="(feat, idx) in parsePlanFeatures(pl.features)" :key="idx">
                                    <i class="fas fa-check"></i> {{ feat }}
                                </li>
                            </ul>
                            <a :href="pl.btn_link || '#booking'" class="membership-btn">{{ pl.btn_text || t('joinNow') }}</a>
                        </div>
                    </div>
                </section>

                <!-- 10. BOOKING (RESORT & PARTY) -->
                <section v-if="sec.section_key === 'booking' && sec.status === 5" id="booking">
                    <h2 class="section-title">{{ t('bookingTitle') }}</h2>
                    <p class="section-sub">{{ t('bookingSub') }}</p>

                    <div class="booking-tabs">
                        <button type="button" :class="{ 'active': activeBookingTab === 'resort' }" @click="activeBookingTab = 'resort'; bookingSuccessMessage = null">
                            <i class="fas fa-bed"></i> {{ t('navBooking') }}
                        </button>
                        <button type="button" :class="{ 'active': activeBookingTab === 'party' }" @click="activeBookingTab = 'party'; bookingSuccessMessage = null">
                            <i class="fas fa-glass-cheers"></i> {{ t('navParty') }}
                        </button>
                    </div>

                    <div class="booking-card-wrap">
                        <!-- Success Confirmation Screen -->
                        <div v-if="bookingSuccessMessage" class="booking-success-box">
                            <div class="success-icon-wrap">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h4>{{ t('booking_received_title') }}</h4>
                            <p class="success-msg-desc">{{ bookingSuccessMessage }}</p>
                            <div class="success-contact-badge">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ t('urgent_contact_prompt') }}</span>
                                <strong>{{ settings.resort_phone || '+880 1711-123456' }}</strong>
                            </div>
                            <button type="button" @click="bookingSuccessMessage = null" class="btn-new-booking">
                                <i class="fas fa-calendar-plus mr-1"></i> {{ t('make_another_booking') }}
                            </button>
                        </div>

                        <form v-else @submit.prevent="submitBooking">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>{{ t('fullName') }} *</label>
                                    <input v-model="bookingForm.name" type="text" :placeholder="t('phName')" required />
                                </div>
                                <div class="form-group">
                                    <label>{{ t('phone') }} *</label>
                                    <input v-model="bookingForm.phone" type="tel" :placeholder="t('phPhone')" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>{{ t('email') }}</label>
                                    <input v-model="bookingForm.email" type="email" placeholder="example@gmail.com" />
                                </div>
                                <div class="form-group">
                                    <label>{{ activeBookingTab === 'resort' ? t('roomType') : t('eventType') }}</label>
                                    <select v-if="activeBookingTab === 'resort'" v-model="bookingForm.room_type">
                                        <option :value="t('opt_deluxe_cottage')">{{ t('opt_deluxe_cottage') }}</option>
                                        <option :value="t('opt_family_suite')">{{ t('opt_family_suite') }}</option>
                                        <option :value="t('opt_vip_villa')">{{ t('opt_vip_villa') }}</option>
                                        <option :value="t('opt_couple_room')">{{ t('opt_couple_room') }}</option>
                                    </select>
                                    <select v-else v-model="bookingForm.event_type">
                                        <option :value="t('opt_birthday_party')">{{ t('opt_birthday_party') }}</option>
                                        <option :value="t('opt_wedding_party')">{{ t('opt_wedding_party') }}</option>
                                        <option :value="t('opt_corporate_meeting')">{{ t('opt_corporate_meeting') }}</option>
                                        <option :value="t('opt_family_gathering')">{{ t('opt_family_gathering') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>{{ activeBookingTab === 'resort' ? t('checkin_date') : t('event_date') }} *</label>
                                    <input v-model="bookingForm.booking_date" type="date" required />
                                </div>
                                <div class="form-group">
                                    <label>{{ t('guests') }}</label>
                                    <input v-model="bookingForm.guests_count" type="number" min="1" value="2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ t('specialNote') }}</label>
                                <textarea v-model="bookingForm.notes" rows="3" :placeholder="t('phNote')"></textarea>
                            </div>
                            <button type="submit" class="btn btn-submit" :disabled="bookingSubmitting">
                                {{ bookingSubmitting ? t('processing') : (activeBookingTab === 'resort' ? t('bookNow') : t('confirm_party_booking')) }}
                            </button>
                        </form>
                    </div>
                </section>

                <!-- 11. RESTAURANT / FOOD ORDER (IMPORTANT REQUIREMENT 1 & 12) -->
                <section v-if="sec.section_key === 'food' && sec.status === 5" id="food" class="bg-alt food-section-wrap">
                    <h2 class="section-title">{{ t('foodTitle') }}</h2>
                    <p class="section-sub">{{ t('foodSub') }}</p>

                    <!-- DINING TABLES SELECTOR & QR CODE DISPLAY -->
                    <div class="tables-qr-container">
                        <!-- Table Chips / Selector -->
                        <div class="table-chips-header" v-if="diningTables.length > 0">
                            <span class="chips-title"><i class="fas fa-chair text-gold"></i> {{ t('select_table') }}</span>
                            <div class="table-chips">
                                <button type="button" v-for="table in diningTables" :key="table.id"
                                    class="table-chip" :class="{ 'active': activeTable && activeTable.id === table.id }"
                                    @click="selectedTableId = table.id">
                                    <i class="fas fa-utensils mr-1"></i>
                                    <span>{{ table.name }}</span>
                                    <small v-if="table.size">({{ table.size }} {{ t('seating') }})</small>
                                </button>
                            </div>
                        </div>

                        <!-- Main Table Display Card -->
                        <div class="table-qr-card" v-if="activeTable">
                            <div class="table-qr-visual">
                                <div class="qr-code-box">
                                    <img :src="activeTable.qr_code" :alt="activeTable.name + ' QR Code'" class="table-qr-img" />
                                    <span class="qr-label"><i class="fas fa-qrcode mr-1"></i> {{ activeTable.name }} QR</span>
                                </div>
                            </div>
                            <div class="table-qr-details">
                                <h3 class="room-order-heading">
                                    {{ t('room_service_table_order_title') }}
                                </h3>
                                <p class="room-order-desc">
                                    {{ t('room_service_table_order_sub') }}
                                </p>

                                <div class="table-action-btns">
                                    <router-link :to="'/menu/' + activeTable.slug + '?order_type=room'" class="btn btn-room-order">
                                        <i class="fas fa-bed mr-2"></i>
                                        <span>{{ t('order_food_from_room') }}</span>
                                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </router-link>

                                    <router-link :to="'/menu/' + activeTable.slug" class="btn btn-table-direct">
                                        <i class="fas fa-utensils mr-2"></i>
                                        <span>{{ activeTable.name }} - {{ t('view_menu_order_from_table') }}</span>
                                    </router-link>
                                </div>
                            </div>
                        </div>

                        <!-- All Tables Grid (Alternative Quick View) -->
                        <div class="all-tables-section" v-if="diningTables.length > 1">
                            <h4 class="all-tables-head">
                                <i class="fas fa-th text-gold mr-1"></i> {{ t('all_tables_qr_title') }}
                            </h4>
                            <div class="tables-grid">
                                <div v-for="table in diningTables" :key="'grid-' + table.id" class="table-grid-item"
                                    :class="{ 'selected': activeTable && activeTable.id === table.id }"
                                    @click="selectedTableId = table.id">
                                    <div class="grid-table-top">
                                        <h5>{{ table.name }}</h5>
                                        <span class="grid-size" v-if="table.size">{{ table.size }} {{ t('seating') }}</span>
                                    </div>
                                    <div class="grid-qr-wrap">
                                        <img :src="table.qr_code" :alt="table.name" />
                                    </div>
                                    <p class="grid-branch" v-if="table.branch_name">{{ table.branch_name }}</p>
                                    <router-link :to="'/menu/' + table.slug" class="grid-order-link" @click.stop>
                                        {{ t('open_menu') }} <i class="fas fa-arrow-right ml-1"></i>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Food items grid loaded from Sohoj RMS catalog -->
                    <div class="food-popular-head">
                        <div class="food-popular-titles">
                            <span class="food-tagline">{{ t('food_popular_tag') }}</span>
                            <h3 class="food-main-title">{{ t('food_popular_title') }}</h3>
                            <p class="food-popular-sub">{{ t('food_popular_sub') }}</p>
                        </div>
                        <router-link :to="'/menu/' + (activeTable ? activeTable.slug : 'table-1')" class="see-all-link">
                            {{ t('browse_full_menu') }} ({{ totalFoodCount }}+)
                        </router-link>
                    </div>

                    <div class="food-grid">
                        <div v-for="dish in foodItems" :key="dish.id" class="food-item">
                            <div class="food-thumb-wrap">
                                <img :src="dish.thumb || dish.cover || '/images/item/thumb.png'" :alt="dish.name" loading="lazy" />
                                <span v-if="dish.category_name" class="food-cat-badge">
                                    {{ dish.category_name }}
                                </span>
                            </div>
                            <div class="food-info">
                                <div class="food-rating">
                                    <span class="rating-label">{{ t('rating_label') }}</span>
                                    <span class="rating-num">4.9 / 5.0</span>
                                </div>
                                <h4 class="food-title" :title="dish.name">{{ dish.name }}</h4>
                                <p v-if="dish.description" class="food-desc">{{ truncateText(dish.description, 55) }}</p>
                                <div class="food-bottom">
                                    <div class="price-wrap">
                                        <span class="currency">৳</span>
                                        <span class="amount">{{ dish.flat_price || dish.price }}</span>
                                    </div>
                                    <router-link :to="'/menu/' + (activeTable ? activeTable.slug : 'table-1')" class="order-mini-btn">
                                        {{ t('order_now') }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Full Menu Showcase Banner -->
                    <div class="food-catalog-banner">
                        <div class="catalog-banner-content">
                            <h4>{{ t('catalog_banner_title') }}</h4>
                            <p>{{ t('catalog_banner_sub') }}</p>
                        </div>
                        <router-link :to="'/menu/' + (activeTable ? activeTable.slug : 'table-1')" class="btn-catalog-cta">
                            {{ t('catalog_banner_btn') }}
                        </router-link>
                    </div>
                </section>

                <!-- 12. CONTACT & MAP -->
                <section v-if="sec.section_key === 'contact' && sec.status === 5" id="contact" class="contact-section">
                    <div class="contact-wrap">
                        <div class="contact-info">
                            <h3>{{ t('contactHead') }}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> {{ settings.resort_address || 'গার্ডেন প্যালেস রিসোর্ট সিলেট, বাংলাদেশ' }}</p>
                            <p><i class="fas fa-phone-alt"></i> {{ settings.resort_phone || '+৮৮০ ১৭১১-১২৩৪৫৬' }}</p>
                            <p><i class="fas fa-envelope"></i> {{ settings.resort_email || 'info@gardenpalaceresortsylhet.com' }}</p>
                            <p><i class="fas fa-clock"></i> {{ t('checkin_checkout_time', { checkin: settings.resort_checkin || '12:00 PM', checkout: settings.resort_checkout || '12:00 PM' }) }}</p>

                            <div class="contact-social">
                                <h4>{{ t('followUs') }}:</h4>
                                <div class="social-icons">
                                    <a v-if="settings.resort_facebook" :href="settings.resort_facebook" target="_blank"><i class="fab fa-facebook"></i></a>
                                    <a v-if="settings.resort_instagram" :href="settings.resort_instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <a v-if="settings.resort_youtube" :href="settings.resort_youtube" target="_blank"><i class="fab fa-youtube"></i></a>
                                    <a v-if="settings.resort_whatsapp" :href="'https://wa.me/' + settings.resort_whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="map-container">
                            <iframe :src="settings.resort_map_iframe || 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115612.5470919157!2d91.7990432!3d24.8949298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3750558b9c0e5c5f%3A0x9a6e5b1c5b1c5b1c!2sSylhet!5e0!3m2!1sen!2sbd!4v1700000000000'"
                                allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </section>
            </template>
        </div>

        <!-- ========== FOOTER ========== -->
        <footer>
            <div class="footer-wrap">
                <div class="footer-col">
                    <h4>{{ settings.resort_name || 'Garden Palace Resort' }}</h4>
                    <p>{{ settings.resort_tagline || 'সিলেটের সবুজ-শ্যামল প্রকৃতির মাঝে একটি স্বর্গীয় আশ্রয়।' }}</p>
                </div>
                <div class="footer-col">
                    <h4>{{ t('quick_links') }}</h4>
                    <ul>
                        <li><a href="#about">{{ t('about_us') }}</a></li>
                        <li><a href="#gallery">{{ t('galleryTitle') }}</a></li>
                        <li><a href="#booking">{{ t('room_booking') }}</a></li>
                        <li><router-link to="/rms">{{ t('restaurant_food_menu') }}</router-link></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>{{ t('restaurant_services') }}</h4>
                    <ul>
                        <li><router-link to="/rms">{{ t('online_food_order') }}</router-link></li>
                        <li><a href="#food">{{ t('table_qr_scan') }}</a></li>
                        <li><a href="#booking">{{ t('party_catering_booking') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ new Date().getFullYear() }} {{ settings.resort_name || 'Garden Palace Resort Sylhet' }} &amp; Sohoj RMS. All rights reserved.</p>
            </div>
        </footer>

        <!-- Video Modal -->
        <div v-if="activeVideoUrl" class="modal-overlay" @click="activeVideoUrl = null">
            <div class="modal-video-box" @click.stop>
                <button class="modal-close-btn" @click="activeVideoUrl = null">&times;</button>
                <iframe :src="activeVideoUrl" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div v-if="previewImageUrl" class="modal-overlay" @click="previewImageUrl = null">
            <div class="modal-image-box" @click.stop>
                <button class="modal-close-btn" @click="previewImageUrl = null">&times;</button>
                <img :src="previewImageUrl" alt="preview" />
            </div>
        </div>
    </div>
</template>

<script>
import alertService from "../../../services/alertService";

export default {
    name: "ResortLandingComponent",
    data() {
        return {
            currentLang: "bn",
            isScrolled: false,
            mobileMenuOpen: false,
            activeSlide: 0,
            slideTimer: null,
            activeAboutIndex: 0,
            activeGalleryCat: "all",
            activeVideoUrl: null,
            previewImageUrl: null,
            activeBookingTab: "resort",
            selectedTableId: null,
            bookingSubmitting: false,
            bookingSuccessMessage: null,
            bookingForm: {
                type: "resort",
                name: "",
                phone: "",
                email: "",
                booking_date: "",
                room_type: "",
                event_type: "",
                guests_count: 2,
                notes: "",
            },
            dict: {
                bn: {
                    navHome: "হোম",
                    navAbout: "পরিচিতি",
                    navGallery: "ফটো গ্যালারি",
                    navVideos: "ভিডিও",
                    navTeam: "আমাদের টিম",
                    navEvents: "সর্বশেষ ইভেন্ট",
                    navMembership: "মেম্বারশিপ",
                    navBooking: "রিসর্ট বুকিং",
                    navParty: "পার্টি বুকিং",
                    navFood: "খাবার অর্ডার",
                    navContact: "যোগাযোগ",
                    navLogin: "লগইন",
                    navDashboard: "ড্যাশবোর্ড",
                    seating: "আসন",
                    followUs: "আমাদের ফলো করুন",
                    aboutTitle: "আমাদের সম্পর্কে",
                    aboutSub: "প্রকৃতির কোলে একটি অনন্য আতিথেয়তা",
                    aboutHead: "গার্ডেন প্যালেস রিসোর্ট সিলেট",
                    aboutP1: "সিলেট শহরের প্রাণকেন্দ্র থেকে মাত্র কয়েক মিনিট দূরে অবস্থিত গার্ডেন প্যালেস রিসোর্ট সিলেট। চারপাশে সবুজ চা বাগান, পাহাড় ও নদীর মনোরম দৃশ্য।",
                    aboutP2: "আমাদের রিসর্টে রয়েছে বিলাসবহুল কক্ষ, সুইমিং পুল, রেস্টুরেন্ট, কনফারেন্স হল, পার্টি ভেন্যু এবং বিশাল সবুজ বাগান।",
                    feat1: "ফ্রি ওয়াইফাই",
                    feat2: "সুইমিং পুল",
                    feat3: "রেস্টুরেন্ট",
                    feat4: "পার্কিং",
                    galleryTitle: "ফটো গ্যালারি",
                    gallerySub: "আমাদের রিসোর্টের কিছু সুন্দর মুহূর্ত",
                    videoTitle: "ভিডিও গ্যালারি",
                    videoSub: "রিসোর্টের পরিবেশ ও সুবিধাগুলো দেখুন",
                    fbSectionTitle: "আমাদের ফেসবুক পেজ",
                    fbSectionSub: "আমাদের ফেসবুক পেজে লাইক ও ফলো করে আপডেট পান",
                    partnersTitle: "আমাদের পার্টনার",
                    partnersSub: "বিশ্বস্ত প্রতিষ্ঠানসমূহের সাথে আমাদের গৌরবময় অংশীদারিত্ব",
                    teamTitle: "আমাদের টিম",
                    teamSub: "আমাদের অভিজ্ঞ ও নিবেদিতপ্রাণ পেশাদার টিম",
                    eventsTitle: "সর্বশেষ ইভেন্ট",
                    eventsSub: "আমাদের রিসোর্টে আসন্ন ও সাম্প্রতিক ইভেন্টসমূহ",
                    membershipTitle: "মেম্বারশিপ প্ল্যান",
                    membershipSub: "আমাদের বিশেষ মেম্বারশিপে যোগ দিয়ে পান অসাধারণ সুবিধা",
                    bookingTitle: "বুকিং করুন",
                    bookingSub: "রিসর্ট রুম অথবা পার্টি ভেন্যু বুক করুন সহজেই",
                    foodTitle: "রেস্টুরেন্ট ফুড অর্ডার",
                    foodSub: "আমাদের জনপ্রিয় খাবারগুলো অর্ডার করুন",
                    food_popular_tag: "শেফের সেরা বাছাইকৃত স্পেশাল মেনু",
                    food_popular_title: "জনপ্রিয় খাবারসমূহ",
                    food_popular_sub: "আমাদের কিচেনের সেরা ও সবচেয়ে বেশি অর্ডারকৃত ১২টি সুস্বাদু খাবার",
                    browse_full_menu: "সম্পূর্ণ মেনু দেখুন",
                    order_now: "অর্ডার করুন",
                    rating_label: "রেটিং",
                    catalog_banner_title: "রেস্টুরেন্টের সম্পূর্ণ মেনু ক্যাটালগ দেখতে চান?",
                    catalog_banner_sub: "১০০+ সুস্বাদু খাবার, স্ন্যাক্স, বেভারেজ ও স্পেশাল ডেজার্ট আইটেম আপনার পছন্দের অপেক্ষায়",
                    catalog_banner_btn: "সম্পূর্ণ মেনু ক্যাটালগ দেখুন",
                    contactHead: "যোগাযোগের ঠিকানা",
                    bookNow: "এখনই বুক করুন",
                    joinNow: "এখনই যোগ দিন",
                    fullName: "আপনার পূর্ণ নাম",
                    phone: "মোবাইল নম্বর",
                    email: "ইমেইল এড্রেস",
                    roomType: "রুম / কটেজ নির্বাচন করুন",
                    eventType: "অনুষ্ঠানের ধরন",
                    guests: "অতিথির সংখ্যা",
                    specialNote: "বিশেষ নির্দেশনা (ঐচ্ছিক)",
                    phName: "যেমন: মোঃ করিম",
                    phPhone: "যেমন: ০১৭১১-১২৩৪৫৬",
                    phNote: "আপনার কোনো বিশেষ চাহিদা থাকলে লিখুন...",
                },
                en: {
                    navHome: "Home",
                    navAbout: "About",
                    navGallery: "Gallery",
                    navVideos: "Videos",
                    navTeam: "Our Team",
                    navEvents: "Events",
                    navMembership: "Membership",
                    navBooking: "Resort Booking",
                    navParty: "Party Booking",
                    navFood: "Food Order",
                    navContact: "Contact",
                    navLogin: "Login",
                    navDashboard: "Dashboard",
                    seating: "Seats",
                    followUs: "Follow Us",
                    aboutTitle: "About Us",
                    aboutSub: "A unique hospitality in the lap of nature",
                    aboutHead: "Garden Palace Resort Sylhet",
                    aboutP1: "Located just minutes away from the heart of Sylhet city, Garden Palace Resort Sylhet is surrounded by lush green tea gardens, hills and rivers.",
                    aboutP2: "Our resort features luxury rooms, swimming pool, restaurant, conference hall, party venue and vast green gardens.",
                    feat1: "Free WiFi",
                    feat2: "Swimming Pool",
                    feat3: "Restaurant",
                    feat4: "Parking",
                    galleryTitle: "Photo Gallery",
                    gallerySub: "Moments of serenity and beauty at our resort",
                    videoTitle: "Video Gallery",
                    videoSub: "Experience our ambience and amenities",
                    fbSectionTitle: "Our Facebook Page",
                    fbSectionSub: "Follow our Facebook page for regular updates",
                    partnersTitle: "Our Partners",
                    partnersSub: "Proud partnerships with trusted institutions",
                    teamTitle: "Our Team",
                    teamSub: "Our experienced and dedicated professional team",
                    eventsTitle: "Latest Events",
                    eventsSub: "Upcoming and recent events at our resort",
                    membershipTitle: "Membership Plans",
                    membershipSub: "Join our exclusive membership for special benefits",
                    bookingTitle: "Make a Reservation",
                    bookingSub: "Book luxury rooms or party venues easily",
                    foodTitle: "Restaurant Food Order",
                    foodSub: "Order our popular delicacies directly",
                    food_popular_tag: "Chef's Signature Selection",
                    food_popular_title: "Popular Dishes",
                    food_popular_sub: "Top 12 best-selling delicacies freshly prepared from our master kitchen",
                    browse_full_menu: "Browse Full Menu",
                    order_now: "Order",
                    rating_label: "Rating",
                    catalog_banner_title: "Want to explore our complete restaurant menu?",
                    catalog_banner_sub: "Over 100+ appetizers, grills, beverages and desserts waiting for your order",
                    catalog_banner_btn: "Explore Full Menu",
                    contactHead: "Contact Address",
                    bookNow: "Book Now",
                    joinNow: "Join Now",
                    fullName: "Full Name",
                    phone: "Phone Number",
                    email: "Email Address",
                    roomType: "Select Room / Cottage",
                    eventType: "Event Type",
                    guests: "Number of Guests",
                    specialNote: "Special Instructions (Optional)",
                    phName: "e.g. John Doe",
                    phPhone: "e.g. +880 1711-123456",
                    phNote: "Tell us about any special requirements...",
                }
            }
        };
    },
    watch: {
        diningTables(newVal) {
            if (newVal && newVal.length > 0 && !this.selectedTableId) {
                this.selectedTableId = newVal[0].id;
            }
        }
    },
    computed: {
        landingContent() { return this.$store.getters['resort/landingContent'] || {}; },
        settings() { return this.landingContent.settings || {}; },
        sections() { return this.landingContent.sections || []; },
        sortedSections() {
            return [...this.sections].sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));
        },
        sliders() { return this.landingContent.sliders || []; },
        photos() { return this.landingContent.photos || []; },
        videos() { return this.landingContent.videos || []; },
        aboutSliders() { return this.landingContent.about_sliders || []; },
        latestEvents() { return this.landingContent.latest_events || []; },
        teams() { return this.landingContent.teams || []; },
        plans() { return this.landingContent.plans || []; },
        partners() { return this.landingContent.partners || []; },
        foodItems() { return (this.landingContent.food_items || []).slice(0, 12); },
        totalFoodCount() { return this.landingContent.total_food_count || (this.foodItems ? this.foodItems.length : 0); },
        diningTables() { return this.landingContent.dining_tables || []; },
        activeTable() {
            if (this.diningTables.length === 0) return null;
            return this.diningTables.find(t => t.id === this.selectedTableId) || this.diningTables[0];
        },
        isLoggedIn() {
            return !!this.$store.getters.authStatus;
        },
        filteredPhotos() {
            if (this.activeGalleryCat === "all") return this.photos;
            return this.photos.filter(p => p.category === this.activeGalleryCat);
        },
        currentOrigin() {
            return window.location.origin;
        }
    },
    mounted() {
        if (this.$i18n && this.$i18n.locale) {
            this.currentLang = this.$i18n.locale;
        }
        this.bookingForm.room_type = this.t('opt_deluxe_cottage');
        this.bookingForm.event_type = this.t('opt_birthday_party');
        this.$store.dispatch('resort/fetchLandingContent');
        window.addEventListener('scroll', this.handleScroll);
        this.startSlideTimer();
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
        if (this.slideTimer) clearInterval(this.slideTimer);
    },
    methods: {
        t(key, params = {}) {
            if (this.$te && this.$te('resort.' + key)) {
                return this.$t('resort.' + key, params);
            }
            if (this.$te && this.$te(key)) {
                return this.$t(key, params);
            }
            let str = (this.dict[this.currentLang] && this.dict[this.currentLang][key]) || key;
            if (params && typeof params === 'object') {
                Object.keys(params).forEach(p => {
                    str = str.replace(`{${p}}`, params[p]);
                });
            }
            return str;
        },
        toggleLanguage() {
            this.currentLang = this.currentLang === 'bn' ? 'en' : 'bn';
            if (this.$i18n) {
                this.$i18n.locale = this.currentLang;
            }
        },
        truncateText(text, length = 60) {
            if (!text) return '';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },
        handleScroll() {
            this.isScrolled = window.scrollY > 50;
        },
        startSlideTimer() {
            this.slideTimer = setInterval(() => {
                if (this.sliders.length > 0) {
                    this.activeSlide = (this.activeSlide + 1) % this.sliders.length;
                }
            }, 5000);
        },
        nextSlide() {
            if (this.sliders.length > 0) {
                this.activeSlide = (this.activeSlide + 1) % this.sliders.length;
            }
        },
        prevSlide() {
            if (this.sliders.length > 0) {
                this.activeSlide = (this.activeSlide - 1 + this.sliders.length) % this.sliders.length;
            }
        },
        nextAboutEvent() {
            if (this.aboutSliders.length > 0) {
                this.activeAboutIndex = (this.activeAboutIndex + 1) % this.aboutSliders.length;
            }
        },
        prevAboutEvent() {
            if (this.aboutSliders.length > 0) {
                this.activeAboutIndex = (this.activeAboutIndex - 1 + this.aboutSliders.length) % this.aboutSliders.length;
            }
        },
        isSectionActive(key) {
            if (!this.sections || this.sections.length === 0) return true;
            const sec = this.sections.find(s => s.section_key === key);
            return sec ? Number(sec.status) === 5 : false;
        },
        galleryCatLabel(cat) {
            const map = {
                all: this.currentLang === 'bn' ? 'সব ছবি' : 'All',
                resort: this.currentLang === 'bn' ? 'রিসোর্ট' : 'Resort',
                rooms: this.currentLang === 'bn' ? 'রুম ও সুইট' : 'Rooms',
                nature: this.currentLang === 'bn' ? 'প্রকৃতি' : 'Nature',
                events: this.currentLang === 'bn' ? 'ইভেন্ট' : 'Events',
            };
            return map[cat] || cat;
        },
        parsePlanFeatures(feat) {
            if (!feat) return [];
            if (Array.isArray(feat)) return feat;
            try { return JSON.parse(feat); } catch (e) { return feat.split("\n").filter(f => f.trim()); }
        },
        previewImage(url) {
            this.previewImageUrl = url;
        },
        submitBooking() {
            this.bookingSubmitting = true;
            this.bookingSuccessMessage = null;
            this.bookingForm.type = this.activeBookingTab;
            if (this.activeBookingTab === 'resort') {
                this.bookingForm.event_type = null;
            } else {
                this.bookingForm.room_type = null;
            }
            this.$store.dispatch('resort/submitBooking', this.bookingForm).then((res) => {
                const msg = res.data.message || 'বুকিং সফল হয়েছে!';
                this.bookingSuccessMessage = msg;
                alertService.success(msg);
                this.bookingForm = {
                    type: this.activeBookingTab,
                    name: "",
                    phone: "",
                    email: "",
                    booking_date: "",
                    room_type: "ডিলাক্স কটেজ",
                    event_type: "জন্মদিন উদযাপন",
                    guests_count: 2,
                    notes: "",
                };
            }).catch(err => {
                const errMsg = err.response?.data?.message || 'বুকিং সাবমিশনে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।';
                alertService.error(errMsg);
            }).finally(() => {
                this.bookingSubmitting = false;
            });
        }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap');

.resort-app {
    font-family: 'Hind Siliguri', 'Playfair Display', sans-serif;
    background-color: #f9f7f2;
    color: #2c2c2c;
    line-height: 1.6;
}

/* SITE HEADER (FIXED & STICKY AT TOP OF PAGE) */
.site-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    transition: all 0.3s ease;
}

.site-header .top-bar {
    background: #0f1a12;
    color: #d0c8b0;
    padding: 7px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    border-bottom: 1px solid #2e4a2e;
    transition: all 0.3s ease;
    overflow: hidden;
    max-height: 50px;
}
.top-bar-left {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}
.top-bar-left a, .top-bar-left span {
    color: #d0c8b0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}
.top-bar-left i { color: #c9a34e; }
.top-bar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}
.top-bar-right .social-label {
    color: #c9a34e;
    font-weight: 600;
}
.top-bar-right a {
    color: #d0c8b0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(201, 163, 78, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    transition: 0.3s;
}
.top-bar-right a:hover {
    background: #c9a34e;
    color: #0f1a12;
}

.site-header .navbar {
    background: rgba(10, 40, 20, 0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 5%;
    box-sizing: border-box;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

/* When scrolled down: collapse top-bar so navbar cleanly sticks at top: 0! */
.site-header.scrolled .top-bar {
    max-height: 0;
    padding-top: 0;
    padding-bottom: 0;
    opacity: 0;
    border-bottom: none;
}
.site-header.scrolled .navbar {
    padding: 9px 5%;
    background: rgba(8, 28, 14, 0.98);
    box-shadow: 0 8px 30px rgba(0,0,0,0.5);
}
.logo a {
    color: #c9a34e;
    font-size: 1.35rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Playfair Display', serif;
}
.nav-links {
    display: flex;
    list-style: none;
    gap: 18px;
    align-items: center;
    margin: 0;
    padding: 0;
}
.nav-links a {
    color: #fff;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 500;
    transition: 0.2s;
}
.nav-links a:hover {
    color: #c9a34e;
}
.nav-links .nav-highlight {
    background: rgba(201, 163, 78, 0.15);
    color: #c9a34e;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid #c9a34e;
}
.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.lang-toggle {
    background: rgba(201, 163, 78, 0.15);
    color: #c9a34e;
    border: 1px solid #c9a34e;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: 0.3s;
}
.lang-toggle:hover {
    background: #c9a34e;
    color: #0f1a12;
}
.rms-quick-btn {
    background: #c9a34e;
    color: #0f1a12;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: 0.3s;
}
.rms-quick-btn:hover {
    background: #e2ba64;
    transform: translateY(-1px);
}
.nav-auth-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: 0.3s;
}
.nav-auth-btn.login-btn {
    background: transparent;
    color: #c9a34e;
    border: 1.5px solid #c9a34e;
}
.nav-auth-btn.login-btn:hover {
    background: #c9a34e;
    color: #0f1a12;
}
.nav-auth-btn.dashboard-btn {
    background: #ff5622;
    color: #fff;
    border: 1.5px solid #ff5622;
}
.nav-auth-btn.dashboard-btn:hover {
    background: #e64a19;
}
.mobile-auth-item {
    display: none;
}
@media (max-width: 991px) {
    .mobile-auth-item {
        display: block;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    .mobile-dash-link {
        color: #ff5622 !important;
        font-weight: 700 !important;
    }
    .mobile-login-link {
        color: #c9a34e !important;
        font-weight: 700 !important;
    }
}
.menu-toggle {
    display: none;
    color: #fff;
    font-size: 1.3rem;
    cursor: pointer;
}

/* HERO SLIDER */
.slider {
    position: relative;
    height: 100vh;
    min-height: 560px;
    padding-top: 100px;
    box-sizing: border-box;
    overflow: hidden;
}
.slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 80px 20px 20px;
}
.slide.active {
    opacity: 1;
}
.slide-content {
    max-width: 850px;
    color: #fff;
    animation: fadeInUp 0.8s ease;
}
.slide-content h1 {
    font-size: 2.8rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 15px;
    font-family: 'Playfair Display', serif;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}
.slide-content p {
    font-size: 1.2rem;
    color: #e5ded0;
    margin-bottom: 25px;
}
.hero-btns {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}
.btn {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}
.btn-primary {
    background: #c9a34e;
    color: #0f1a12;
    border: none;
}
.btn-primary:hover {
    background: #d8b45f;
    transform: translateY(-2px);
}
.btn-outline {
    background: transparent;
    color: #fff;
    border: 2px solid #fff;
}
.btn-outline:hover {
    background: #fff;
    color: #0f1a12;
}
.btn-outline-sm {
    padding: 6px 16px;
    border: 1px solid #c9a34e;
    color: #c9a34e;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: 0.2s;
    display: inline-block;
}
.btn-outline-sm:hover {
    background: #c9a34e;
    color: #0f1a12;
}
.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(0,0,0,0.4);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
    z-index: 10;
}
.slider-arrow:hover {
    background: #c9a34e;
    color: #0f1a12;
}
.slider-arrow.prev { left: 25px; }
.slider-arrow.next { right: 25px; }
.slider-dots {
    position: absolute;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 10;
}
.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
    transition: 0.3s;
}
.dot.active {
    background: #c9a34e;
    width: 28px;
    border-radius: 10px;
}

/* SECTION STYLES */
section {
    padding: 70px 5%;
}
.bg-alt {
    background: #f2efe8;
}
.section-title {
    text-align: center;
    font-size: 2.2rem;
    color: #1e2b1e;
    font-family: 'Playfair Display', serif;
    margin-bottom: 6px;
}
.section-sub {
    text-align: center;
    font-size: 1rem;
    color: #777;
    margin-bottom: 45px;
}

/* ABOUT */
.about-container {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}
.about-text h3 {
    font-size: 1.5rem;
    color: #c9a34e;
    margin-bottom: 15px;
}
.about-text p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 16px;
    line-height: 1.8;
}
.about-features {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 25px;
}
.feature-item {
    background: #fff;
    padding: 10px 20px;
    border-radius: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    font-weight: 600;
    color: #1e2b1e;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #e5ded0;
}
.feature-item i { color: #c9a34e; }

.about-showcase-wrap {
    max-width: 800px;
    margin: 45px auto 0;
    background: #fff;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.06);
    border: 1px solid #e8e2d5;
}
.about-showcase-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0ebe0;
}
.about-showcase-header h4 {
    font-size: 1.1rem;
    color: #1e2b1e;
}
.showcase-nav {
    display: flex;
    align-items: center;
    gap: 12px;
}
.showcase-nav button {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid #c9a34e;
    background: transparent;
    color: #c9a34e;
    cursor: pointer;
}
.showcase-nav button:hover {
    background: #c9a34e;
    color: #fff;
}
.showcase-card {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 25px;
    align-items: center;
}
.showcase-img {
    width: 100%;
    height: 180px;
    object-cover: cover;
    border-radius: 15px;
}
.showcase-tag {
    background: rgba(201, 163, 78, 0.15);
    color: #c9a34e;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
}
.showcase-content h4 {
    font-size: 1.3rem;
    color: #1e2b1e;
    margin: 8px 0;
}
.showcase-content p {
    color: #666;
    font-size: 0.92rem;
    margin-bottom: 15px;
}

/* GALLERY */
.gallery-filters {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 35px;
    flex-wrap: wrap;
}
.gallery-filters button {
    background: #fff;
    border: 1px solid #ddd;
    padding: 8px 20px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    color: #555;
    transition: 0.3s;
}
.gallery-filters button.active, .gallery-filters button:hover {
    background: #c9a34e;
    color: #0f1a12;
    border-color: #c9a34e;
}
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}
.gallery-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    height: 220px;
    cursor: pointer;
}
.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: 0.4s;
}
.gallery-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 26, 18, 0.75);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: 0.3s;
    color: #fff;
    padding: 15px;
    text-align: center;
}
.gallery-item:hover .gallery-overlay { opacity: 1; }
.gallery-item:hover img { transform: scale(1.08); }
.gallery-overlay h4 { color: #c9a34e; font-size: 1.1rem; }
.gallery-overlay span { font-size: 1.4rem; margin-top: 8px; }

/* VIDEOS */
.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}
.video-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
}
.video-thumbnail {
    position: relative;
    height: 190px;
    cursor: pointer;
}
.video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(201, 163, 78, 0.9);
    color: #0f1a12;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: 0.3s;
}
.video-card:hover .play-btn {
    transform: translate(-50%, -50%) scale(1.15);
}
.video-body {
    padding: 15px;
}
.video-body h4 {
    font-size: 0.95rem;
    color: #1e2b1e;
    font-weight: 600;
}

/* FACEBOOK */
.fb-wrapper {
    max-width: 600px;
    margin: 0 auto;
}
.fb-box {
    background: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.06);
    text-align: center;
}
.fb-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 15px;
}
.fb-header h4 {
    font-size: 1.2rem;
    color: #1e2b1e;
    margin: 0;
}
.fb-header p {
    color: #777;
    font-size: 0.85rem;
    margin: 0;
}
.fb-text {
    color: #555;
    margin-bottom: 20px;
    font-size: 0.95rem;
}

/* PARTNERS */
.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
    align-items: center;
}
.partner-card {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    text-align: center;
    border: 1px solid #f0ebe0;
}
.partner-card img {
    height: 55px;
    max-width: 140px;
    object-fit: contain;
    margin: 0 auto 10px;
    filter: grayscale(20%);
    transition: 0.3s;
}
.partner-card:hover img {
    filter: grayscale(0%);
    transform: scale(1.05);
}
.partner-card h5 {
    font-size: 0.85rem;
    color: #333;
    font-weight: 600;
}

/* TEAM */
.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 25px;
}
.team-card {
    background: #fff;
    border-radius: 20px;
    padding: 25px 20px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: 0.3s;
}
.team-card:hover { transform: translateY(-5px); }
.team-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 15px;
    border: 3px solid #c9a34e;
}
.team-card h4 {
    font-size: 1.1rem;
    color: #1e2b1e;
    margin-bottom: 4px;
}
.team-card .role {
    color: #c9a34e;
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 10px;
}
.team-card p {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 15px;
}
.team-social {
    display: flex;
    justify-content: center;
    gap: 10px;
}
.team-social a {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f4efe4;
    color: #1e2b1e;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    transition: 0.2s;
}
.team-social a:hover {
    background: #c9a34e;
    color: #fff;
}

/* EVENTS */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
}
.event-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.event-img {
    position: relative;
    height: 190px;
}
.event-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.event-badge {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: #c9a34e;
    color: #0f1a12;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 700;
}
.event-body {
    padding: 20px;
}
.event-body h4 {
    font-size: 1.15rem;
    color: #1e2b1e;
    margin-bottom: 6px;
}
.event-meta {
    font-size: 0.82rem;
    color: #777;
    margin-bottom: 12px;
}
.event-body p {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 15px;
    line-height: 1.6;
}

/* MEMBERSHIP */
.membership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    max-width: 1050px;
    margin: 0 auto;
}
.membership-card {
    background: #fff;
    border-radius: 20px;
    padding: 35px 25px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    position: relative;
    border: 1px solid #e8e2d5;
    transition: 0.3s;
}
.membership-card.featured {
    border: 2px solid #c9a34e;
    transform: scale(1.03);
}
.plan-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #c9a34e;
    color: #0f1a12;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
}
.plan-icon {
    font-size: 2.2rem;
    color: #c9a34e;
    margin-bottom: 15px;
}
.membership-card h4 {
    font-size: 1.35rem;
    color: #1e2b1e;
    margin-bottom: 10px;
}
.membership-card .price {
    font-size: 2.2rem;
    color: #c9a34e;
    font-weight: 700;
    margin-bottom: 20px;
}
.membership-card .price small {
    font-size: 0.9rem;
    color: #888;
    font-weight: 400;
}
.membership-card ul {
    list-style: none;
    padding: 0;
    margin-bottom: 25px;
    text-align: left;
}
.membership-card li {
    padding: 8px 0;
    color: #555;
    font-size: 0.92rem;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px dashed #f0ebe0;
}
.membership-card li i { color: #2e7d32; }
.membership-btn {
    background: #0f1a12;
    color: #c9a34e;
    padding: 12px 24px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: 0.3s;
    width: 100%;
}
.membership-card.featured .membership-btn {
    background: #c9a34e;
    color: #0f1a12;
}
.membership-btn:hover {
    background: #c9a34e;
    color: #0f1a12;
}

/* BOOKING */
.booking-tabs {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 30px;
}
.booking-tabs button {
    background: #fff;
    border: 2px solid #c9a34e;
    color: #1e2b1e;
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.booking-tabs button.active {
    background: #c9a34e;
    color: #0f1a12;
}
.booking-card-wrap {
    max-width: 750px;
    margin: 0 auto;
    background: #fff;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.06);
    border: 1px solid #e8e2d5;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 6px;
    color: #333;
}
.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-family: inherit;
    font-size: 0.92rem;
    transition: 0.3s;
    background: #faf8f5;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: #c9a34e;
    background: #fff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(201, 163, 78, 0.15);
}
.btn-submit {
    width: 100%;
    background: #c9a34e;
    color: #0f1a12;
    padding: 14px;
    font-size: 1.05rem;
    font-weight: 700;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.btn-submit:hover {
    background: #d8b45f;
}
.booking-success-box {
    text-align: center;
    padding: 30px 20px;
    animation: fadeIn 0.4s ease;
}
.success-icon-wrap {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 50%;
    background: #e8f5e9;
    color: #2e7d32;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 4px 14px rgba(46, 125, 50, 0.2);
}
.booking-success-box h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1b2e20;
    margin-bottom: 10px;
}
.success-msg-desc {
    font-size: 0.95rem;
    color: #4b5563;
    line-height: 1.6;
    max-width: 520px;
    margin: 0 auto 20px;
}
.success-contact-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fdf8ef;
    border: 1px solid #e8dbbe;
    color: #855c1b;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 0.88rem;
    margin-bottom: 25px;
}
.btn-new-booking {
    display: inline-flex;
    align-items: center;
    background: #1b2e20;
    color: #fff;
    padding: 11px 26px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.92rem;
    border: none;
    cursor: pointer;
    transition: 0.25s ease;
}
.btn-new-booking:hover {
    background: #c9a34e;
    color: #0f1a12;
    transform: translateY(-2px);
}

/* DINING TABLES & LIVE QR FOOD SECTION (REQUIREMENTS) */
.food-section-wrap {
    padding: 70px 5%;
}
.tables-qr-container {
    max-width: 1050px;
    margin: 0 auto 50px;
}
.table-chips-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
    justify-content: center;
}
.chips-title {
    font-weight: 700;
    font-size: 0.95rem;
    color: #1e2b1e;
}
.table-chips {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}
.table-chip {
    background: #fff;
    color: #2c2c2c;
    border: 1.5px solid #d4cdbe;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.table-chip:hover {
    border-color: #c9a34e;
    color: #c9a34e;
    transform: translateY(-1px);
}
.table-chip.active {
    background: #0f1a12;
    color: #c9a34e;
    border-color: #c9a34e;
    box-shadow: 0 4px 14px rgba(15,26,18,0.25);
}
.table-chip small {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* MAIN TABLE QR CARD */
.table-qr-card {
    background: linear-gradient(135deg, #0b1c11 0%, #15321f 100%);
    border-radius: 24px;
    padding: 35px 40px;
    color: #fff;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 35px;
    align-items: center;
    box-shadow: 0 12px 35px rgba(0,0,0,0.18);
    border: 1.5px solid #2e4a2e;
    position: relative;
    overflow: hidden;
}
.table-qr-card::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,163,78,0.15) 0%, transparent 70%);
    pointer-events: none;
}
.table-qr-visual {
    display: flex;
    justify-content: center;
    align-items: center;
}
.qr-code-box {
    background: #ffffff;
    padding: 14px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    border: 3px solid #c9a34e;
}
.table-qr-img {
    width: 190px;
    height: 190px;
    display: block;
    border-radius: 10px;
}
.qr-label {
    display: inline-block;
    color: #0f1a12;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-qr-details {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.table-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(34, 197, 94, 0.15);
    color: #4ade80;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    width: fit-content;
    border: 1px solid rgba(34, 197, 94, 0.3);
}
.room-order-heading {
    font-size: 1.85rem;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 10px;
    line-height: 1.35;
    font-family: 'Playfair Display', 'Hind Siliguri', serif;
}
.room-order-desc {
    font-size: 0.98rem;
    color: #d1d5db;
    line-height: 1.6;
    margin: 0 0 24px;
    max-width: 580px;
}

.table-action-btns {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    align-items: center;
}
.btn-room-order {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #ea580c; /* Plain solid orange - no gradient, no golden */
    color: #ffffff !important;
    padding: 13px 26px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 0.96rem;
    text-decoration: none;
    border: none;
    transition: all 0.25s ease;
}
.btn-room-order:hover {
    background: #c2410c;
    color: #ffffff !important;
    transform: translateY(-2px);
}

.btn-table-direct {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #1e3a29; /* Plain solid dark green */
    color: #ffffff !important;
    padding: 13px 24px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.94rem;
    text-decoration: none;
    border: 1px solid #16a34a;
    transition: all 0.25s ease;
}
.btn-table-direct:hover {
    background: #166534;
    color: #ffffff !important;
    transform: translateY(-2px);
}

/* ALL TABLES GRID */
.all-tables-section {
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px dashed #dcd4c5;
}
.all-tables-head {
    font-size: 1.1rem;
    color: #1e2b1e;
    margin-bottom: 18px;
    text-align: center;
    font-weight: 700;
}
.tables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 18px;
}
.table-grid-item {
    background: #fff;
    border: 1.5px solid #e2dac9;
    border-radius: 18px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.table-grid-item:hover {
    border-color: #c9a34e;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.table-grid-item.selected {
    border-color: #c9a34e;
    background: #fbf7ee;
    box-shadow: 0 6px 18px rgba(201,163,78,0.25);
}
.grid-table-top h5 {
    font-size: 1rem;
    color: #0f1a12;
    margin: 0;
    font-weight: 700;
}
.grid-size {
    font-size: 0.75rem;
    color: #777;
}
.grid-qr-wrap {
    background: #fff;
    padding: 8px;
    border-radius: 12px;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.grid-qr-wrap img {
    width: 110px;
    height: 110px;
    display: block;
}
.grid-branch {
    font-size: 0.75rem;
    color: #c9a34e;
    margin: 0;
    font-weight: 600;
}
.grid-order-link {
    display: inline-flex;
    align-items: center;
    background: #0f1a12;
    color: #c9a34e !important;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    text-decoration: none;
    transition: 0.2s;
    margin-top: 4px;
}
.grid-order-link:hover {
    background: #c9a34e;
    color: #0f1a12 !important;
}

@media (max-width: 820px) {
    .table-qr-card {
        grid-template-columns: 1fr;
        padding: 25px 20px;
        text-align: center;
    }
    .table-qr-details {
        align-items: center;
        text-align: center;
    }
    .room-order-heading {
        font-size: 1.45rem;
    }
    .table-action-btns {
        width: 100%;
        flex-direction: column;
    }
    .btn-room-order,
    .btn-table-direct {
        width: 100%;
        justify-content: center;
    }
}

.food-popular-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    max-width: 1200px;
    margin: 0 auto 32px;
    flex-wrap: wrap;
    gap: 20px;
}
.food-popular-titles {
    display: flex;
    flex-direction: column;
}
.food-tagline {
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #936e2d;
    margin-bottom: 6px;
    display: inline-block;
}
.food-popular-head h3.food-main-title {
    font-size: 1.95rem;
    color: #121f15;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.3px;
    line-height: 1.25;
}
.food-popular-sub {
    font-size: 0.92rem;
    color: #617064;
    margin: 6px 0 0;
    max-width: 620px;
    line-height: 1.55;
    font-weight: 400;
}
.see-all-link {
    background: #1b2e20;
    color: #ffffff;
    border: 1px solid #1b2e20;
    padding: 9px 22px;
    border-radius: 24px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.86rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
    box-shadow: none !important;
}
.see-all-link:hover {
    background: #936e2d;
    color: #ffffff;
    border-color: #936e2d;
    box-shadow: none !important;
}
.food-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    max-width: 1200px;
    margin: 0 auto;
}
@media (max-width: 1100px) {
    .food-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
}
@media (max-width: 768px) {
    .food-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
}
@media (max-width: 480px) {
    .food-grid {
        grid-template-columns: 1fr;
    }
}
.food-item {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: none !important;
    border: 1px solid #e7ded0;
    transition: border-color 0.25s ease;
    display: flex;
    flex-direction: column;
    position: relative;
}
.food-item:hover {
    border-color: #c9a34e;
    box-shadow: none !important;
}
.food-thumb-wrap {
    position: relative;
    width: 100%;
    height: 195px;
    overflow: hidden;
    background: #f4ede2;
}
.food-thumb-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
    display: block;
}
.food-item:hover .food-thumb-wrap img {
    transform: scale(1.05);
}
.food-cat-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(18, 31, 21, 0.8);
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 14px;
    letter-spacing: 0.2px;
    z-index: 2;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: none !important;
}
.food-info {
    padding: 16px 18px 18px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
}
.food-rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
    font-size: 0.75rem;
}
.food-rating .rating-label {
    color: #8c734b;
    font-weight: 600;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.food-rating .rating-num {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.74rem;
}
.food-info h4.food-title {
    font-size: 1.05rem;
    color: #152217;
    font-weight: 700;
    margin: 0 0 6px;
    line-height: 1.35;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: color 0.2s;
}
.food-item:hover h4.food-title {
    color: #936e2d;
}
.food-desc {
    font-size: 0.82rem;
    color: #617064;
    line-height: 1.5;
    margin: 0 0 14px;
    min-height: 38px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.food-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f0e6d6;
}
.price-wrap {
    display: flex;
    align-items: baseline;
    gap: 3px;
    color: #152217;
}
.price-wrap .currency {
    font-size: 1rem;
    font-weight: 600;
    color: #936e2d;
}
.price-wrap .amount {
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: -0.3px;
}
.order-mini-btn {
    background: #1b2e20;
    color: #ffffff;
    border: 1px solid #1b2e20;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: 0.2s ease;
    box-shadow: none !important;
}
.order-mini-btn:hover {
    background: #936e2d;
    border-color: #936e2d;
    color: #ffffff;
    box-shadow: none !important;
}

/* Full Menu Catalog Banner */
.food-catalog-banner {
    max-width: 1200px;
    margin: 40px auto 0;
    background: #121f15;
    border: 1px solid rgba(201, 163, 78, 0.25);
    border-radius: 18px;
    padding: 24px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    box-shadow: none !important;
}
.catalog-banner-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.food-catalog-banner h4 {
    font-size: 1.15rem;
    color: #ffffff;
    font-weight: 700;
    margin: 0;
}
.food-catalog-banner p {
    font-size: 0.88rem;
    color: #b5c7b9;
    margin: 0;
    line-height: 1.45;
}
.btn-catalog-cta {
    background: #c9a34e;
    color: #0d1a10;
    border: 1px solid #c9a34e;
    padding: 10px 24px;
    border-radius: 24px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.88rem;
    display: inline-flex;
    align-items: center;
    transition: 0.2s ease;
    box-shadow: none !important;
    white-space: nowrap;
}
.btn-catalog-cta:hover {
    background: #d8b460;
    border-color: #d8b460;
    color: #000000;
    box-shadow: none !important;
}

/* CONTACT & MAP */
.contact-section {
    background: #121f15;
    color: #fff;
    padding: 70px 5%;
}
.contact-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto;
    align-items: center;
}
.contact-info h3 {
    font-size: 1.8rem;
    color: #c9a34e;
    font-family: 'Playfair Display', serif;
    margin-bottom: 20px;
}
.contact-info p {
    color: #d0c8b0;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.contact-info i { color: #c9a34e; }
.contact-social {
    margin-top: 25px;
}
.contact-social h4 {
    color: #c9a34e;
    font-size: 1rem;
    margin-bottom: 10px;
}
.social-icons {
    display: flex;
    gap: 12px;
}
.social-icons a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(201, 163, 78, 0.2);
    color: #c9a34e;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: 0.3s;
}
.social-icons a:hover {
    background: #c9a34e;
    color: #0f1a12;
}
.map-container iframe {
    width: 100%;
    height: 300px;
    border-radius: 18px;
    border: 2px solid #2e4a2e;
}

/* FOOTER */
footer {
    background: #0a140c;
    color: #a09885;
    padding: 50px 5% 25px;
    border-top: 1px solid #1f3322;
}
.footer-wrap {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto 40px;
}
.footer-col h4 {
    color: #c9a34e;
    font-size: 1.15rem;
    margin-bottom: 15px;
    font-family: 'Playfair Display', serif;
}
.footer-col ul {
    list-style: none;
    padding: 0;
}
.footer-col li {
    margin-bottom: 8px;
}
.footer-col a {
    color: #a09885;
    text-decoration: none;
    font-size: 0.88rem;
    transition: 0.2s;
}
.footer-col a:hover { color: #c9a34e; }
.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #1a2a1d;
    font-size: 0.82rem;
    color: #777;
}

/* MODALS */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-video-box {
    position: relative;
    width: 100%;
    max-width: 800px;
    height: 450px;
    background: #000;
    border-radius: 15px;
    overflow: hidden;
}
.modal-video-box iframe {
    width: 100%;
    height: 100%;
    border: none;
}
.modal-image-box {
    position: relative;
    max-width: 900px;
    max-height: 85vh;
}
.modal-image-box img {
    max-width: 100%;
    max-height: 85vh;
    border-radius: 15px;
    object-fit: contain;
}
.modal-close-btn {
    position: absolute;
    top: -40px;
    right: 0;
    color: #fff;
    font-size: 2rem;
    background: transparent;
    border: none;
    cursor: pointer;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .food-cta-banner {
        grid-template-columns: 1fr;
        text-align: center;
        padding: 30px 20px;
    }
    .cta-qr-box {
        flex-direction: column;
        text-align: center;
    }
    .contact-wrap {
        grid-template-columns: 1fr;
    }
    .footer-wrap {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
@media (max-width: 768px) {
    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: rgba(10, 35, 18, 0.98);
        flex-direction: column;
        padding: 20px;
        gap: 15px;
    }
    .nav-links.active {
        display: flex;
    }
    .menu-toggle {
        display: block;
    }
    .slide-content h1 {
        font-size: 1.8rem;
    }
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .showcase-card {
        grid-template-columns: 1fr;
    }
}
</style>
