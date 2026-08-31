<template>
    <aside class="db-sidebar flex flex-col justify-between !overflow-hidden"
        :class="$route.path.includes('kitchen-display-system') || $route.path.includes('order-status-screen') ? 'hidden' : ''">
        
        <!-- Fixed Header -->
        <div class="db-sidebar-header pb-3 mb-3 border-b border-gray-200 dark:border-gray-800 flex-shrink-0">
            <router-link class="flex items-center gap-2.5" :to="{ name: 'admin.dashboard' }">
                <div
                    class="bg-orange-500 text-white p-2.5 rounded-xl font-bold text-base shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div>
                    <span class="text-sm font-bold text-orange-500 dark:text-orange-400 block leading-tight">{{
                        setting.company_name || 'Sohoj RMS' }}</span>
                    <span class="text-[10px] text-gray-500">রেস্টুরেন্ট আরএমএস</span>
                </div>
            </router-link>
            <button @click.prevent="handleSidebar"
                class="fa-solid fa-xmark text-gray-500 dark:text-gray-400 hover:text-red-500 text-lg"></button>
        </div>

        <!-- Scrollable Navigation Area -->
        <nav class="db-sidebar-nav flex-1 overflow-y-auto overflow-x-hidden space-y-1 pr-1">
            <ul class="db-sidebar-nav-list space-y-1" v-if="menus.length > 0" v-for="menu in menus" :key="menu">
                <li class="db-sidebar-nav-item" v-if="menu.url === '#'">
                    <span
                        class="db-sidebar-nav-title px-3 pt-3 pb-1 block text-[11px] font-semibold tracking-wider text-gray-400 dark:text-gray-500 uppercase">
                        {{ $t('menu.' + menu.language) }}
                    </span>
                </li>

                <li class="db-sidebar-nav-item" v-else>
                    <router-link :to="'/admin/' + menu.url" class="db-sidebar-nav-menu">
                        <i class="text-sm" :class="menu.icon"></i>
                        <span class="text-sm font-medium flex-auto">{{ $t('menu.' + menu.language) }}</span>
                    </router-link>
                </li>

                <li class="db-sidebar-nav-item" v-if="menu.children" v-for="children in menu.children">
                    <router-link :to="'/admin/' + children.url" class="db-sidebar-nav-menu">
                        <i class="text-sm" :class="children.icon"></i>
                        <span class="text-sm font-medium flex-auto">{{ $t('menu.' + children.language) }}</span>
                    </router-link>
                </li>
            </ul>
        </nav>

        <!-- Fixed Bottom Profile & Settings Section -->
        <div v-if="authInfo.name" class="relative mt-2 pt-3 border-t border-gray-200 dark:border-gray-800 flex-shrink-0 bg-white dark:bg-gray-900">
            <!-- Upward Settings / Profile Popup -->
            <transition name="fade-up">
                <div v-if="profileMenuOpen"
                    class="absolute bottom-full left-0 mb-2 w-full bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 p-3 z-50 overflow-hidden">
                    <div class="flex items-center gap-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                        <img class="w-10 h-10 rounded-xl object-cover border-2 border-orange-500/30 flex-shrink-0" :src="authInfo.image" alt="avatar">
                        <div class="truncate flex-1">
                            <h4 class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate">{{ authInfo.name }}</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ authInfo.email }}</p>
                        </div>
                    </div>

                    <div class="pt-2 space-y-1">
                        <router-link :to="{ name: 'admin.profile.editProfile' }" @click="profileMenuOpen = false"
                            class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-orange-500/10 hover:text-orange-500 dark:hover:text-orange-400 transition">
                            <i class="lab lab-edit lab-font-size-16 text-orange-500"></i>
                            <span>{{ $t('button.edit_profile') }}</span>
                        </router-link>

                        <router-link :to="{ name: 'admin.profile.changePassword' }" @click="profileMenuOpen = false"
                            class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-xs font-medium text-gray-700 dark:text-gray-200 hover:bg-orange-500/10 hover:text-orange-500 dark:hover:text-orange-400 transition">
                            <i class="lab lab-key lab-font-size-16 text-orange-500"></i>
                            <span>{{ $t('button.change_password') }}</span>
                        </router-link>

                        <button @click="logout(); profileMenuOpen = false;"
                            class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-500/10 transition text-left">
                            <i class="lab lab-logout lab-font-size-16 text-red-500"></i>
                            <span>{{ $t('button.logout') }}</span>
                        </button>
                    </div>
                </div>
            </transition>

            <!-- Bottom Left Profile Trigger -->
            <div class="flex items-center justify-between p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition cursor-pointer"
                @click="profileMenuOpen = !profileMenuOpen">
                <div class="flex items-center space-x-2.5 min-w-0">
                    <img class="w-8 h-8 rounded-xl object-cover border border-orange-500/30 flex-shrink-0" :src="authInfo.image"
                        alt="avatar">
                    <div class="truncate max-w-[130px]">
                        <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ authInfo.name }}</h4>
                        <p class="text-[10px] text-green-500 dark:text-green-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> {{ $t('label.online') || 'অনলাইন' }}
                        </p>
                    </div>
                </div>
                <button type="button" class="text-gray-400 hover:text-orange-500 p-1 transition text-xs" :title="$t('button.edit_profile')">
                    <i :class="profileMenuOpen ? 'fa-solid fa-chevron-down text-orange-500' : 'fa-solid fa-gear'"></i>
                </button>
            </div>
        </div>
    </aside>
</template>

<script>
export default {
    name: "BackendMenuComponent",
    data: function () {
        return {
            activeParentId: 1,
            activeChildId: 0,
            sidebarOpen: false,
            profileMenuOpen: false,
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        menus: function () {
            return this.$store.getters.authMenu;
        },
        sidebar() {
            return this.$store.getters['globalState/lists'].topSidebar;
        },
        authInfo: function () {
            return this.$store.getters.authInfo;
        },
    },
    mounted() {
        this.defaultSidebarActive();
        document.addEventListener("click", this.closeProfileMenuOnClickOutside);
    },
    beforeUnmount() {
        document.removeEventListener("click", this.closeProfileMenuOnClickOutside);
    },
    methods: {
        closeProfileMenuOnClickOutside: function (e) {
            if (!this.$el.contains(e.target)) {
                this.profileMenuOpen = false;
            }
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$router.push({ name: "auth.login" });
            }).catch();
        },
        sidebarActive: function (e) {
            const activeMenu = document.querySelector('.db-sidebar-nav-item.active');
            if (activeMenu) {
                activeMenu.classList.remove('active');
            }
            e?.currentTarget?.classList?.add('active');
        },
        defaultSidebarActive: function () {
            const activeMenu = document.querySelector(".db-sidebar-nav-menu.active");
            if (activeMenu) {
                activeMenu.closest(".db-sidebar-nav-item")?.classList.add("active");
            } else {
                document?.querySelector('.router-link-exact-active')?.parentElement?.classList?.add('active');
            }
        },
        handleSidebar: function () {
            this.sidebarOpen = !this.sidebar;
            this.$store.dispatch("globalState/set", { topSidebar: this.sidebarOpen });

            if (document?.querySelector(".db-sidebar")?.classList?.contains("active")) {
                document?.querySelector(".db-main")?.classList?.remove("expand");
                document?.querySelector(".db-sidebar")?.classList?.remove("active");
                document?.querySelector(".backdrop")?.classList?.remove("active");
            } else {
                document?.querySelector(".db-sidebar")?.classList?.add("active");
                document?.querySelector(".db-main")?.classList?.add("expand");
                document?.querySelector(".backdrop")?.classList?.add("active");
            }
        },
    },
    watch: {
        $route() {
            this.$nextTick(() => {
                document.querySelectorAll(".db-sidebar-nav-item").forEach(el => el.classList.remove("active"));
                this.defaultSidebarActive();
            });
        }
    },
}
</script>
