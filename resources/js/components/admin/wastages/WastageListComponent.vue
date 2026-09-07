<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title flex items-center gap-2">
                    <i class="fa-solid fa-trash-can-arrow-up text-red-600"></i>
                    <span>{{ $t('menu.wastages') || 'অপচয় ও নষ্ট মাল ট্র্যাকিং (Wastage & Spoilage)' }}</span>
                </h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <WastageCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div flex flex-wrap items-center justify-between gap-3">
                    <div class="form-group flex-1 min-w-[200px]">
                        <input v-model="props.search.wastage_no" @input="list(1)" type="text" class="db-field-control"
                            :placeholder="$t('label.search_wastage_voucher') || 'ভাউচার নং দিয়ে খুঁজুন...'">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.voucher_no') || 'ভাউচার নং' }}</th>
                                <th class="db-table-head-th">{{ $t('label.date') || 'তারিখ' }}</th>
                                <th class="db-table-head-th">{{ $t('label.total_items') || 'মোট আইটেম' }}</th>
                                <th class="db-table-head-th">{{ $t('label.total_loss_amount') || 'মোট আর্থিক ক্ষতি' }}</th>
                                <th class="db-table-head-th">{{ $t('label.logged_by') || 'এন্ট্রি করেছেন' }}</th>
                                <th class="db-table-head-th">{{ $t('label.note') || 'মন্তব্য' }}</th>
                                <th class="db-table-head-th">{{ $t('label.action') || 'অ্যাকশন' }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="wastages.length > 0">
                            <tr class="db-table-body-tr cursor-pointer" v-for="item in wastages" :key="item.id" @click="showDetails(item.id)">
                                <td class="db-table-body-td font-bold text-red-600 dark:text-red-400">#{{ item.wastage_no }}</td>
                                <td class="db-table-body-td">{{ item.date }}</td>
                                <td class="db-table-body-td font-semibold">{{ item.total_items }} {{ $t('label.items') || 'টি' }}</td>
                                <td class="db-table-body-td font-bold text-red-600 dark:text-red-400">
                                    {{ currencyFormat(item.total_loss_amount) }}
                                </td>
                                <td class="db-table-body-td font-medium">{{ item.user_name || 'Staff' }}</td>
                                <td class="db-table-body-td">{{ item.note || '-' }}</td>
                                <td class="db-table-body-td" @click.stop>
                                    <div class="flex items-center gap-2">
                                        <button @click.prevent="showDetails(item.id)" class="db-table-action view" title="View Voucher">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button @click.prevent="destroy(item.id)" class="db-table-action delete" title="Delete Voucher">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="wastages.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>

    <WastageShowComponent :wastage="activeWastage" />
</template>

<script>
import WastageCreateComponent from "./WastageCreateComponent.vue";
import WastageShowComponent from "./WastageShowComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "WastageListComponent",
    components: {
        WastageCreateComponent,
        WastageShowComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            activeWastage: {},
            props: {
                form: {
                    date: new Date().toISOString().slice(0, 10),
                    note: "",
                    items: [
                        { kitchen_goods_id: null, quantity: "", cost_per_unit: 0, unit_id: null, reason: "Damaged" }
                    ]
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    wastage_no: "",
                }
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
        },
        wastages: function () {
            return this.$store.getters['wastage/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['wastage/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['wastage/page'];
        }
    },
    mounted() {
        this.list();
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
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('wastage/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        showDetails: function (id) {
            this.loading.isActive = true;
            this.$store.dispatch('wastage/show', id).then((res) => {
                this.loading.isActive = false;
                this.activeWastage = res.data.data;
                appService.modalShow('#wastageDetailsModal');
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err);
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('wastage/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting item');
                    });
                }
            }).catch(() => {});
        }
    }
}
</script>
