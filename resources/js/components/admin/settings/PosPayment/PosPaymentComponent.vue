<template>
    <LoadingComponent :props="loading" />

    <div id="pos-payment" class="db-card db-tab-div active">
        <div class="db-card-header">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-credit-card text-orange-500 text-lg"></i>
                <h3 class="db-card-title">{{ $t("menu.pos_payment") }}</h3>
            </div>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save">
                <!-- 1. Card Payment Sub-options -->
                <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-regular fa-credit-card text-primary"></i>
                                <span>{{ $t("label.card_types") }}</span>
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $t("label.card_types_hint") }}</p>
                        </div>
                        <button type="button" @click="resetCardsToDefault" class="text-xs font-semibold text-orange-600 hover:text-orange-700 underline">
                            {{ $t("label.reset_default") }}
                        </button>
                    </div>

                    <!-- Current Card Badges -->
                    <div class="flex flex-wrap gap-2 mb-4 min-h-[42px] p-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <span v-for="(card, index) in form.order_setup_pos_card_types" :key="index"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-orange-50 dark:bg-gray-800 border border-orange-200 dark:border-gray-700 text-orange-600 dark:text-orange-400 shadow-sm">
                            <i class="fa-solid fa-credit-card text-[11px]"></i>
                            <span>{{ card }}</span>
                            <button type="button" @click="removeCard(index)" class="text-gray-400 hover:text-red-500 transition ml-0.5">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </span>
                        <span v-if="form.order_setup_pos_card_types.length === 0" class="text-xs text-red-500 italic py-1">
                            {{ $t("message.no_card_types_added") }}
                        </span>
                    </div>

                    <!-- Add New Card Input -->
                    <div class="flex gap-2 max-w-md">
                        <input type="text" v-model="newCardName" @keydown.enter.prevent="addCard"
                            :placeholder="$t('label.enter_card_type_name')"
                            class="db-field-control text-xs rounded-xl h-10">
                        <button type="button" @click="addCard"
                            class="flex-shrink-0 px-4 h-10 rounded-xl text-xs font-bold text-white bg-primary hover:bg-primary/90 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>{{ $t("button.add") }}</span>
                        </button>
                    </div>
                </div>

                <!-- 2. MFS (Mobile Banking) Sub-options -->
                <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-800">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                <i class="fa-solid fa-mobile-screen-button text-primary"></i>
                                <span>{{ $t("label.mfs_types") }}</span>
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $t("label.mfs_types_hint") }}</p>
                        </div>
                        <button type="button" @click="resetMfsToDefault" class="text-xs font-semibold text-orange-600 hover:text-orange-700 underline">
                            {{ $t("label.reset_default") }}
                        </button>
                    </div>

                    <!-- Current MFS Badges -->
                    <div class="flex flex-wrap gap-2 mb-4 min-h-[42px] p-2.5 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
                        <span v-for="(mfs, index) in form.order_setup_pos_mfs_types" :key="index"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-orange-50 dark:bg-gray-800 border border-orange-200 dark:border-gray-700 text-orange-600 dark:text-orange-400 shadow-sm">
                            <i class="fa-solid fa-mobile-screen text-[11px]"></i>
                            <span>{{ mfs }}</span>
                            <button type="button" @click="removeMfs(index)" class="text-gray-400 hover:text-red-500 transition ml-0.5">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </span>
                        <span v-if="form.order_setup_pos_mfs_types.length === 0" class="text-xs text-red-500 italic py-1">
                            {{ $t("message.no_mfs_types_added") }}
                        </span>
                    </div>

                    <!-- Add New MFS Input -->
                    <div class="flex gap-2 max-w-md">
                        <input type="text" v-model="newMfsName" @keydown.enter.prevent="addMfs"
                            :placeholder="$t('label.enter_mfs_type_name')"
                            class="db-field-control text-xs rounded-xl h-10">
                        <button type="button" @click="addMfs"
                            class="flex-shrink-0 px-4 h-10 rounded-xl text-xs font-bold text-white bg-primary hover:bg-primary/90 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>{{ $t("button.add") }}</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-col-12">
                    <button type="submit" class="db-btn text-white bg-primary shadow-lg shadow-orange-500/20">
                        <i class="lab lab-save"></i>
                        <span>{{ $t("button.save") }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";

export default {
    name: "PosPaymentComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                order_setup_pos_card_types: ['Visa', 'Mastercard', 'Takapay', 'Nexuspay'],
                order_setup_pos_mfs_types: ['bKash', 'Rocket', 'Nagad', 'Ucash']
            },
            newCardName: "",
            newMfsName: "",
            defaultCards: ['Visa', 'Mastercard', 'Takapay', 'Nexuspay'],
            defaultMfs: ['bKash', 'Rocket', 'Nagad', 'Ucash'],
            errors: {},
        };
    },
    mounted() {
        this.loadSettings();
    },
    methods: {
        loadSettings: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("posPaymentSetting/lists").then((res) => {
                    const data = res.data.data;
                    if (data.order_setup_pos_card_types) {
                        this.form.order_setup_pos_card_types = Array.isArray(data.order_setup_pos_card_types)
                            ? [...data.order_setup_pos_card_types]
                            : JSON.parse(data.order_setup_pos_card_types);
                    }
                    if (data.order_setup_pos_mfs_types) {
                        this.form.order_setup_pos_mfs_types = Array.isArray(data.order_setup_pos_mfs_types)
                            ? [...data.order_setup_pos_mfs_types]
                            : JSON.parse(data.order_setup_pos_mfs_types);
                    }
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            } catch (err) {
                this.loading.isActive = false;
            }
        },
        addCard: function () {
            const name = this.newCardName.trim();
            if (!name) return;
            if (this.form.order_setup_pos_card_types.some(c => c.toLowerCase() === name.toLowerCase())) {
                alertService.warning(this.$t("message.item_already_exists") || "Already exists");
                return;
            }
            this.form.order_setup_pos_card_types.push(name);
            this.newCardName = "";
        },
        removeCard: function (index) {
            this.form.order_setup_pos_card_types.splice(index, 1);
        },
        resetCardsToDefault: function () {
            this.form.order_setup_pos_card_types = [...this.defaultCards];
        },
        addMfs: function () {
            const name = this.newMfsName.trim();
            if (!name) return;
            if (this.form.order_setup_pos_mfs_types.some(m => m.toLowerCase() === name.toLowerCase())) {
                alertService.warning(this.$t("message.item_already_exists") || "Already exists");
                return;
            }
            this.form.order_setup_pos_mfs_types.push(name);
            this.newMfsName = "";
        },
        removeMfs: function (index) {
            this.form.order_setup_pos_mfs_types.splice(index, 1);
        },
        resetMfsToDefault: function () {
            this.form.order_setup_pos_mfs_types = [...this.defaultMfs];
        },
        save: function () {
            if (this.form.order_setup_pos_card_types.length === 0) {
                alertService.error(this.$t("message.at_least_one_card_required") || "Please add at least one card type");
                return;
            }
            if (this.form.order_setup_pos_mfs_types.length === 0) {
                alertService.error(this.$t("message.at_least_one_mfs_required") || "Please add at least one MFS type");
                return;
            }

            try {
                this.loading.isActive = true;
                this.$store.dispatch("posPaymentSetting/save", this.form).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(1, this.$t("menu.pos_payment"));
                    this.$store.dispatch("frontendSetting/lists").then().catch();
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response?.data?.message || "Failed to save POS payment settings");
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
