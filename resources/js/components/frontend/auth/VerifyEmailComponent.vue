<template>
    <LoadingComponent :props="loading" />
    <section class="min-h-screen py-10 px-4 flex flex-col justify-center items-center bg-gray-50 dark:bg-gray-950 transition-colors duration-200">
        <div class="w-full max-w-[380px] p-6 sm:p-8 shadow-xl rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 transition-colors">
            <h2 class="mb-6 text-center text-xl font-bold text-gray-900 dark:text-white">
                {{ $t('label.verify_email') }}
            </h2>
            <form @submit.prevent="verifyCode" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ $t('message.enter_the_code_sent_to') }} <span class="font-bold text-orange-500">{{ resetInfo.email }}</span>
                    </label>
                    <input :class="errors.code ? 'border-red-500 dark:border-red-500' : 'border-gray-200 dark:border-gray-700'"
                        v-model="form.code" type="number"
                        class="w-full h-12 rounded-xl border bg-gray-50/50 dark:bg-gray-800/80 px-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:bg-white dark:focus:bg-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 outline-none transition"
                        placeholder="123456">
                    <small class="text-xs text-red-500 mt-1 block" v-if="errors.code">{{ errors.code[0] }}</small>
                </div>

                <div class="text-right">
                    <button @click.prevent="resendCode" type="button"
                        class="text-xs font-medium text-orange-500 hover:text-orange-600 dark:text-orange-400 transition hover:underline">
                        {{ $t('button.resend_code') }}
                    </button>
                </div>

                <button type="submit"
                    class="w-full h-12 font-semibold text-sm rounded-xl text-white bg-orange-500 hover:bg-orange-600 active:scale-[0.99] transition shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                    <span>{{ $t('button.continue') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>
    </section>
</template>

<script>
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "VerifyEmailComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                email: null,
                code: null
            },
            errors: {}
        }
    },
    computed: {
        resetInfo: function () {
            return this.$store.getters.resetInfo;
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
                this.$router.push({ name: 'auth.forgetPassword' });
            }
        },
        resendCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('forgetPassword', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message);
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        verifyCode: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('verifyCode', this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.success(res.data.message);
                    this.$router.push({ name: 'auth.resetPassword' });
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