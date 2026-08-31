<template>
    <LoadingComponent :props="loading"/>
    <section class="min-h-screen py-10 px-4 flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
        <div class="w-full max-w-[380px] p-6 sm:p-8 shadow-xl rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-colors">
            <h2 class="mb-6 text-center text-xl font-bold text-gray-900 dark:text-white">
                {{ $t('label.create_new_password') }}
            </h2>
            <form @submit.prevent="resetPassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('label.new_password') }}
                    </label>
                    <input :class="errors.password ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.password" type="password"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        placeholder="••••••••">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.password">{{ errors.password[0] }}</small>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('label.confirm_password') }}
                    </label>
                    <input :class="errors.password_confirmation ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.password_confirmation" type="password"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        placeholder="••••••••">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</small>
                </div>

                <button type="submit"
                    class="w-full h-12 font-semibold text-sm rounded-xl text-white bg-orange-500 hover:bg-orange-600 active:scale-[0.99] transition shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                    <span>{{ $t('button.submit') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>
    </section>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import alertService from "../../../services/alertService";

export default {
    name: "ResetPasswordComponent",
    components: {LoadingComponent},
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: null,
                password: null,
                password_confirmation: null
            },
            errors: {}
        }
    },
    mounted() {
        this.emailChecking();
    },
    methods: {
        emailChecking: function () {
            if (this.$store.getters.resetInfo.email) {
                this.form.email = this.$store.getters.resetInfo.email;
            } else {
                this.$router.push({name: 'auth.verifyEmail'});
            }
        },
        resetPassword: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('resetPassword', this.form).then((res) => {
                    this.$store.dispatch('login', {
                        email: this.form.email,
                        password: this.form.password
                    }).then(LoginRes => {
                        this.loading.isActive = false;
                        alertService.success(LoginRes.data.message);
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.success(res.data.message);
                        this.$router.push({name: "auth.login"});
                    });
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        }
    }
}
</script>
