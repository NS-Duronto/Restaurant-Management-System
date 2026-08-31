<template>
    <aside class="db-sidebar flex flex-col justify-between"
        :class="$route.path.includes('kitchen-display-system') || $route.path.includes('order-status-screen') ? 'hidden' : ''">
        <div>
            <div class="db-sidebar-header pb-3 mb-4 border-b border-gray-200 dark:border-gray-800">
                <router-link class="flex items-center gap-2.5" :to="{ name: 'admin.dashboard' }">
                    <div
                        class="bg-orange-500 text-white p-2.5 rounded-xl font-bold text-base shadow-md shadow-orange-500/20">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-orange-500 dark:text-orange-400 block leading-tight">{{
                            setting.company_name || 'FoodScan' }}</span>
                        <span class="text-[10px] text-gray-500">রেস্টুরেন্ট আরএমএস</span>
                    </div>
                </router-link>
                <button @click.prevent="handleSidebar"
                    class="fa-solid fa-xmark text-gray-500 dark:text-gray-400 hover:text-red-500 text-lg"></button>
            </div>

            <nav class="db-sidebar-nav space-y-1">
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
        </div>

        <div v-if="authInfo.name"
            class="mt-4 pt-3 pb-2 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <img class="w-8 h-8 rounded-xl object-cover border border-orange-500/30" :src="authInfo.image"
                    alt="avatar">
                <div class="truncate max-w-[150px]">
                    <h4 class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate">{{ authInfo.name }}</h4>
                    <p class="text-[10px] text-green-500 dark:text-green-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> {{ $t('label.online')
                        || 'অনলাইন' }}
                    </p>
                </div>
            </div>
            <button @click="logout()"
                class="text-gray-400 hover:text-red-500 transition p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg text-xs"
                title="লগআউট">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
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
    },
    methods: {
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
