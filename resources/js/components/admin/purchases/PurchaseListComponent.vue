<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('menu.purchases') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <PurchaseCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div">
                    <div class="form-group">
                        <input v-model="props.search.reference_no" @input="list" type="text" class="db-field-control" placeholder="ভাউচার নং দিয়ে খুঁজুন...">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.reference_no') || 'ভাউচার নং' }}</th>
                                <th class="db-table-head-th">{{ $t('label.date') }}</th>
                                <th class="db-table-head-th">{{ $t('label.supplier') }}</th>
                                <th class="db-table-head-th">{{ $t('label.total') }}</th>
                                <th class="db-table-head-th">{{ $t('label.payment_status') || 'পেমেন্ট' }}</th>
                                <th class="db-table-head-th">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="purchases.length > 0">
                            <tr class="db-table-body-tr" v-for="item in purchases" :key="item.id">
                                <td class="db-table-body-td font-bold text-orange-600 dark:text-orange-400">#{{ item.purchase_no }}</td>
                                <td class="db-table-body-td">{{ item.date }}</td>
                                <td class="db-table-body-td font-semibold">{{ item.supplier_name || '-' }}</td>
                                <td class="db-table-body-td font-bold">
                                    {{ currencyFormat(item.total_amount, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                                </td>
                                <td class="db-table-body-td">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold"
                                        :class="item.payment_status === 1 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400'">
                                        {{ item.payment_status === 1 ? 'PAID' : 'UNPAID' }}
                                    </span>
                                </td>
                                <td class="db-table-body-td">
                                    <div class="flex items-center gap-2">
                                        <button @click.prevent="destroy(item.id)" class="db-table-action delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="purchases.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PurchaseCreateComponent from "./PurchaseCreateComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "PurchaseListComponent",
    components: {
        PurchaseCreateComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            props: {
                form: {
                    supplier_id: null,
                    date: new Date().toISOString().slice(0, 10),
                    payment_status: 1,
                    note: "",
                    items: [
                        { kitchen_goods_id: null, quantity: "", unit_cost: "" }
                    ]
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    reference_no: "",
                }
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        purchases: function () {
            return this.$store.getters['purchase/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['purchase/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['purchase/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('purchase/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('purchase/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting purchase');
                    });
                }
            }).catch();
        }
    }
}
</script>
