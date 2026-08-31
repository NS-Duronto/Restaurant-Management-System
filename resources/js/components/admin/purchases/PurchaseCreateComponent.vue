<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer !w-[700px] max-w-full">
        <div class="drawer-dialog !w-[700px] max-w-full">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.purchases') }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <!-- Supplier Select -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="supplier" class="db-field-title required">{{ $t("label.supplier") }}</label>
                            <vue-select class="db-field-control" id="supplier"
                                v-model="props.form.supplier_id" :options="suppliers" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="সাপ্লায়ার নির্বাচন করুন" />
                            <small class="db-field-alert" v-if="errors.supplier_id">{{ errors.supplier_id[0] }}</small>
                        </div>

                        <!-- Date -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="purchase_date" class="db-field-title required">{{ $t("label.purchase_date") }}</label>
                            <input v-model="props.form.date" type="date" id="purchase_date" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.date">{{ errors.date[0] }}</small>
                        </div>

                        <!-- Dynamic Items Section -->
                        <div class="form-col-12 mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="db-field-title !mb-0 required font-bold">কাঁচামাল সামগ্রী (Items to Purchase)</label>
                                <button type="button" @click="addItemRow" class="text-xs font-bold text-orange-500 hover:text-orange-600 flex items-center gap-1 bg-orange-50 dark:bg-gray-800 px-2.5 py-1 rounded-lg border border-orange-200 dark:border-gray-700">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>{{ $t('button.add_item_row') || 'আইটেম যোগ করুন' }}</span>
                                </button>
                            </div>

                            <div class="space-y-2.5">
                                <div v-for="(row, index) in props.form.items" :key="index"
                                    class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 flex flex-wrap sm:flex-nowrap items-center gap-2">
                                    <div class="flex-1 min-w-[180px]">
                                        <vue-select class="db-field-control text-xs"
                                            v-model="row.kitchen_goods_id" :options="kitchenGoodsOptions" label-by="name"
                                            value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                            @update:modelValue="onItemSelect(row)"
                                            placeholder="কাঁচামাল নির্বাচন করুন" />
                                    </div>
                                    <div class="w-24">
                                        <input v-model="row.quantity" type="number" step="0.01" min="0.01"
                                            placeholder="পরিমাণ" class="db-field-control text-xs" required>
                                    </div>
                                    <div class="w-24">
                                        <input v-model="row.unit_cost" type="number" step="0.01" min="0"
                                            placeholder="দর (৳)" class="db-field-control text-xs" required>
                                    </div>
                                    <div class="w-24 text-right font-bold text-xs text-orange-600 dark:text-orange-400">
                                        ৳{{ ((Number(row.quantity) || 0) * (Number(row.unit_cost) || 0)).toFixed(2) }}
                                    </div>
                                    <button type="button" @click="removeItemRow(index)" v-if="props.form.items.length > 1"
                                        class="text-gray-400 hover:text-red-500 p-1 text-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Summary & Notes -->
                        <div class="form-col-12 sm:form-col-6 mt-3">
                            <label for="payment_status" class="db-field-title">{{ $t('label.payment_status') || 'পেমেন্ট স্ট্যাটাস' }}</label>
                            <select v-model="props.form.payment_status" id="payment_status" class="db-field-control">
                                <option :value="1">PAID (পরিশোধিত)</option>
                                <option :value="2">UNPAID (বাকি)</option>
                            </select>
                        </div>

                        <div class="form-col-12 sm:form-col-6 mt-3">
                            <label class="db-field-title font-bold text-gray-700 dark:text-gray-300">সর্বমোট পারচেজ বিল (Total Bill)</label>
                            <div class="h-10 rounded-xl bg-orange-50 dark:bg-gray-800 border border-orange-500/30 flex items-center px-4 font-black text-orange-600 dark:text-orange-400 text-lg">
                                ৳ {{ totalPurchaseAmount.toFixed(2) }}
                            </div>
                        </div>

                        <div class="form-col-12 mt-2">
                            <label for="note" class="db-field-title">{{ $t("label.note") }}</label>
                            <textarea v-model="props.form.note" id="note" class="db-field-control" placeholder="পারচেজ ভাউচার নোট..."></textarea>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("label.save") }}</span>
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
    name: "PurchaseCreateComponent",
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
        addButton: function () {
            return { title: this.$t('button.add_purchase') || 'মাল ক্রয় এন্ট্রি' };
        },
        suppliers: function () {
            return this.$store.getters['supplier/lists'] || [];
        },
        kitchenGoods: function () {
            return this.$store.getters['kitchenGoods/lists'] || [];
        },
        kitchenGoodsOptions: function () {
            const list = this.$store.getters['kitchenGoods/lists'] || [];
            return list.map(item => ({
                id: item.id,
                name: `${item.name} (${Number(item.current_stock || item.stock_quantity || 0).toFixed(2)} ${item.unit_name || item.unit_code || ''})`,
                cost_per_unit: item.cost_per_unit
            }));
        },
        totalPurchaseAmount: function () {
            if (!this.props.form.items || !this.props.form.items.length) return 0;
            return this.props.form.items.reduce((acc, row) => {
                return acc + ((Number(row.quantity) || 0) * (Number(row.unit_cost) || 0));
            }, 0);
        }
    },
    mounted() {
        this.$store.dispatch('supplier/lists', { paginate: 0 }).then().catch();
        this.$store.dispatch('kitchenGoods/lists', { paginate: 0 }).then().catch();
    },
    methods: {
        addItemRow: function () {
            this.props.form.items.push({
                kitchen_goods_id: null,
                quantity: "",
                unit_cost: "",
            });
        },
        removeItemRow: function (index) {
            this.props.form.items.splice(index, 1);
        },
        onItemSelect: function (row) {
            if (!row.kitchen_goods_id) return;
            const item = this.kitchenGoods.find(k => k.id === row.kitchen_goods_id);
            if (item && item.cost_per_unit) {
                row.unit_cost = item.cost_per_unit;
            }
        },
        addReset: function () {
            this.errors = {};
            this.props.form = {
                supplier_id: null,
                date: new Date().toISOString().slice(0, 10),
                payment_status: 1,
                note: "",
                items: [
                    { kitchen_goods_id: null, quantity: "", unit_cost: "" }
                ]
            };
        },
        reset: function () {
            this.errors = {};
            appService.sideDrawerHide();
            this.props.form = {
                supplier_id: null,
                date: new Date().toISOString().slice(0, 10),
                payment_status: 1,
                note: "",
                items: [
                    { kitchen_goods_id: null, quantity: "", unit_cost: "" }
                ]
            };
        },
        save: function () {
            try {
                if (!this.props.form.items || !this.props.form.items.length) {
                    return alertService.error("কমপক্ষে একটি আইটেম যোগ করুন");
                }
                this.loading.isActive = true;
                this.$store.dispatch('purchase/save', this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.success(this.$t('menu.purchases') + " সফলভাবে তৈরি ও স্টকে যোগ হয়েছে!");
                    this.$store.dispatch('kitchenGoods/lists', { paginate: 0 }).then().catch();
                    this.addReset();
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                    alertService.error(err.response?.data?.message || 'Error saving purchase');
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>
