<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer !w-[700px] max-w-full">
        <div class="drawer-dialog !w-[700px] max-w-full">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.send_to_kitchen') }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <!-- Issue Date -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="issue_date" class="db-field-title required">{{ $t("label.issue_date") || 'ইস্যুর তারিখ' }}</label>
                            <input v-model="props.form.date" type="date" id="issue_date" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.date">{{ errors.date[0] }}</small>
                        </div>

                        <!-- Dynamic Items to Issue -->
                        <div class="form-col-12 mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <label class="db-field-title !mb-0 required font-bold">কিচেনে পাঠানোর আইটেম (Items to Kitchen)</label>
                                <button type="button" @click="addItemRow" class="text-xs font-bold text-orange-500 hover:text-orange-600 flex items-center gap-1 bg-orange-50 dark:bg-gray-800 px-2.5 py-1 rounded-lg border border-orange-200 dark:border-gray-700">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                    <span>{{ $t('button.add_item_row') || 'আইটেম যোগ করুন' }}</span>
                                </button>
                            </div>

                            <div class="space-y-2 max-h-[280px] overflow-y-auto pr-1">
                                <div v-for="(row, index) in props.form.items" :key="index"
                                    class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 flex items-center gap-2">
                                    <div class="flex-1">
                                        <vue-select class="db-field-control text-xs"
                                            v-model="row.kitchen_goods_id" :options="kitchenGoods" label-by="name"
                                            value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                            placeholder="কাঁচামাল নির্বাচন" />
                                    </div>
                                    <div class="w-32">
                                        <input v-model="row.quantity" type="number" step="0.01" min="0.01"
                                            placeholder="ইস্যুর পরিমাণ" class="db-field-control text-xs" required>
                                    </div>
                                    <div class="w-32 text-xs text-gray-500 dark:text-gray-400 font-semibold" v-if="getStock(row.kitchen_goods_id) !== null">
                                        স্টক: <span class="text-orange-500 font-bold">{{ getStock(row.kitchen_goods_id) }}</span>
                                    </div>
                                    <button type="button" @click="removeItemRow(index)" v-if="props.form.items.length > 1"
                                        class="text-gray-400 hover:text-red-500 p-1 text-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="form-col-12 mt-3">
                            <label for="note" class="db-field-title">{{ $t("label.note") }}</label>
                            <textarea v-model="props.form.note" id="note" class="db-field-control" placeholder="যেমন: দুপুরের বিরিয়ানি ব্যাচ..."></textarea>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="fa-solid fa-paper-plane mr-1"></i>
                                    <span>{{ $t("button.send_to_kitchen") || 'কিচেনে পাঠান' }}</span>
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
    name: "SendToKitchenCreateComponent",
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
            return { title: this.$t('button.send_to_kitchen') || 'কিচেনে মাল পাঠান' };
        },
        kitchenGoods: function () {
            return this.$store.getters['kitchenGoods/lists'] || [];
        },
    },
    mounted() {
        this.$store.dispatch('kitchenGoods/lists', { paginate: 0 }).then().catch();
    },
    methods: {
        getStock: function (goodsId) {
            if (!goodsId) return null;
            const item = this.kitchenGoods.find(k => k.id === goodsId);
            return item ? Number(item.current_stock).toFixed(2) + ' ' + (item.unit_code || '') : null;
        },
        addItemRow: function () {
            this.props.form.items.push({
                kitchen_goods_id: null,
                quantity: "",
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
                    { kitchen_goods_id: null, quantity: "" }
                ]
            };
        },
        reset: function () {
            this.errors = {};
            appService.sideDrawerHide();
            this.props.form = {
                date: new Date().toISOString().slice(0, 10),
                note: "",
                items: [
                    { kitchen_goods_id: null, quantity: "" }
                ]
            };
        },
        save: function () {
            try {
                if (!this.props.form.items || !this.props.form.items.length) {
                    return alertService.error("কমপক্ষে একটি আইটেম যোগ করুন");
                }
                this.loading.isActive = true;
                this.$store.dispatch('sendToKitchen/save', this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.success("কিচেনে মালামাল পাঠানো হয়েছে ও স্টক থেকে কাটা হয়েছে!");
                    this.$store.dispatch('kitchenGoods/lists', { paginate: 0 }).then().catch();
                    this.addReset();
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                    alertService.error(err.response?.data?.message || 'Error issuing items');
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>
