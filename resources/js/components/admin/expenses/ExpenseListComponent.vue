<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('menu.expenses') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <ExpenseCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div">
                    <div class="form-group">
                        <input v-model="props.search.description" @input="list" type="text" class="db-field-control" placeholder="খরচের বিবরণ দিয়ে খুঁজুন...">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.date') }}</th>
                                <th class="db-table-head-th">{{ $t('label.category') }}</th>
                                <th class="db-table-head-th">{{ $t('label.amount') }}</th>
                                <th class="db-table-head-th">{{ $t('label.payment_method') }}</th>
                                <th class="db-table-head-th">{{ $t('label.description') }}</th>
                                <th class="db-table-head-th">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="expenses.length > 0">
                            <tr class="db-table-body-tr" v-for="item in expenses" :key="item.id">
                                <td class="db-table-body-td">{{ item.date }}</td>
                                <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-200">{{ item.category_name || item.expense_category_name || '-' }}</td>
                                <td class="db-table-body-td font-bold text-red-600 dark:text-red-400">
                                    {{ currencyFormat(item.amount, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                                </td>
                                <td class="db-table-body-td">{{ paymentMethodMap[item.payment_method] || 'Cash' }}</td>
                                <td class="db-table-body-td">{{ item.note || item.description || '-' }}</td>
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

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="expenses.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ExpenseCreateComponent from "./ExpenseCreateComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "ExpenseListComponent",
    components: {
        ExpenseCreateComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            paymentMethodMap: {
                1: 'Cash',
                2: 'Bank',
                3: 'bKash / Nagad',
                4: 'Other'
            },
            props: {
                form: {
                    expense_category_id: null,
                    date: new Date().toISOString().slice(0, 10),
                    amount: "",
                    payment_method: "Cash",
                    description: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    description: "",
                }
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        expenses: function () {
            return this.$store.getters['expense/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['expense/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['expense/page'];
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
            this.$store.dispatch('expense/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('expense/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting expense');
                    });
                }
            }).catch();
        }
    }
}
</script>
