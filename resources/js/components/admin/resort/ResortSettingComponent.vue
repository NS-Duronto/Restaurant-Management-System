<template>
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>

        <!-- Sidebar / Navigation Tabs -->
        <div class="col-12 md:col-4 xl:col-3">
            <nav class="db-card p-3">
                <button type="button" v-for="tab in tabs" :key="tab.id"
                    @click="setTab(tab.id)"
                    class="db-tab-btn"
                    :class="{ 'active': activeTab === tab.id }">
                    <i :class="tab.icon" class="text-sm"></i>
                    <span class="flex-auto">{{ $t('resort.' + tab.id) }}</span>
                    <span v-if="tab.badge" class="px-1.5 py-0.5 rounded-full text-[10px] bg-red-500 text-white font-bold">
                        {{ tab.badge }}
                    </span>
                </button>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="col-12 md:col-8 xl:col-9">
            <LoadingComponent :props="loading" />

            <!-- TAB 1: GENERAL SETTINGS & NAVBAR -->
            <div v-if="activeTab === 'general'" class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('resort.general_title') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="saveSettings">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ $t('resort.resort_name') }}</label>
                                <input v-model="settingsForm.resort_name" type="text" class="db-field-control" required />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_tagline') }}</label>
                                <input v-model="settingsForm.resort_tagline" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ $t('resort.resort_phone') }}</label>
                                <input v-model="settingsForm.resort_phone" type="text" class="db-field-control" required />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ $t('resort.resort_email') }}</label>
                                <input v-model="settingsForm.resort_email" type="email" class="db-field-control" required />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ $t('resort.resort_address') }}</label>
                                <input v-model="settingsForm.resort_address" type="text" class="db-field-control" required />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_whatsapp') }}</label>
                                <input v-model="settingsForm.resort_whatsapp" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_checkin') }}</label>
                                <input v-model="settingsForm.resort_checkin" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_checkout') }}</label>
                                <input v-model="settingsForm.resort_checkout" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_facebook') }}</label>
                                <input v-model="settingsForm.resort_facebook" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_instagram') }}</label>
                                <input v-model="settingsForm.resort_instagram" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_youtube') }}</label>
                                <input v-model="settingsForm.resort_youtube" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.resort_fb_page_url') }}</label>
                                <input v-model="settingsForm.resort_fb_page_url" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t('resort.resort_map_iframe') }}</label>
                                <textarea v-model="settingsForm.resort_map_iframe" rows="2" class="db-field-control"></textarea>
                            </div>
                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t('resort.resort_food_cta') }}</label>
                                <input v-model="settingsForm.resort_food_cta" type="text" class="db-field-control" />
                            </div>

                            <div class="form-col-12 mt-2">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t('button.save') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: SECTIONS REORDERING & TOGGLE -->
            <div v-if="activeTab === 'sections'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.sections_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.sections_subtitle') }}</p>
                    </div>
                    <button @click="saveSectionOrder" class="db-btn text-white bg-primary text-xs flex items-center gap-1.5">
                        <i class="lab lab-save"></i>
                        <span>{{ $t('resort.save_order') }}</span>
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="space-y-2">
                        <div v-for="(sec, idx) in localSections" :key="sec.id"
                            class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-xs">
                                    {{ idx + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ sec.name }}</h4>
                                    <span class="text-[11px] text-gray-400">#{{ sec.section_key }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveSection(idx, -1)" :disabled="idx === 0"
                                        class="w-7 h-7 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs">
                                        <i class="lab lab-arrow-up"></i>
                                    </button>
                                    <button type="button" @click="moveSection(idx, 1)" :disabled="idx === localSections.length - 1"
                                        class="w-7 h-7 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs">
                                        <i class="lab lab-arrow-down"></i>
                                    </button>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="sec.status" :true-value="5" :false-value="10" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: HERO SLIDER -->
            <div v-if="activeTab === 'slider'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.slider_title') }}</h3>
                    <button @click="openSliderModal()" class="db-btn text-white bg-primary text-xs flex items-center gap-1.5">
                        <i class="lab lab-plus"></i>
                        <span>{{ $t('resort.add_slide') }}</span>
                    </button>
                </div>
                <div class="db-card-body p-0">
                    <div class="db-table-responsive">
                        <table class="db-table stripe">
                            <thead class="db-table-head">
                                <tr class="db-table-head-tr">
                                    <th class="db-table-head-th">{{ $t('resort.image') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.title') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.subtitle') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.button_text') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.status') }}</th>
                                    <th class="db-table-head-th text-right">{{ $t('resort.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="db-table-body">
                                <tr v-for="slide in sliders" :key="slide.id" class="db-table-body-tr">
                                    <td class="db-table-body-td">
                                        <img :src="slide.image" class="w-16 h-10 object-cover rounded-lg border border-gray-200" alt="slide" />
                                    </td>
                                    <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-100">{{ slide.title }}</td>
                                    <td class="db-table-body-td text-gray-500 max-w-xs truncate">{{ slide.subtitle }}</td>
                                    <td class="db-table-body-td text-gray-500">{{ slide.btn_text1 || '—' }}</td>
                                    <td class="db-table-body-td">
                                        <span :class="slide.status === 5 ? 'text-emerald-700 bg-emerald-100' : 'text-red-700 bg-red-100'" class="px-2 py-0.5 rounded-full text-[10px] font-bold">
                                            {{ slide.status === 5 ? $t('resort.active') : $t('resort.inactive') }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td text-right">
                                        <button @click="openSliderModal(slide)" class="db-table-action edit mr-2"><i class="lab lab-edit"></i></button>
                                        <button @click="deleteSlider(slide.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="sliders.length === 0">
                                    <td colspan="6" class="db-table-body-td text-center p-6 text-gray-400">{{ $t('message.no_data_available') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: ABOUT US & SHOWCASE -->
            <div v-if="activeTab === 'about'" class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('resort.about_title') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="saveSettings">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.about_section_title') }}</label>
                                <input v-model="settingsForm.resort_about_title" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title">{{ $t('resort.about_section_sub') }}</label>
                                <input v-model="settingsForm.resort_about_sub" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t('resort.about_head') }}</label>
                                <input v-model="settingsForm.resort_about_head" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t('resort.about_p1') }}</label>
                                <textarea v-model="settingsForm.resort_about_p1" rows="3" class="db-field-control"></textarea>
                            </div>
                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t('resort.about_p2') }}</label>
                                <textarea v-model="settingsForm.resort_about_p2" rows="3" class="db-field-control"></textarea>
                            </div>
                            <div class="form-col-12 sm:form-col-3">
                                <label class="db-field-title">{{ $t('resort.feat1') }}</label>
                                <input v-model="settingsForm.resort_feat1" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-3">
                                <label class="db-field-title">{{ $t('resort.feat2') }}</label>
                                <input v-model="settingsForm.resort_feat2" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-3">
                                <label class="db-field-title">{{ $t('resort.feat3') }}</label>
                                <input v-model="settingsForm.resort_feat3" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 sm:form-col-3">
                                <label class="db-field-title">{{ $t('resort.feat4') }}</label>
                                <input v-model="settingsForm.resort_feat4" type="text" class="db-field-control" />
                            </div>
                            <div class="form-col-12 mt-2">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t('button.save') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Showcase events list -->
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $t('resort.showcase_events') }}</h4>
                            <button @click="openEventModal(null, 1)" class="db-btn text-white bg-primary text-xs py-1.5 px-3">
                                <i class="lab lab-plus"></i> {{ $t('resort.add_showcase_event') }}
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div v-for="ev in aboutEvents" :key="ev.id" class="p-3 rounded-lg border border-gray-200 dark:border-gray-800 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <img :src="ev.image" class="w-12 h-12 object-cover rounded-lg" alt="event" />
                                    <div>
                                        <span class="text-[10px] text-primary font-bold uppercase">{{ ev.tag }}</span>
                                        <h5 class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ ev.title }}</h5>
                                        <p class="text-[11px] text-gray-400 line-clamp-1">{{ ev.description }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <button @click="openEventModal(ev, 1)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                    <button @click="deleteEvent(ev.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: PHOTO & VIDEO GALLERY -->
            <div v-if="activeTab === 'gallery'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.gallery_title') }}</h3>
                    <div class="flex items-center gap-2">
                        <button @click="openGalleryModal('photo')" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                            <i class="lab lab-plus"></i> {{ $t('resort.add_photo') }}
                        </button>
                        <button @click="openGalleryModal('video')" class="db-btn text-white bg-slate-700 hover:bg-slate-800 text-xs py-1.5 px-3 flex items-center gap-1">
                            <i class="lab lab-video"></i> {{ $t('resort.add_video') }}
                        </button>
                    </div>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <div v-for="item in galleries" :key="item.id" class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden group relative">
                            <img :src="item.image" class="w-full h-32 object-cover" alt="gallery" />
                            <div class="p-2.5 bg-white dark:bg-gray-900">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ item.category }} ({{ item.type }})</span>
                                <h5 class="text-xs font-semibold text-gray-800 dark:text-gray-100 truncate">{{ item.title || '—' }}</h5>
                            </div>
                            <div class="absolute top-2 right-2 flex gap-1 opacity-90 group-hover:opacity-100 transition">
                                <button @click="openGalleryModal(item.type, item)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                <button @click="deleteGallery(item.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 6: TEAMS -->
            <div v-if="activeTab === 'team'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.team_title') }}</h3>
                    <button @click="openTeamModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                        <i class="lab lab-plus"></i> {{ $t('resort.add_team_member') }}
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="tm in teams" :key="tm.id" class="rounded-lg border border-gray-200 dark:border-gray-800 p-3 text-center relative group">
                            <img :src="tm.image" class="w-20 h-20 rounded-full object-cover mx-auto mb-2 border-2 border-primary/20" alt="team" />
                            <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ tm.name }}</h4>
                            <span class="text-[11px] text-primary block">{{ tm.designation }}</span>
                            <p class="text-[10px] text-gray-400 mt-1 line-clamp-2">{{ tm.bio }}</p>
                            <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-800 flex justify-center gap-2 text-xs text-gray-400">
                                <a v-if="tm.facebook" :href="tm.facebook" target="_blank"><i class="lab lab-facebook"></i></a>
                                <a v-if="tm.linkedin" :href="tm.linkedin" target="_blank"><i class="lab lab-linkedin"></i></a>
                                <a v-if="tm.instagram" :href="tm.instagram" target="_blank"><i class="lab lab-instagram"></i></a>
                            </div>
                            <div class="absolute top-2 right-2 flex gap-1">
                                <button @click="openTeamModal(tm)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                <button @click="deleteTeam(tm.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 7: MEMBERSHIP PLANS -->
            <div v-if="activeTab === 'plans'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.plans_title') }}</h3>
                    <button @click="openPlanModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                        <i class="lab lab-plus"></i> {{ $t('resort.add_plan') }}
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-for="plan in plans" :key="plan.id" class="rounded-xl border p-4 relative" :class="plan.is_featured ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-800'">
                            <span v-if="plan.badge" class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary text-white">{{ plan.badge }}</span>
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ plan.title }}</h4>
                            <div class="text-xl font-extrabold text-primary mt-2">৳{{ plan.price }} <small class="text-xs font-normal text-gray-400">/{{ plan.duration || $t('resort.year') }}</small></div>
                            <ul class="mt-3 space-y-1.5 text-xs text-gray-600 dark:text-gray-300">
                                <li v-for="(f, i) in parsedFeatures(plan.features)" :key="i" class="flex items-center gap-1.5">
                                    <i class="lab lab-check text-emerald-600 text-[10px]"></i> {{ f }}
                                </li>
                            </ul>
                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs">
                                <button @click="openPlanModal(plan)" class="text-primary hover:underline font-semibold">{{ $t('resort.edit') }}</button>
                                <button @click="deletePlan(plan.id)" class="text-red-500 hover:underline">{{ $t('resort.delete') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 8: PARTNERS -->
            <div v-if="activeTab === 'partners'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.partners_title') }}</h3>
                    <button @click="openPartnerModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                        <i class="lab lab-plus"></i> {{ $t('resort.add_partner') }}
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div v-for="part in partners" :key="part.id" class="p-3 rounded-lg border border-gray-200 dark:border-gray-800 text-center relative group">
                            <img :src="part.image" class="h-12 object-contain mx-auto mb-2" alt="partner" />
                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate">{{ part.name }}</h5>
                            <a v-if="part.website_url" :href="part.website_url" target="_blank" class="text-[10px] text-primary hover:underline truncate block">{{ $t('resort.website_url') }}</a>
                            <div class="absolute top-2 right-2 flex gap-1">
                                <button @click="openPartnerModal(part)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                <button @click="deletePartner(part.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 9: BOOKINGS REQUESTS -->
            <div v-if="activeTab === 'bookings'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.bookings_title') }}</h3>
                </div>
                <div class="db-card-body p-0">
                    <div class="db-table-responsive">
                        <table class="db-table stripe">
                            <thead class="db-table-head">
                                <tr class="db-table-head-tr">
                                    <th class="db-table-head-th">{{ $t('resort.type') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.customer') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.date_time') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.room_event') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.guest') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.notes') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.status') }}</th>
                                    <th class="db-table-head-th text-right">{{ $t('resort.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="db-table-body">
                                <tr v-for="b in bookings" :key="b.id" class="db-table-body-tr">
                                    <td class="db-table-body-td">
                                        <span :class="b.type === 'resort' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-purple-100 text-purple-800 border border-purple-300'" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider inline-flex items-center gap-1">
                                            <i :class="b.type === 'resort' ? 'fas fa-bed' : 'fas fa-glass-cheers'"></i>
                                            {{ b.type === 'party' ? 'পার্টি বুকিং' : 'রিসোর্ট বুকিং' }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-100">
                                        {{ b.name }}
                                        <span class="block text-[11px] text-gray-400 font-normal">{{ b.phone }}</span>
                                    </td>
                                    <td class="db-table-body-td text-gray-600">{{ b.booking_date || b.check_in || '—' }}</td>
                                    <td class="db-table-body-td text-gray-600 font-medium">
                                        <span v-if="b.type === 'party'" class="text-purple-700 dark:text-purple-400">
                                            <i class="fas fa-calendar-check text-xs mr-1"></i>{{ b.event_type || '—' }}
                                        </span>
                                        <span v-else class="text-emerald-700 dark:text-emerald-400">
                                            <i class="fas fa-hotel text-xs mr-1"></i>{{ b.room_type || '—' }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td text-gray-600">{{ b.guests_count }}</td>
                                    <td class="db-table-body-td text-gray-500 max-w-xs truncate">{{ b.notes || '—' }}</td>
                                    <td class="db-table-body-td">
                                        <select v-model="b.status" @change="updateBookingStatus(b)" class="text-xs rounded border border-gray-300 dark:border-gray-700 p-1 bg-white dark:bg-gray-800">
                                            <option :value="5">{{ $t('resort.pending') }}</option>
                                            <option :value="10">{{ $t('resort.confirmed') }}</option>
                                            <option :value="15">{{ $t('resort.cancelled') }}</option>
                                        </select>
                                    </td>
                                    <td class="db-table-body-td text-right">
                                        <button @click="deleteBooking(b.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="bookings.length === 0">
                                    <td colspan="8" class="db-table-body-td text-center p-6 text-gray-400">{{ $t('resort.no_bookings') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 10: WEBSITE ORDERS -->
            <div v-if="activeTab === 'orders'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <h3 class="db-card-title">{{ $t('resort.orders_title') }}</h3>
                    <button type="button" @click="$store.dispatch('resort/getWebsiteOrders')" class="db-btn-outline text-xs flex items-center gap-1.5 py-1 px-3">
                        <i class="lab lab-refresh text-xs"></i>
                        <span>{{ $t('button.refresh') || 'Refresh' }}</span>
                    </button>
                </div>
                <div class="db-card-body p-0">
                    <div class="db-table-responsive">
                        <table class="db-table stripe">
                            <thead class="db-table-head">
                                <tr class="db-table-head-tr">
                                    <th class="db-table-head-th">{{ $t('resort.order_id') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.customer_room') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.ordered_items') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.date_time') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.total') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.payment') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.order_status') }}</th>
                                    <th class="db-table-head-th text-right">{{ $t('resort.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="db-table-body">
                                <tr v-for="ord in websiteOrders" :key="ord.id" class="db-table-body-tr">
                                    <td class="db-table-body-td font-bold text-primary">#{{ ord.order_serial_no || ord.id }}</td>
                                    <td class="db-table-body-td">
                                        <div class="flex items-center gap-1.5 font-bold text-gray-800 dark:text-gray-100">
                                            <i class="fas fa-user-circle text-orange-500 text-xs"></i>
                                            <span>{{ ord.customer_name || (ord.user ? ord.user.name : 'Guest') }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            <i class="fas fa-phone-alt text-[9px] mr-1 text-gray-400"></i>{{ ord.customer_phone || (ord.user ? ord.user.phone : '-') }}
                                        </div>
                                        <div class="mt-1 flex items-center gap-1">
                                            <span v-if="ord.room_number" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fas fa-door-open"></i> {{ $t('resort.room_label') }}: {{ ord.room_number }}
                                            </span>
                                            <span v-if="ord.dining_table" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fas fa-chair"></i> {{ ord.dining_table.name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="db-table-body-td text-xs text-gray-600 max-w-[180px]">
                                        <div v-for="item in (ord.order_items || []).slice(0, 3)" :key="item.id" class="truncate text-[11px] leading-tight mb-1">
                                            • {{ item.item_name }} × {{ item.quantity }}
                                        </div>
                                        <span v-if="(ord.order_items || []).length > 3" class="text-[10px] text-orange-500 font-semibold block">
                                            +{{ ord.order_items.length - 3 }} more...
                                        </span>
                                    </td>
                                    <td class="db-table-body-td text-gray-600 text-xs">{{ ord.order_datetime }}</td>
                                    <td class="db-table-body-td font-bold text-gray-800 dark:text-gray-100">৳{{ ord.total }}</td>
                                    <td class="db-table-body-td">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="ord.payment_status === 5 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                                            {{ ord.payment_status === 5 ? $t('resort.paid') : $t('resort.unpaid') }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold whitespace-nowrap inline-flex items-center gap-1"
                                            :class="orderStatusBadgeClass(ord.status)">
                                            {{ getStatusText(ord) }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td text-right">
                                        <router-link :to="{ name: 'admin.table.order.show', params: { id: ord.id } }"
                                            class="db-table-action view" :title="$t('label.view') || 'View Order'">
                                            <i class="lab lab-view"></i>
                                        </router-link>
                                    </td>
                                </tr>
                                <tr v-if="websiteOrders.length === 0">
                                    <td colspan="8" class="db-table-body-td text-center p-6 text-gray-400">{{ $t('resort.no_orders') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: SLIDER -->
    <div v-if="sliderModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button @click="sliderModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ sliderForm.id ? $t('resort.edit_slide') : $t('resort.new_slide') }}</h3>
            <form @submit.prevent="submitSlider">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.title') }}</label>
                        <input v-model="sliderForm.title" type="text" class="db-field-control" required />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.subtitle') }}</label>
                        <textarea v-model="sliderForm.subtitle" rows="2" class="db-field-control"></textarea>
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.file_upload') }}</label>
                        <input type="file" @change="onSliderFileChange" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.or_image_url') }}</label>
                        <input v-model="sliderForm.image_url" type="text" class="db-field-control" placeholder="https://..." />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="db-field-title">{{ $t('resort.button_text') }}</label>
                            <input v-model="sliderForm.btn_text1" type="text" class="db-field-control" />
                        </div>
                        <div>
                            <label class="db-field-title">{{ $t('resort.button_link') }}</label>
                            <input v-model="sliderForm.btn_link1" type="text" class="db-field-control" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="sliderModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: GALLERY -->
    <div v-if="galleryModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="galleryModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ galleryForm.id ? $t('resort.edit_gallery') : $t('resort.new_gallery') }}</h3>
            <form @submit.prevent="submitGallery">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title">{{ $t('resort.title') }}</label>
                        <input v-model="galleryForm.title" type="text" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.category') }}</label>
                        <select v-model="galleryForm.category" class="db-field-control">
                            <option value="resort">Resort</option>
                            <option value="rooms">Rooms</option>
                            <option value="nature">Nature</option>
                            <option value="events">Events</option>
                        </select>
                    </div>
                    <div v-if="galleryForm.type === 'photo'">
                        <label class="db-field-title">{{ $t('resort.file_upload') }}</label>
                        <input type="file" @change="onGalleryFileChange" class="db-field-control" />
                        <label class="db-field-title mt-2">{{ $t('resort.or_image_url') }}</label>
                        <input v-model="galleryForm.image_url" type="text" class="db-field-control" />
                    </div>
                    <div v-else>
                        <label class="db-field-title">Video Embed URL (YouTube/Vimeo)</label>
                        <input v-model="galleryForm.video_url" type="text" class="db-field-control" placeholder="https://..." />
                        <label class="db-field-title mt-2">Video Cover Image URL</label>
                        <input v-model="galleryForm.image_url" type="text" class="db-field-control" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="galleryModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: TEAM -->
    <div v-if="teamModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="teamModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ teamForm.id ? $t('resort.edit_team') : $t('resort.new_team') }}</h3>
            <form @submit.prevent="submitTeam">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.name') }}</label>
                        <input v-model="teamForm.name" type="text" class="db-field-control" required />
                    </div>
                    <div>
                        <label class="db-field-title required">{{ $t('resort.designation') }}</label>
                        <input v-model="teamForm.designation" type="text" class="db-field-control" required />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.bio') }}</label>
                        <textarea v-model="teamForm.bio" rows="2" class="db-field-control"></textarea>
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.file_upload') }}</label>
                        <input type="file" @change="onTeamFileChange" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.or_image_url') }}</label>
                        <input v-model="teamForm.image_url" type="text" class="db-field-control" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="db-field-title">Facebook URL</label>
                            <input v-model="teamForm.facebook" type="text" class="db-field-control" />
                        </div>
                        <div>
                            <label class="db-field-title">LinkedIn URL</label>
                            <input v-model="teamForm.linkedin" type="text" class="db-field-control" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="teamModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: PLAN -->
    <div v-if="planModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="planModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ planForm.id ? $t('resort.edit_plan') : $t('resort.new_plan') }}</h3>
            <form @submit.prevent="submitPlan">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.title') }}</label>
                        <input v-model="planForm.title" type="text" class="db-field-control" required />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="db-field-title required">{{ $t('resort.price') }} (৳)</label>
                            <input v-model.number="planForm.price" type="number" class="db-field-control" required />
                        </div>
                        <div>
                            <label class="db-field-title">{{ $t('resort.duration') }}</label>
                            <input v-model="planForm.duration" type="text" class="db-field-control" placeholder="Year / Month" />
                        </div>
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.badge') }}</label>
                        <input v-model="planForm.badge" type="text" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.features') }}</label>
                        <textarea v-model="planForm.featuresText" rows="4" class="db-field-control" placeholder="10% Discount on Rooms&#10;Free Breakfast&#10;VIP Pool Access"></textarea>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="featured" v-model="planForm.is_featured" :true-value="1" :false-value="0" />
                        <label for="featured">{{ $t('resort.featured_plan') }}</label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="planModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: PARTNER -->
    <div v-if="partnerModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="partnerModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ partnerForm.id ? $t('resort.edit_partner') : $t('resort.new_partner') }}</h3>
            <form @submit.prevent="submitPartner">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.name') }}</label>
                        <input v-model="partnerForm.name" type="text" class="db-field-control" required />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.website_url') }}</label>
                        <input v-model="partnerForm.website_url" type="text" class="db-field-control" placeholder="https://..." />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.file_upload') }}</label>
                        <input type="file" @change="onPartnerFileChange" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.or_image_url') }}</label>
                        <input v-model="partnerForm.image_url" type="text" class="db-field-control" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="partnerModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EVENT -->
    <div v-if="eventModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button @click="eventModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ eventForm.id ? $t('resort.edit_event') : $t('resort.new_event') }}</h3>
            <form @submit.prevent="submitEvent">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.title') }}</label>
                        <input v-model="eventForm.title" type="text" class="db-field-control" required />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.tag_badge') }}</label>
                        <input v-model="eventForm.tag" type="text" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.description') }}</label>
                        <textarea v-model="eventForm.description" rows="3" class="db-field-control"></textarea>
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.file_upload') }}</label>
                        <input type="file" @change="onEventFileChange" class="db-field-control" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.or_image_url') }}</label>
                        <input v-model="eventForm.image_url" type="text" class="db-field-control" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="eventModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import alertService from "../../../services/alertService";

export default {
    name: "ResortSettingComponent",
    components: { BreadcrumbComponent, LoadingComponent },
    data() {
        return {
            loading: { isActive: false },
            activeTab: "general",
            localSections: [],
            tabs: [
                { id: "general",  icon: "lab lab-sliders" },
                { id: "sections", icon: "lab lab-list" },
                { id: "slider",   icon: "lab lab-image" },
                { id: "about",    icon: "lab lab-info" },
                { id: "gallery",  icon: "lab lab-variation" },
                { id: "team",     icon: "lab lab-customers" },
                { id: "plans",    icon: "lab lab-pos-bold" },
                { id: "partners", icon: "lab lab-company" },
                { id: "bookings", icon: "lab lab-time-slots" },
                { id: "orders",   icon: "lab lab-online-orders" },
            ],
            settingsForm: {
                resort_name: "",
                resort_tagline: "",
                resort_phone: "",
                resort_email: "",
                resort_address: "",
                resort_whatsapp: "",
                resort_checkin: "",
                resort_checkout: "",
                resort_facebook: "",
                resort_instagram: "",
                resort_youtube: "",
                resort_fb_page_url: "",
                resort_map_iframe: "",
                resort_about_title: "",
                resort_about_sub: "",
                resort_about_head: "",
                resort_about_p1: "",
                resort_about_p2: "",
                resort_feat1: "",
                resort_feat2: "",
                resort_feat3: "",
                resort_feat4: "",
                resort_food_cta: "",
            },
            sliderModal: false,
            sliderForm: { id: null, title: "", subtitle: "", image_url: "", btn_text1: "", btn_link1: "", file: null },
            galleryModal: false,
            galleryForm: { id: null, title: "", type: "photo", category: "resort", image_url: "", video_url: "", file: null },
            teamModal: false,
            teamForm: { id: null, name: "", designation: "", bio: "", image_url: "", facebook: "", linkedin: "", file: null },
            planModal: false,
            planForm: { id: null, title: "", price: 0, duration: "", badge: "", featuresText: "", is_featured: 0 },
            partnerModal: false,
            partnerForm: { id: null, name: "", website_url: "", image_url: "", file: null },
            eventModal: false,
            eventForm: { id: null, title: "", tag: "", description: "", image_url: "", is_about_slider: 0, file: null },
        };
    },
    computed: {
        sliders() { return this.$store.getters['resort/sliders'] || []; },
        galleries() { return this.$store.getters['resort/galleries'] || []; },
        events() { return this.$store.getters['resort/events'] || []; },
        aboutEvents() { return (this.$store.getters['resort/events'] || []).filter(e => e.is_about_slider === 1); },
        teams() { return this.$store.getters['resort/teams'] || []; },
        plans() { return this.$store.getters['resort/plans'] || []; },
        partners() { return this.$store.getters['resort/partners'] || []; },
        bookings() { return this.$store.getters['resort/bookings'] || []; },
        websiteOrders() { return this.$store.getters['resort/websiteOrders'] || []; },
    },
    watch: {
        '$route.query.tab'(newTab) {
            if (newTab && this.tabs.some(t => t.id === newTab) && this.activeTab !== newTab) {
                this.activeTab = newTab;
            }
        }
    },
    mounted() {
        this.initActiveTab();
        this.loadAllData();
    },
    methods: {
        initActiveTab() {
            const queryTab = this.$route.query.tab;
            const savedTab = localStorage.getItem('resort_admin_active_tab');
            const validTabs = this.tabs.map(t => t.id);

            if (queryTab && validTabs.includes(queryTab)) {
                this.activeTab = queryTab;
                localStorage.setItem('resort_admin_active_tab', queryTab);
            } else if (savedTab && validTabs.includes(savedTab)) {
                this.activeTab = savedTab;
                this.syncTabToUrl(savedTab);
            }
        },
        setTab(tabId) {
            this.activeTab = tabId;
            localStorage.setItem('resort_admin_active_tab', tabId);
            this.syncTabToUrl(tabId);
            if (tabId === 'orders') {
                this.$store.dispatch('resort/getWebsiteOrders');
            } else if (tabId === 'bookings') {
                this.$store.dispatch('resort/getBookings');
            }
        },
        syncTabToUrl(tabId) {
            if (this.$route.query.tab !== tabId) {
                this.$router.replace({
                    query: { ...this.$route.query, tab: tabId }
                }).catch(() => {});
            }
        },
        loadAllData() {
            this.loading.isActive = true;
            Promise.all([
                this.$store.dispatch('resort/getSettings'),
                this.$store.dispatch('resort/getSections'),
                this.$store.dispatch('resort/getSliders'),
                this.$store.dispatch('resort/getGalleries'),
                this.$store.dispatch('resort/getEvents'),
                this.$store.dispatch('resort/getTeams'),
                this.$store.dispatch('resort/getPlans'),
                this.$store.dispatch('resort/getPartners'),
                this.$store.dispatch('resort/getBookings'),
                this.$store.dispatch('resort/getWebsiteOrders'),
            ]).then(([settingsRes, sectionsRes]) => {
                if (settingsRes && settingsRes.data && settingsRes.data.data) {
                    this.settingsForm = { ...this.settingsForm, ...settingsRes.data.data };
                }
                if (sectionsRes && sectionsRes.data && sectionsRes.data.data) {
                    this.localSections = JSON.parse(JSON.stringify(sectionsRes.data.data));
                }
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        saveSettings() {
            this.loading.isActive = true;
            this.$store.dispatch('resort/updateSettings', this.settingsForm).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => {
                alertService.error(err.response?.data?.message || 'Error saving settings');
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        moveSection(idx, direction) {
            const target = idx + direction;
            if (target < 0 || target >= this.localSections.length) return;
            const temp = this.localSections[idx];
            this.localSections[idx] = this.localSections[target];
            this.localSections[target] = temp;
        },
        saveSectionOrder() {
            this.loading.isActive = true;
            this.$store.dispatch('resort/updateSectionsOrder', this.localSections).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            }).catch(() => {
                alertService.error('Error saving order');
            }).finally(() => {
                this.loading.isActive = false;
            });
        },

        // SLIDER
        openSliderModal(slide = null) {
            if (slide) {
                this.sliderForm = { ...slide, file: null };
                this.$store.commit('resort/temp', { temp_id: slide.id, isEditing: true });
            } else {
                this.sliderForm = { id: null, title: "", subtitle: "", image_url: "", btn_text1: "Book Now", btn_link1: "#booking", file: null };
                this.$store.commit('resort/reset');
            }
            this.sliderModal = true;
        },
        onSliderFileChange(e) {
            this.sliderForm.file = e.target.files[0];
        },
        submitSlider() {
            const formData = new FormData();
            formData.append('title', this.sliderForm.title);
            if (this.sliderForm.subtitle) formData.append('subtitle', this.sliderForm.subtitle);
            if (this.sliderForm.image_url) formData.append('image_url', this.sliderForm.image_url);
            if (this.sliderForm.btn_text1) formData.append('btn_text1', this.sliderForm.btn_text1);
            if (this.sliderForm.btn_link1) formData.append('btn_link1', this.sliderForm.btn_link1);
            if (this.sliderForm.file) formData.append('image', this.sliderForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveSlider', formData).then(() => {
                this.sliderModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => {
                alertService.error(err.response?.data?.message || 'Error saving slider');
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        deleteSlider(id) {
            if (!confirm('Are you sure you want to delete this slider?')) return;
            this.$store.dispatch('resort/destroySlider', id).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            });
        },

        // GALLERY
        openGalleryModal(type = 'photo', item = null) {
            if (item) {
                this.galleryForm = { ...item, file: null };
                this.$store.commit('resort/temp', { temp_id: item.id, isEditing: true });
            } else {
                this.galleryForm = { id: null, title: "", type: type, category: "resort", image_url: "", video_url: "", file: null };
                this.$store.commit('resort/reset');
            }
            this.galleryModal = true;
        },
        onGalleryFileChange(e) {
            this.galleryForm.file = e.target.files[0];
        },
        submitGallery() {
            const formData = new FormData();
            formData.append('type', this.galleryForm.type);
            if (this.galleryForm.title) formData.append('title', this.galleryForm.title);
            if (this.galleryForm.category) formData.append('category', this.galleryForm.category);
            if (this.galleryForm.image_url) formData.append('image_url', this.galleryForm.image_url);
            if (this.galleryForm.video_url) formData.append('video_url', this.galleryForm.video_url);
            if (this.galleryForm.file) formData.append('image', this.galleryForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveGallery', formData).then(() => {
                this.galleryModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving gallery'))
            .finally(() => this.loading.isActive = false);
        },
        deleteGallery(id) {
            if (!confirm('Are you sure you want to delete this?')) return;
            this.$store.dispatch('resort/destroyGallery', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // TEAMS
        openTeamModal(tm = null) {
            if (tm) {
                this.teamForm = { ...tm, file: null };
                this.$store.commit('resort/temp', { temp_id: tm.id, isEditing: true });
            } else {
                this.teamForm = { id: null, name: "", designation: "", bio: "", image_url: "", facebook: "", linkedin: "", file: null };
                this.$store.commit('resort/reset');
            }
            this.teamModal = true;
        },
        onTeamFileChange(e) {
            this.teamForm.file = e.target.files[0];
        },
        submitTeam() {
            const formData = new FormData();
            formData.append('name', this.teamForm.name);
            formData.append('designation', this.teamForm.designation);
            if (this.teamForm.bio) formData.append('bio', this.teamForm.bio);
            if (this.teamForm.image_url) formData.append('image_url', this.teamForm.image_url);
            if (this.teamForm.facebook) formData.append('facebook', this.teamForm.facebook);
            if (this.teamForm.linkedin) formData.append('linkedin', this.teamForm.linkedin);
            if (this.teamForm.file) formData.append('image', this.teamForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveTeam', formData).then(() => {
                this.teamModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving team member'))
            .finally(() => this.loading.isActive = false);
        },
        deleteTeam(id) {
            if (!confirm('Are you sure you want to delete this?')) return;
            this.$store.dispatch('resort/destroyTeam', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // PLANS
        openPlanModal(plan = null) {
            if (plan) {
                const feats = this.parsedFeatures(plan.features);
                this.planForm = { ...plan, featuresText: feats.join("\n") };
                this.$store.commit('resort/temp', { temp_id: plan.id, isEditing: true });
            } else {
                this.planForm = { id: null, title: "", price: 5000, duration: "Year", badge: "", featuresText: "", is_featured: 0 };
                this.$store.commit('resort/reset');
            }
            this.planModal = true;
        },
        parsedFeatures(feat) {
            if (!feat) return [];
            if (Array.isArray(feat)) return feat;
            try { return JSON.parse(feat); } catch (e) { return feat.split("\n").filter(f => f.trim()); }
        },
        submitPlan() {
            const features = this.planForm.featuresText.split("\n").map(f => f.trim()).filter(f => f);
            const payload = {
                title: this.planForm.title,
                price: this.planForm.price,
                duration: this.planForm.duration,
                badge: this.planForm.badge,
                is_featured: this.planForm.is_featured,
                features: features,
            };
            this.loading.isActive = true;
            this.$store.dispatch('resort/savePlan', payload).then(() => {
                this.planModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving plan'))
            .finally(() => this.loading.isActive = false);
        },
        deletePlan(id) {
            if (!confirm('Are you sure you want to delete this?')) return;
            this.$store.dispatch('resort/destroyPlan', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // PARTNERS
        openPartnerModal(part = null) {
            if (part) {
                this.partnerForm = { ...part, file: null };
                this.$store.commit('resort/temp', { temp_id: part.id, isEditing: true });
            } else {
                this.partnerForm = { id: null, name: "", website_url: "", image_url: "", file: null };
                this.$store.commit('resort/reset');
            }
            this.partnerModal = true;
        },
        onPartnerFileChange(e) {
            this.partnerForm.file = e.target.files[0];
        },
        submitPartner() {
            const formData = new FormData();
            formData.append('name', this.partnerForm.name);
            if (this.partnerForm.website_url) formData.append('website_url', this.partnerForm.website_url);
            if (this.partnerForm.image_url) formData.append('image_url', this.partnerForm.image_url);
            if (this.partnerForm.file) formData.append('image', this.partnerForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/savePartner', formData).then(() => {
                this.partnerModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving partner'))
            .finally(() => this.loading.isActive = false);
        },
        deletePartner(id) {
            if (!confirm('Are you sure you want to delete this?')) return;
            this.$store.dispatch('resort/destroyPartner', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // EVENTS
        openEventModal(ev = null, isAbout = 0) {
            if (ev) {
                this.eventForm = { ...ev, file: null };
                this.$store.commit('resort/temp', { temp_id: ev.id, isEditing: true });
            } else {
                this.eventForm = { id: null, title: "", tag: "", description: "", image_url: "", is_about_slider: isAbout, file: null };
                this.$store.commit('resort/reset');
            }
            this.eventModal = true;
        },
        onEventFileChange(e) {
            this.eventForm.file = e.target.files[0];
        },
        submitEvent() {
            const formData = new FormData();
            formData.append('title', this.eventForm.title);
            if (this.eventForm.tag) formData.append('tag', this.eventForm.tag);
            if (this.eventForm.description) formData.append('description', this.eventForm.description);
            if (this.eventForm.image_url) formData.append('image_url', this.eventForm.image_url);
            formData.append('is_about_slider', this.eventForm.is_about_slider);
            if (this.eventForm.file) formData.append('image', this.eventForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveEvent', formData).then(() => {
                this.eventModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving event'))
            .finally(() => this.loading.isActive = false);
        },
        deleteEvent(id) {
            if (!confirm('Are you sure you want to delete this?')) return;
            this.$store.dispatch('resort/destroyEvent', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // BOOKINGS
        updateBookingStatus(b) {
            this.$store.dispatch('resort/updateBookingStatus', { id: b.id, status: b.status }).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            });
        },
        deleteBooking(id) {
            if (!confirm('Are you sure you want to delete this booking request?')) return;
            this.$store.dispatch('resort/destroyBooking', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // WEBSITE ORDERS
        getStatusText(ord) {
            const map = {
                1: this.$t('label.pending') || 'Pending',
                4: this.$t('label.accept') || 'Accept',
                7: this.$t('label.preparing') || 'Preparing',
                8: this.$t('label.prepared') || 'Prepared',
                10: this.$t('label.out_for_delivery') || 'Out for Delivery',
                13: this.$t('label.delivered') || 'Delivered',
                16: this.$t('label.canceled') || 'Canceled',
                19: this.$t('label.rejected') || 'Rejected',
            };
            return map[ord.status] || ord.status_name || 'Processing';
        },
        orderStatusBadgeClass(status) {
            if (status === 1) return 'bg-amber-100 text-amber-800 border border-amber-300';
            if (status === 4 || status === 7) return 'bg-blue-100 text-blue-800 border border-blue-300';
            if (status === 8) return 'bg-purple-100 text-purple-800 border border-purple-300';
            if (status === 13) return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
            if (status === 16 || status === 19) return 'bg-red-100 text-red-800 border border-red-300';
            return 'bg-gray-100 text-gray-800';
        },
    },
};
</script>
