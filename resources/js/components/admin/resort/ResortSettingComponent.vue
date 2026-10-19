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
            <!-- TAB 3: HERO SLIDER -->
            <div v-if="activeTab === 'slider'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.slider_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.slider_reorder_help') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="saveSliderOrder" class="db-btn text-white bg-slate-800 hover:bg-slate-900 text-xs flex items-center gap-1.5">
                            <i class="lab lab-save"></i>
                            <span>{{ $t('resort.save_order') }}</span>
                        </button>
                        <button @click="openSliderModal()" class="db-btn text-white bg-primary text-xs flex items-center gap-1.5">
                            <i class="lab lab-plus"></i>
                            <span>{{ $t('resort.add_slide') }}</span>
                        </button>
                    </div>
                </div>
                <div class="db-card-body p-0">
                    <div class="db-table-responsive">
                        <table class="db-table stripe">
                            <thead class="db-table-head">
                                <tr class="db-table-head-tr">
                                    <th class="db-table-head-th">{{ $t('resort.order') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.image') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.title') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.subtitle') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.button_text') }}</th>
                                    <th class="db-table-head-th">{{ $t('resort.status') }}</th>
                                    <th class="db-table-head-th text-right">{{ $t('resort.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="db-table-body">
                                <tr v-for="(slide, idx) in localSliders" :key="slide.id" class="db-table-body-tr">
                                    <td class="db-table-body-td">
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="moveSlider(idx, -1)" :disabled="idx === 0"
                                                class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_up')">
                                                <i class="lab lab-arrow-up"></i>
                                            </button>
                                            <button type="button" @click="moveSlider(idx, 1)" :disabled="idx === localSliders.length - 1"
                                                class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_down')">
                                                <i class="lab lab-arrow-down"></i>
                                            </button>
                                            <span class="text-xs font-bold text-gray-500 ml-1">#{{ idx + 1 }}</span>
                                        </div>
                                    </td>
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
                                <tr v-if="localSliders.length === 0">
                                    <td colspan="7" class="db-table-body-td text-center p-6 text-gray-400">{{ $t('message.no_data_available') }}</td>
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

            <!-- TAB 5: PHOTO GALLERY (Note 16 & Note 17) -->
            <div v-if="activeTab === 'gallery'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.photo_gallery_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.photo_gallery_sub') }}</p>
                    </div>
                    <button @click="openPhotoModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                        <i class="lab lab-plus"></i> {{ $t('resort.add_photo') }}
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <div v-for="item in photoGalleries" :key="item.id" class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden group relative">
                            <img :src="item.image" class="w-full h-32 object-cover" alt="gallery" />
                            <div class="p-2.5 bg-white dark:bg-gray-900">
                                <span class="text-[10px] font-bold text-primary uppercase">{{ item.category }}</span>
                                <h5 class="text-xs font-semibold text-gray-800 dark:text-gray-100 truncate">{{ item.title || '—' }}</h5>
                            </div>
                            <div class="absolute top-2 right-2 flex gap-1 opacity-90 group-hover:opacity-100 transition">
                                <button @click="openPhotoModal(item)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                <button @click="deleteGallery(item.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                            </div>
                        </div>
                        <div v-if="photoGalleries.length === 0" class="col-span-full text-center p-8 text-gray-400">
                            {{ $t('resort.no_photos') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 6: VIDEO GALLERY (Note 17) -->
            <div v-if="activeTab === 'videos'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.video_gallery_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.video_gallery_sub') }}</p>
                    </div>
                    <button @click="openVideoModal()" class="db-btn text-white bg-red-600 hover:bg-red-700 text-xs py-1.5 px-3 flex items-center gap-1">
                        <i class="fa-solid fa-video"></i> {{ $t('resort.add_video') }}
                    </button>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div v-for="item in videoGalleries" :key="item.id" class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden group relative">
                            <div class="relative h-36 bg-black">
                                <img :src="item.image" class="w-full h-full object-cover opacity-85" alt="video cover" />
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-10 h-10 rounded-full bg-red-600/90 text-white flex items-center justify-center text-sm shadow-lg">
                                        <i class="fas fa-play ml-0.5"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-white dark:bg-gray-900">
                                <span class="text-[10px] font-bold text-red-600 uppercase">{{ item.category }} • {{ $t('resort.video_badge') }}</span>
                                <h5 class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate mt-0.5">{{ item.title || '—' }}</h5>
                                <a v-if="item.video_url" :href="item.video_url" target="_blank" class="text-[11px] text-blue-500 hover:underline truncate block mt-1">
                                    <i class="fa-solid fa-video text-xs mr-1"></i>{{ item.video_url }}
                                </a>
                            </div>
                            <div class="absolute top-2 right-2 flex gap-1 opacity-90 group-hover:opacity-100 transition">
                                <button @click="openVideoModal(item)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                <button @click="deleteGallery(item.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                            </div>
                        </div>
                        <div v-if="videoGalleries.length === 0" class="col-span-full text-center p-8 text-gray-400">
                            {{ $t('resort.no_videos') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 7: TEAMS (Note 14 Ordering) -->
            <div v-if="activeTab === 'team'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.team_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.team_reorder_help') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="saveTeamOrder" class="db-btn text-white bg-slate-800 hover:bg-slate-900 text-xs flex items-center gap-1.5">
                            <i class="lab lab-save"></i>
                            <span>{{ $t('resort.save_order') }}</span>
                        </button>
                        <button @click="openTeamModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                            <i class="lab lab-plus"></i> {{ $t('resort.add_team_member') }}
                        </button>
                    </div>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="(tm, idx) in localTeams" :key="tm.id" class="rounded-lg border border-gray-200 dark:border-gray-800 p-3 text-center relative group">
                            <img :src="tm.image" class="w-20 h-20 rounded-full object-cover mx-auto mb-2 border-2 border-primary/20" alt="team" />
                            <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ tm.name }}</h4>
                            <span class="text-[11px] text-primary block">{{ tm.designation }}</span>
                            <p class="text-[10px] text-gray-400 mt-1 line-clamp-2">{{ tm.bio }}</p>
                            <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-800 flex justify-center gap-2 text-xs text-gray-400">
                                <a v-if="tm.facebook" :href="tm.facebook" target="_blank"><i class="lab lab-facebook"></i></a>
                                <a v-if="tm.linkedin" :href="tm.linkedin" target="_blank"><i class="lab lab-linkedin"></i></a>
                                <a v-if="tm.instagram" :href="tm.instagram" target="_blank"><i class="lab lab-instagram"></i></a>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveTeam(idx, -1)" :disabled="idx === 0"
                                        class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_up')">
                                        <i class="lab lab-arrow-up"></i>
                                    </button>
                                    <button type="button" @click="moveTeam(idx, 1)" :disabled="idx === localTeams.length - 1"
                                        class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_down')">
                                        <i class="lab lab-arrow-down"></i>
                                    </button>
                                    <span class="text-[11px] font-bold text-gray-500 ml-1">#{{ idx + 1 }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="openTeamModal(tm)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                    <button @click="deleteTeam(tm.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                                </div>
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

            <!-- TAB 9: PARTNERS (Note 14 Ordering) -->
            <div v-if="activeTab === 'partners'" class="db-card">
                <div class="db-card-header flex items-center justify-between">
                    <div>
                        <h3 class="db-card-title">{{ $t('resort.partners_title') }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $t('resort.partners_reorder_help') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="savePartnerOrder" class="db-btn text-white bg-slate-800 hover:bg-slate-900 text-xs flex items-center gap-1.5">
                            <i class="lab lab-save"></i>
                            <span>{{ $t('resort.save_order') }}</span>
                        </button>
                        <button @click="openPartnerModal()" class="db-btn text-white bg-primary text-xs py-1.5 px-3 flex items-center gap-1">
                            <i class="lab lab-plus"></i> {{ $t('resort.add_partner') }}
                        </button>
                    </div>
                </div>
                <div class="db-card-body p-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div v-for="(part, idx) in localPartners" :key="part.id" class="p-3 rounded-lg border border-gray-200 dark:border-gray-800 text-center relative group">
                            <img :src="part.image" class="h-12 object-contain mx-auto mb-2" alt="partner" />
                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate">{{ part.name }}</h5>
                            <a v-if="part.website_url" :href="part.website_url" target="_blank" class="text-[10px] text-primary hover:underline truncate block">{{ $t('resort.website_url') }}</a>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="movePartner(idx, -1)" :disabled="idx === 0"
                                        class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_up')">
                                        <i class="lab lab-arrow-up"></i>
                                    </button>
                                    <button type="button" @click="movePartner(idx, 1)" :disabled="idx === localPartners.length - 1"
                                        class="w-6 h-6 rounded border border-gray-300 dark:border-gray-700 hover:bg-primary hover:text-white transition disabled:opacity-30 flex items-center justify-center text-xs" :title="$t('resort.move_down')">
                                        <i class="lab lab-arrow-down"></i>
                                    </button>
                                    <span class="text-[11px] font-bold text-gray-500 ml-1">#{{ idx + 1 }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button @click="openPartnerModal(part)" class="db-table-action edit"><i class="lab lab-edit"></i></button>
                                    <button @click="deletePartner(part.id)" class="db-table-action delete"><i class="lab lab-delete"></i></button>
                                </div>
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
                                            {{ b.type === 'party' ? $t('resort.party_booking') : $t('resort.resort_booking') }}
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
                    <div>
                        <label class="db-field-title">{{ $t('resort.sort_order_label') }}</label>
                        <input v-model.number="sliderForm.sort_order" type="number" class="db-field-control" :placeholder="$t('resort.sort_order_placeholder')" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="sliderModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: PHOTO GALLERY (Note 16 & Note 17) -->
    <div v-if="photoModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="photoModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ photoForm.id ? $t('resort.edit_photo') : $t('resort.new_photo') }}</h3>
            <form @submit.prevent="submitPhoto">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title">{{ $t('resort.title') }}</label>
                        <input v-model="photoForm.title" type="text" class="db-field-control" :placeholder="$t('resort.photo_title_placeholder')" />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.category') }}</label>
                        <select v-model="photoForm.category" class="db-field-control">
                            <option value="resort">{{ $t('resort.cat_resort') }}</option>
                            <option value="rooms">{{ $t('resort.cat_rooms') }}</option>
                            <option value="nature">{{ $t('resort.cat_nature') }}</option>
                            <option value="events">{{ $t('resort.cat_events') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="db-field-title font-bold text-primary">{{ $t('resort.upload_photo_file') }}</label>
                        <input type="file" @change="onPhotoFileChange" accept="image/*" class="db-field-control" />
                        <div v-if="photoPreview || photoForm.image_url" class="mt-2">
                            <img :src="photoPreview || photoForm.image_url" class="h-24 w-auto rounded border object-cover" />
                        </div>
                    </div>
                    <div>
                        <label class="db-field-title text-gray-400">{{ $t('resort.or_photo_url') }}</label>
                        <input v-model="photoForm.image_url" type="text" class="db-field-control" placeholder="https://..." />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="photoModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-primary text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: VIDEO GALLERY (Note 17) -->
    <div v-if="videoModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <button @click="videoModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500"><i class="lab lab-close text-lg"></i></button>
            <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 mb-4">{{ videoForm.id ? $t('resort.edit_video') : $t('resort.new_video') }}</h3>
            <form @submit.prevent="submitVideo">
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="db-field-title required">{{ $t('resort.title') }}</label>
                        <input v-model="videoForm.title" type="text" class="db-field-control" :placeholder="$t('resort.video_title_placeholder')" required />
                    </div>
                    <div>
                        <label class="db-field-title">{{ $t('resort.category') }}</label>
                        <select v-model="videoForm.category" class="db-field-control">
                            <option value="resort">{{ $t('resort.cat_resort') }}</option>
                            <option value="rooms">{{ $t('resort.cat_rooms') }}</option>
                            <option value="nature">{{ $t('resort.cat_nature') }}</option>
                            <option value="events">{{ $t('resort.cat_events') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="db-field-title required font-bold text-red-600">{{ $t('resort.youtube_embed_label') }}</label>
                        <input v-model="videoForm.video_url" type="text" class="db-field-control" placeholder="https://www.youtube.com/watch?v=..." required />
                        <span class="text-[10px] text-gray-400">{{ $t('resort.youtube_embed_help') }}</span>
                    </div>
                    <div>
                        <label class="db-field-title font-bold text-primary">{{ $t('resort.upload_video_cover') }}</label>
                        <input type="file" @change="onVideoFileChange" accept="image/*" class="db-field-control" />
                        <div v-if="videoPreview || videoForm.image_url" class="mt-2">
                            <img :src="videoPreview || videoForm.image_url" class="h-24 w-auto rounded border object-cover" />
                        </div>
                    </div>
                    <div>
                        <label class="db-field-title text-gray-400">{{ $t('resort.or_video_cover_url') }}</label>
                        <input v-model="videoForm.image_url" type="text" class="db-field-control" placeholder="https://..." />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" @click="videoModal = false" class="db-btn-outline sm py-1.5 px-4 text-xs">{{ $t('resort.cancel') }}</button>
                    <button type="submit" class="db-btn text-white bg-red-600 hover:bg-red-700 text-xs py-1.5 px-5">{{ $t('button.save') }}</button>
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
                    <div>
                        <label class="db-field-title">{{ $t('resort.sort_order_label') }}</label>
                        <input v-model.number="teamForm.sort_order" type="number" class="db-field-control" :placeholder="$t('resort.sort_order_placeholder')" />
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
                    <div>
                        <label class="db-field-title">{{ $t('resort.sort_order_label') }}</label>
                        <input v-model.number="partnerForm.sort_order" type="number" class="db-field-control" :placeholder="$t('resort.sort_order_placeholder')" />
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
            localSliders: [],
            localTeams: [],
            localPartners: [],
            tabs: [
                { id: "general",  icon: "lab lab-sliders" },
                { id: "sections", icon: "lab lab-list" },
                { id: "slider",   icon: "lab lab-image" },
                { id: "about",    icon: "lab lab-info" },
                { id: "gallery",  icon: "lab lab-variation" },
                { id: "videos",   icon: "fa-solid fa-video" },
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
            sliderForm: { id: null, title: "", subtitle: "", image_url: "", btn_text1: "", btn_link1: "", sort_order: null, file: null },
            photoModal: false,
            photoPreview: null,
            photoForm: { id: null, title: "", category: "resort", image_url: "", file: null },
            videoModal: false,
            videoPreview: null,
            videoForm: { id: null, title: "", category: "resort", image_url: "", video_url: "", file: null },
            galleryModal: false,
            galleryForm: { id: null, title: "", type: "photo", category: "resort", image_url: "", video_url: "", file: null },
            teamModal: false,
            teamForm: { id: null, name: "", designation: "", bio: "", image_url: "", facebook: "", linkedin: "", sort_order: null, file: null },
            planModal: false,
            planForm: { id: null, title: "", price: 0, duration: "", badge: "", featuresText: "", is_featured: 0 },
            partnerModal: false,
            partnerForm: { id: null, name: "", website_url: "", image_url: "", sort_order: null, file: null },
            eventModal: false,
            eventForm: { id: null, title: "", tag: "", description: "", image_url: "", is_about_slider: 0, file: null },
        };
    },
    computed: {
        sliders() { return this.$store.getters['resort/sliders'] || []; },
        galleries() { return this.$store.getters['resort/galleries'] || []; },
        photoGalleries() { return (this.$store.getters['resort/galleries'] || []).filter(g => g.type === 'photo'); },
        videoGalleries() { return (this.$store.getters['resort/galleries'] || []).filter(g => g.type === 'video'); },
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
        },
        sliders: {
            immediate: true,
            handler(val) {
                if (val && val.length) {
                    this.localSliders = JSON.parse(JSON.stringify(val));
                }
            }
        },
        teams: {
            immediate: true,
            handler(val) {
                if (val && val.length) {
                    this.localTeams = JSON.parse(JSON.stringify(val));
                }
            }
        },
        partners: {
            immediate: true,
            handler(val) {
                if (val && val.length) {
                    this.localPartners = JSON.parse(JSON.stringify(val));
                }
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
            ]).then(([settingsRes, sectionsRes, slidersRes, , , teamsRes, , partnersRes]) => {
                if (settingsRes && settingsRes.data && settingsRes.data.data) {
                    this.settingsForm = { ...this.settingsForm, ...settingsRes.data.data };
                }
                if (sectionsRes && sectionsRes.data && sectionsRes.data.data) {
                    this.localSections = JSON.parse(JSON.stringify(sectionsRes.data.data));
                }
                if (slidersRes && slidersRes.data && slidersRes.data.data) {
                    this.localSliders = JSON.parse(JSON.stringify(slidersRes.data.data));
                }
                if (teamsRes && teamsRes.data && teamsRes.data.data) {
                    this.localTeams = JSON.parse(JSON.stringify(teamsRes.data.data));
                }
                if (partnersRes && partnersRes.data && partnersRes.data.data) {
                    this.localPartners = JSON.parse(JSON.stringify(partnersRes.data.data));
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
                alertService.error(this.$t('message.something_went_wrong'));
            }).finally(() => {
                this.loading.isActive = false;
            });
        },

        // SLIDER
        moveSlider(idx, direction) {
            const target = idx + direction;
            if (target < 0 || target >= this.localSliders.length) return;
            const temp = this.localSliders[idx];
            this.localSliders[idx] = this.localSliders[target];
            this.localSliders[target] = temp;
        },
        saveSliderOrder() {
            this.loading.isActive = true;
            this.$store.dispatch('resort/updateSlidersOrder', this.localSliders).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            }).catch(() => {
                alertService.error(this.$t('message.something_went_wrong'));
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        openSliderModal(slide = null) {
            if (slide) {
                this.sliderForm = { ...slide, file: null };
                this.$store.commit('resort/temp', { temp_id: slide.id, isEditing: true });
            } else {
                this.sliderForm = { id: null, title: "", subtitle: "", image_url: "", btn_text1: "Book Now", btn_link1: "#booking", sort_order: null, file: null };
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
            if (this.sliderForm.sort_order !== null && this.sliderForm.sort_order !== undefined) {
                formData.append('sort_order', this.sliderForm.sort_order);
            }
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
            if (!confirm(this.$t('resort.delete_slider_confirm'))) return;
            this.$store.dispatch('resort/destroySlider', id).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            });
        },

        // PHOTO GALLERY (Note 16 & Note 17)
        openPhotoModal(item = null) {
            if (item) {
                this.photoForm = { id: item.id, title: item.title || "", category: item.category || "resort", image_url: item.image_url || "", file: null };
                this.photoPreview = item.image || null;
                this.$store.commit('resort/temp', { temp_id: item.id, isEditing: true });
            } else {
                this.photoForm = { id: null, title: "", category: "resort", image_url: "", file: null };
                this.photoPreview = null;
                this.$store.commit('resort/reset');
            }
            this.photoModal = true;
        },
        onPhotoFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.photoForm.file = file;
                this.photoPreview = URL.createObjectURL(file);
            }
        },
        submitPhoto() {
            const formData = new FormData();
            formData.append('type', 'photo');
            if (this.photoForm.title) formData.append('title', this.photoForm.title);
            if (this.photoForm.category) formData.append('category', this.photoForm.category);
            if (this.photoForm.image_url) formData.append('image_url', this.photoForm.image_url);
            if (this.photoForm.file) formData.append('image', this.photoForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveGallery', formData).then(() => {
                this.photoModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving photo'))
            .finally(() => this.loading.isActive = false);
        },

        // VIDEO GALLERY (Note 17)
        openVideoModal(item = null) {
            if (item) {
                this.videoForm = { id: item.id, title: item.title || "", category: item.category || "resort", image_url: item.image_url || "", video_url: item.video_url || "", file: null };
                this.videoPreview = item.image || null;
                this.$store.commit('resort/temp', { temp_id: item.id, isEditing: true });
            } else {
                this.videoForm = { id: null, title: "", category: "resort", image_url: "", video_url: "", file: null };
                this.videoPreview = null;
                this.$store.commit('resort/reset');
            }
            this.videoModal = true;
        },
        onVideoFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.videoForm.file = file;
                this.videoPreview = URL.createObjectURL(file);
            }
        },
        submitVideo() {
            const formData = new FormData();
            formData.append('type', 'video');
            if (this.videoForm.title) formData.append('title', this.videoForm.title);
            if (this.videoForm.category) formData.append('category', this.videoForm.category);
            if (this.videoForm.video_url) formData.append('video_url', this.videoForm.video_url);
            if (this.videoForm.image_url) formData.append('image_url', this.videoForm.image_url);
            if (this.videoForm.file) formData.append('image', this.videoForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveGallery', formData).then(() => {
                this.videoModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving video'))
            .finally(() => this.loading.isActive = false);
        },
        deleteGallery(id) {
            if (!confirm(this.$t('resort.delete_item_confirm'))) return;
            this.$store.dispatch('resort/destroyGallery', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // TEAMS
        moveTeam(idx, direction) {
            const target = idx + direction;
            if (target < 0 || target >= this.localTeams.length) return;
            const temp = this.localTeams[idx];
            this.localTeams[idx] = this.localTeams[target];
            this.localTeams[target] = temp;
        },
        saveTeamOrder() {
            this.loading.isActive = true;
            this.$store.dispatch('resort/updateTeamsOrder', this.localTeams).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            }).catch(() => {
                alertService.error(this.$t('message.something_went_wrong'));
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        openTeamModal(tm = null) {
            if (tm) {
                this.teamForm = { ...tm, file: null };
                this.$store.commit('resort/temp', { temp_id: tm.id, isEditing: true });
            } else {
                this.teamForm = { id: null, name: "", designation: "", bio: "", image_url: "", facebook: "", linkedin: "", sort_order: null, file: null };
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
            if (this.teamForm.sort_order !== null && this.teamForm.sort_order !== undefined) {
                formData.append('sort_order', this.teamForm.sort_order);
            }
            if (this.teamForm.file) formData.append('image', this.teamForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/saveTeam', formData).then(() => {
                this.teamModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving team member'))
            .finally(() => this.loading.isActive = false);
        },
        deleteTeam(id) {
            if (!confirm(this.$t('resort.delete_item_confirm'))) return;
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
            if (!confirm(this.$t('resort.delete_item_confirm'))) return;
            this.$store.dispatch('resort/destroyPlan', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // PARTNERS
        movePartner(idx, direction) {
            const target = idx + direction;
            if (target < 0 || target >= this.localPartners.length) return;
            const temp = this.localPartners[idx];
            this.localPartners[idx] = this.localPartners[target];
            this.localPartners[target] = temp;
        },
        savePartnerOrder() {
            this.loading.isActive = true;
            this.$store.dispatch('resort/updatePartnersOrder', this.localPartners).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            }).catch(() => {
                alertService.error(this.$t('message.something_went_wrong'));
            }).finally(() => {
                this.loading.isActive = false;
            });
        },
        openPartnerModal(part = null) {
            if (part) {
                this.partnerForm = { ...part, file: null };
                this.$store.commit('resort/temp', { temp_id: part.id, isEditing: true });
            } else {
                this.partnerForm = { id: null, name: "", website_url: "", image_url: "", sort_order: null, file: null };
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
            if (this.partnerForm.sort_order !== null && this.partnerForm.sort_order !== undefined) {
                formData.append('sort_order', this.partnerForm.sort_order);
            }
            if (this.partnerForm.file) formData.append('image', this.partnerForm.file);

            this.loading.isActive = true;
            this.$store.dispatch('resort/savePartner', formData).then(() => {
                this.partnerModal = false;
                alertService.success(this.$t('message.file_update_success'));
            }).catch(err => alertService.error(err.response?.data?.message || 'Error saving partner'))
            .finally(() => this.loading.isActive = false);
        },
        deletePartner(id) {
            if (!confirm(this.$t('resort.delete_item_confirm'))) return;
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
            if (!confirm(this.$t('resort.delete_item_confirm'))) return;
            this.$store.dispatch('resort/destroyEvent', id).then(() => alertService.success(this.$t('message.file_update_success')));
        },

        // BOOKINGS
        updateBookingStatus(b) {
            this.$store.dispatch('resort/updateBookingStatus', { id: b.id, status: b.status }).then(() => {
                alertService.success(this.$t('message.file_update_success'));
            });
        },
        deleteBooking(id) {
            if (!confirm(this.$t('resort.delete_booking_confirm'))) return;
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
