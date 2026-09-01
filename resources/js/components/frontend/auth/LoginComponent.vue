<template>
    <LoadingComponent :props="loading" />
    <section class="min-h-screen py-10 px-4 flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
        
        <!-- Top Brand & Theme Switcher -->
        <div class="w-full max-w-[380px] flex items-center justify-between mb-6 px-1">
            <div class="flex items-center gap-2.5">
                <div class="bg-orange-500 text-white p-2 rounded-xl font-bold text-sm shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <span class="text-base font-bold text-orange-500 dark:text-orange-400">
                    {{ setting.company_name || 'Sohoj RMS' }}
                </span>
            </div>
            
            <!-- Dark / Light Mode Toggle -->
            <button @click="toggleTheme" type="button"
                class="w-9 h-9 rounded-xl flex items-center justify-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400 shadow-sm transition"
                :title="isDarkMode ? $t('label.light_mode') : $t('label.dark_mode')">
                <i :class="isDarkMode ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-indigo-500'"></i>
            </button>
        </div>

        <!-- Main Login Card -->
        <div class="w-full max-w-[380px] p-6 sm:p-8 mb-4 shadow-xl rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-colors">
            <h2 class="mb-6 text-center text-xl font-bold text-gray-900 dark:text-white">
                {{ $t('label.welcome_back') }}
            </h2>

            <div v-if="errors.validation"
                class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 mb-5 rounded-2xl flex items-start gap-2.5 text-sm"
                role="alert">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                <span class="flex-auto">{{ errors.validation }}</span>
                <button type="button" @click="close" class="leading-none text-red-400 hover:text-red-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label for="formEmail" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('label.email') }}
                    </label>
                    <input type="text" :class="errors.email ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.email"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        id="formEmail" placeholder="admin@example.com">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.email">{{ errors.email[0] }}</small>
                </div>

                <div>
                    <label for="formPassword" class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('label.password') }}
                    </label>
                    <input autocomplete="off" type="password" :class="errors.password ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.password"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        id="formPassword" placeholder="••••••••">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.password">{{ errors.password[0] }}</small>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-400">
                        <input type="checkbox" id="checkbox2" class="w-4 h-4 rounded text-orange-500 focus:ring-orange-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                        <span>{{ $t('label.remember_me') }}</span>
                    </label>
                    <router-link :to="{ name: 'auth.forgetPassword' }"
                        class="text-xs font-medium text-orange-500 hover:text-orange-600 dark:text-orange-400 transition">
                        {{ $t('button.forget_password') }}
                    </router-link>
                </div>

                <button type="submit"
                    class="w-full h-12 mt-2 font-semibold text-sm rounded-xl text-white bg-orange-500 hover:bg-orange-600 active:scale-[0.99] transition shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                    <span>{{ $t('button.login') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Demo Quick Login Card -->
        <div v-if="demo === 'true' || demo === 'TRUE' || demo === 'True' || demo === '1' || demo === 1"
            class="w-full max-w-[380px] p-6 shadow-xl rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-colors">
            <h2 class="mb-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                {{ $t('message.for_quick_demo') }}
            </h2>
            <nav class="grid grid-cols-1 gap-2.5">
                <button @click.prevent="setupCredit('admin')"
                    class="click-to-prop w-full h-10 rounded-xl text-center text-xs font-semibold capitalize text-white bg-orange-500 hover:bg-orange-600 active:scale-95 transition shadow-sm"
                    id="adminClick">
                    {{ $t('label.admin') }}
                </button>
                <!-- 
                <button @click.prevent="setupCredit('branchManager')"
                    class="click-to-prop w-full h-10 rounded-xl text-center text-xs font-semibold capitalize text-white bg-sky-600 hover:bg-sky-700 active:scale-95 transition shadow-sm"
                    id="branchManagerClick">
                    {{ $t('label.branch_manager') }}
                </button>
                <button @click.prevent="setupCredit('posOperator')"
                    class="click-to-prop w-full h-10 rounded-xl text-center text-xs font-semibold capitalize text-white bg-purple-600 hover:bg-purple-700 active:scale-95 transition shadow-sm"
                    id="posOperatorClick">
                    {{ $t('label.pos_operator') }}
                </button>
                <button @click.prevent="setupCredit('chef')"
                    class="click-to-prop w-full h-10 rounded-xl text-center text-xs font-semibold capitalize text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 transition shadow-sm"
                    id="chefClick">
                    {{ $t('label.chef_kitchen') }}
                </button>
                -->
            </nav>
        </div>
    </section>
</template>

<script>
import router from "../../../router";
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";
import ENV from "../../../config/env";
import { routes } from "../../../router";
import appService from "../../../services/appService";

export default {
    name: "LoginComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: "",
                password: ""
            },
            errors: {},
            permissions: {},
            firstMenu: null,
            demo: ENV.DEMO,
            isDarkMode: localStorage.getItem('rms_theme') === 'dark' || (!('rms_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        permission: function () {
            return this.$store.getters.authPermission;
        }
    },
    methods: {
        toggleTheme: function () {
            this.isDarkMode = !this.isDarkMode;
            if (this.isDarkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('rms_theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('rms_theme', 'light');
            }
        },
        login: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('login', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message);

                    // Patch route meta (access/title) from the freshly loaded permission
                    // list BEFORE navigating, so the very first navigation already
                    // respects what this user is allowed to see.
                    appService.recursiveRouter(routes, this.permission);

                    const defaultPermission = res.data.defaultPermission;
                    if (defaultPermission && defaultPermission.url) {
                        // Send the user straight to the first menu they actually have
                        // access to, instead of always forcing them onto the dashboard.
                        const accessibleRoute = router.getRoutes()
                            .filter(r =>
                                r.meta.isFrontend === false &&
                                r.meta.permissionUrl === defaultPermission.url &&
                                r.name
                            )
                            .sort((a, b) => a.path.length - b.path.length)[0];
                        router.push(accessibleRoute && accessibleRoute.name
                            ? { name: accessibleRoute.name }
                            : { name: "admin.dashboard" }
                        );
                    } else {
                        // No permission at all was granted to this account — sending
                        // them to the dashboard would just render a blank page with
                        // no menu, so land them on the exception page instead.
                        router.push({ name: "route.exception" });
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
            }
        },
        close: function () {
            this.errors = {}
        },
        setupCredit: function (e) {
            if (e === 'admin') {
                this.form.email = 'admin@example.com';
                this.form.password = '123456';
            } else if (e === 'customer') {
                this.form.email = 'customer@example.com';
                this.form.password = '123456';
            } else if (e === 'branchManager') {
                this.form.email = 'branchmanager@example.com';
                this.form.password = '123456';
            } else if (e === 'posOperator') {
                this.form.email = 'posoperator@example.com';
                this.form.password = '123456';
            } else if (e === 'chef') {
                this.form.email = 'chef@example.com';
                this.form.password = '123456';
            }
        }
    }
}
</script>