<template>
    <LoadingComponent :props="loading" />
    <section class="min-h-screen py-10 px-4 flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
        <div class="w-full max-w-[380px] p-6 sm:p-8 shadow-xl rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-colors">
            <h2 class="mb-6 text-center text-xl font-bold text-gray-900 dark:text-white">
                {{ $t('label.forget_password') }}
            </h2>
            <form @submit.prevent="forgetPassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('label.email') }}
                    </label>
                    <input :class="errors.email ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.email" type="email"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        placeholder="you@example.com">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.email">{{ errors.email[0] }}</small>
                </div>

                <button type="submit"
                    class="w-full h-12 font-semibold text-sm rounded-xl text-white bg-orange-500 hover:bg-orange-600 active:scale-[0.99] transition shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                    <span>{{ $t('label.next') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>

                <div class="flex items-center justify-center gap-2 pt-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $t('label.already_have_an_account') }}</span>
                    <router-link class="text-xs font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 transition" :to="{ name: 'auth.login' }">
                        {{ $t('button.login') }}
                    </router-link>
                </div>
            </form>
        </div>
    </section>
</template>
<script>

import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "ForgetPasswordComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: ""
            },
            errors: {}
        }
    },
    methods: {
        forgetPassword: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('forgetPassword', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message);
                    this.$router.push({ name: 'auth.verifyEmail' });
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>