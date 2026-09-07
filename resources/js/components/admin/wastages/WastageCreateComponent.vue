<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer !w-[800px] max-w-full">
        <div class="drawer-dialog !w-[800px] max-w-full">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.wastages') || 'অপচয় ও নষ্ট মাল (Wastage & Spoilage)' }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <!-- Date -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="wastage_date" class="db-field-title required">{{ $t("label.date") || 'তারিখ' }}</label>
                            <input v-model="props.form.date" type="date" id="wastage_date" class="db-field-control" required>
                            <small class="db-field-alert" v-if="errors.date">{{ errors.date[0] }}</small>
                        </div>

                        <!-- Dynamic Items -->
                        <div class="form-col-12 mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="db-field-title !mb-0 required font-bold">
                                    {{ $t('label.wastage_items') || 'নষ্ট / অপচয়কৃত কাঁচামালের তালিকা' }}
                                </label>
                                <button type="button" @click="addItemRow"
                                    class="text-xs font-bold text-red-600 hover:text-red-700 flex items-center gap-1 bg-red-50 dark:bg-gray-800 px-2.5 py-1 rounded-lg border border-red-200 dark:border-gray-700">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>{{ $t('button.add_item_row') || 'আরেকটি যোগ করুন' }}</span>
                                </button>
                            </div>

                            <div class="space-y-3">
                                <div v-for="(row, index) in props.form.items" :key="index"
                                    class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 space-y-2">
                                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                                        <!-- Material -->
                                        <div class="flex-1 min-w-[200px]">
                                            <label class="text-[11px] text-gray-500 font-semibold block mb-1">{{ $t('label.raw_material') || 'কাঁচামাল' }}</label>
                                            <vue-select class="db-field-control text-xs"
                                                v-model="row.kitchen_goods_id" @change="onGoodsChange(row)"
                                                :options="kitchenGoodsOptions" label-by="name"
                                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                                :placeholder="$t('label.select_raw_material') || 'কাঁচামাল নির্বাচন করুন'" />
                                        </div>

                                        <!-- Quantity -->
                                        <div class="w-32">
                                            <label class="text-[11px] text-gray-500 font-semibold block mb-1">{{ $t('label.quantity') || 'পরিমাণ' }}</label>
                                            <input v-model="row.quantity" type="number" step="0.001" min="0.001"
                                                :placeholder="$t('label.quantity')" class="db-field-control text-xs" required>
                                        </div>

                                        <!-- Unit Cost -->
                                        <div class="w-28">
                                            <label class="text-[11px] text-gray-500 font-semibold block mb-1">{{ $t('label.cost_per_unit') || 'দর' }}</label>
                                            <input v-model="row.cost_per_unit" type="number" step="0.01" min="0"
                                                placeholder="Unit Cost" class="db-field-control text-xs" required>
                                        </div>

                                        <!-- Reason -->
                                        <div class="w-40">
                                            <label class="text-[11px] text-gray-500 font-semibold block mb-1">{{ $t('label.reason') || 'কারণ' }}</label>
                                            <select v-model="row.reason" class="db-field-control text-xs">
                                                <option value="Damaged">{{ $t('label.reason_damaged') || 'পচে গেছে / নষ্ট' }}</option>
                                                <option value="Expired">{{ $t('label.reason_expired') || 'মেয়াদোত্তীর্ণ' }}</option>
                                                <option value="Burnt">{{ $t('label.reason_burnt') || 'রান্নায় পুড়ে গেছে' }}</option>
                                                <option value="Spilled">{{ $t('label.reason_spilled') || 'পড়ে নষ্ট হয়েছে' }}</option>
                                                <option value="Other">{{ $t('label.reason_other') || 'অন্যান্য' }}</option>
                                            </select>
                                        </div>

                                        <!-- Remove Row Button -->
                                        <div class="pt-5" v-if="props.form.items.length > 1">
                                            <button type="button" @click="removeItemRow(index)"
                                                class="text-gray-400 hover:text-red-500 p-1 text-sm">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Line total display -->
                                    <div class="text-right text-xs text-gray-600 dark:text-gray-300">
                                        <span>{{ $t('label.loss_amount') || 'ক্ষতি' }}: </span>
                                        <span class="font-bold text-red-600 dark:text-red-400">
                                            {{ currencyFormat(((Number(row.quantity) || 0) * (Number(row.cost_per_unit) || 0))) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Voucher Loss -->
                        <div class="form-col-12 mt-3">
                            <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 rounded-xl flex items-center justify-between">
                                <span class="font-semibold text-gray-700 dark:text-gray-200 text-sm">
                                    {{ $t('label.total_loss_amount') || 'মোট আর্থিক ক্ষতি (Total Loss):' }}
                                </span>
                                <span class="text-lg font-bold text-red-600 dark:text-red-400">
                                    {{ currencyFormat(totalCalculatedLoss) }}
                                </span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-col-12 mt-3">
                            <label for="wastage_note" class="db-field-title">{{ $t("label.note") || 'নোট / মন্তব্য' }}</label>
                            <textarea v-model="props.form.note" id="wastage_note" class="db-field-control" :placeholder="$t('label.wastage_note_placeholder') || 'ক্ষতির বিবরণ বা কারণ লিখুন...'"></textarea>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-red-600 hover:bg-red-700">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                    <span>{{ $t("button.record_wastage") || 'অপচয় রেকর্ড করুন' }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "WastageCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            errors: {},
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
        },
        addButton: function () {
            return { title: this.$t('button.add_wastage') || 'Add Wastage Entry' };
        },
        kitchenGoods: function () {
            return this.$store.getters['kitchenGoods/lists'] || [];
        },
        kitchenGoodsOptions: function () {
            const list = this.$store.getters['kitchenGoods/lists'] || [];
            return list.map(item => ({
                id: item.id,
                name: `${item.name} (${this.$t('label.stock') || 'স্টক'}: ${Number(item.current_stock || 0).toFixed(2)} ${item.unit_code || ''})`
            }));
        },
        totalCalculatedLoss: function () {
            if (!this.props.form.items) return 0;
            return this.props.form.items.reduce((sum, row) => {
                return sum + ((Number(row.quantity) || 0) * (Number(row.cost_per_unit) || 0));
            }, 0);
        }
    },
    mounted() {
        this.$store.dispatch('kitchenGoods/lists', { paginate: 0 }).then().catch();
    },
    methods: {
        currencyFormat: function (amount) {
            return appService.currencyFormat(
                amount,
                this.setting?.site_digit_after_decimal_point,
                this.setting?.site_default_currency_symbol,
                this.setting?.site_currency_position
            );
        },
        onGoodsChange: function (row) {
            const good = this.kitchenGoods.find(g => g.id === row.kitchen_goods_id);
            if (good) {
                row.cost_per_unit = good.cost_per_unit || 0;
                row.unit_id = good.unit_id;
            }
        },
        addItemRow: function () {
            this.props.form.items.push({
                kitchen_goods_id: null,
                quantity: "",
                cost_per_unit: 0,
                unit_id: null,
                reason: "Damaged",
            });
        },
        removeItemRow: function (index) {
            this.props.form.items.splice(index, 1);
        },
        addReset: function () {
            this.errors = {};
            this.props.form = {
                date: new Date().toISOString().slice(0, 10),
                note: "",
                items: [
                    { kitchen_goods_id: null, quantity: "", cost_per_unit: 0, unit_id: null, reason: "Damaged" }
                ]
            };
        },
        reset: function () {
            this.errors = {};
            appService.sideDrawerHide();
            this.$store.dispatch('wastage/reset').then().catch();
            this.props.form = {
                date: new Date().toISOString().slice(0, 10),
                note: "",
                items: [
                    { kitchen_goods_id: null, quantity: "", cost_per_unit: 0, unit_id: null, reason: "Damaged" }
                ]
            };
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('wastage/save', this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.successItem(this.$t('message.save_success') || 'Wastage recorded successfully');
                    this.props.form = {
                        date: new Date().toISOString().slice(0, 10),
                        note: "",
                        items: [
                            { kitchen_goods_id: null, quantity: "", cost_per_unit: 0, unit_id: null, reason: "Damaged" }
                        ]
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                    if (err.response?.data?.message) {
                        alertService.error(err.response.data.message);
                    }
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>
