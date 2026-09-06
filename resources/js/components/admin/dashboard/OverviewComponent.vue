<template>
    <LoadingComponent :props="loading" />
    <div class="mb-8">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h4 class="font-bold text-xl leading-tight capitalize text-gray-900 dark:text-gray-100">{{ $t("menu.overview") }}</h4>
            <div class="relative cursor-pointer custom-datepicker">
                <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                    @update:modelValue="handleDate" v-model="date" range :preset-ranges="presetRanges">
                    <template #yearly="{ label, range, presetDateRange }">
                        <span @click="presetDateRange(range)">{{ label }}</span>
                    </template>
                </Datepicker>
            </div>
        </div>
        <div class="row g-4">
            <!-- 1. Total Sales -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4">
                <router-link :to="{ name: 'admin.sales-report.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl hover:border-orange-500/40 transition-all duration-300 group flex items-center justify-between cursor-pointer block">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.total_sales') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-orange-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight text-gray-900 dark:text-white mt-1.5 group-hover:text-orange-500 dark:group-hover:text-orange-400 transition-colors">{{ total_sales || defaultZeroAmount }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-orange-500 to-amber-500 text-white text-2xl shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </router-link>
            </div>

            <!-- 2. Total Orders -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4">
                <router-link :to="{ name: 'admin.pos.orders.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl hover:border-blue-500/40 transition-all duration-300 group flex items-center justify-between cursor-pointer block">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.total_orders') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight text-gray-900 dark:text-white mt-1.5 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors">{{ total_orders !== null ? total_orders : 0 }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-2xl shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </router-link>
            </div>

            <!-- 3. Total Customers -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4">
                <router-link :to="{ name: 'admin.customers.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300 group flex items-center justify-between cursor-pointer block">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.total_customers') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight text-gray-900 dark:text-white mt-1.5 group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">{{ total_customers !== null ? total_customers : 0 }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-2xl shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </router-link>
            </div>

            <!-- 4. Total Menu Items -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4">
                <router-link :to="{ name: 'admin.items.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl hover:border-purple-500/40 transition-all duration-300 group flex items-center justify-between cursor-pointer block">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.total_menu_items') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight text-gray-900 dark:text-white mt-1.5 group-hover:text-purple-500 dark:group-hover:text-purple-400 transition-colors">{{ total_menu_items !== null ? total_menu_items : 0 }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-purple-500 to-violet-600 text-white text-2xl shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-burger"></i>
                    </div>
                </router-link>
            </div>

            <!-- 5. Total Expenses -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4" v-if="permissionChecker('profit-loss-report')">
                <router-link :to="{ name: 'admin.expenses.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl hover:border-rose-500/40 transition-all duration-300 group flex items-center justify-between cursor-pointer block">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.total_expense') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-rose-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight text-gray-900 dark:text-white mt-1.5 group-hover:text-rose-500 dark:group-hover:text-rose-400 transition-colors">{{ total_expenses || defaultZeroAmount }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-gradient-to-br from-rose-500 to-red-600 text-white text-2xl shadow-lg shadow-rose-500/20 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </router-link>
            </div>

            <!-- 6. Net Profit / Loss -->
            <div class="col-12 sm:col-6 xl:col-4 mb-4" v-if="permissionChecker('profit-loss-report')">
                <router-link :to="{ name: 'admin.profit-loss-report.list' }" class="p-5 rounded-2xl bg-white dark:bg-gray-900/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm hover:shadow-xl transition-all duration-300 group flex items-center justify-between cursor-pointer block"
                    :class="net_profit_raw >= 0 ? 'hover:border-emerald-500/40' : 'hover:border-amber-500/40'">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t('label.net_profit') }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide"
                                :class="net_profit_raw >= 0 ? 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 dark:text-rose-400 border border-rose-500/20'">
                                {{ net_profit_raw >= 0 ? '▲ ' + $t('label.profit') : '▼ ' + $t('label.loss') }}
                            </span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-0 group-hover:opacity-100 transition-opacity" :class="net_profit_raw >= 0 ? 'text-emerald-500' : 'text-amber-500'"></i>
                        </div>
                        <h4 class="font-extrabold text-2xl lg:text-[26px] leading-tight mt-1.5 transition-colors"
                            :class="net_profit_raw >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-500 dark:text-amber-400'">
                            {{ net_profit || defaultZeroAmount }}
                        </h4>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg group-hover:scale-110 transition-transform duration-300"
                        :class="net_profit_raw >= 0 ? 'bg-gradient-to-br from-emerald-500 to-teal-600 shadow-emerald-500/20' : 'bg-gradient-to-br from-amber-500 to-rose-600 shadow-amber-500/20'">
                        <i :class="net_profit_raw >= 0 ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-triangle-exclamation'"></i>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import appService from "../../../services/appService";
import { ref } from 'vue';
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths, subYears } from 'date-fns';

export default {
    name: "OverviewComponent",
    components: { LoadingComponent, Datepicker },
    data() {
        return {
            loading: {
                isActive: false,
            },
            date: null,
            first_date: null,
            last_date: null,
            total_sales: null,
            total_orders: null,
            total_customers: null,
            total_menu_items: null,
            total_expenses: null,
            net_profit: null,
            net_profit_raw: 0,
            presetRanges: [
                { label: 'Today', range: [new Date(), new Date()] },
                { label: 'This month', range: [startOfMonth(new Date()), endOfMonth(new Date())] },
                {
                    label: 'Last month',
                    range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
                },
                { label: 'This year', range: [startOfYear(new Date()), endOfYear(new Date())] },
                {
                    label: 'Last year',
                    range: [startOfYear(subYears(new Date(), 1)), endOfYear(subYears(new Date(), 1))]
                },
            ]
        };
    },
    mounted() {
        const date = new Date();
        const startDate = new Date(date.getFullYear(), date.getMonth(), 1);
        const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        this.date = [startDate, endDate];
        this.fetchAll();
    },
    computed: {
        setting: function () {
            return this.$store.getters["frontendSetting/lists"];
        },
        defaultZeroAmount: function () {
            return (this.setting?.site_default_currency_symbol || '৳') + '0.00';
        }
    },
    methods: {
        handleDate: function (e) {
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];
            } else {
                this.first_date = null;
                this.last_date = null;
            }
            this.fetchAll();
        },
        fetchAll() {
            this.totalSales();
            this.totalOrders();
            this.totalCustomers();
            this.totalMenuItems();
            if (this.permissionChecker('profit-loss-report')) {
                this.totalExpenses();
                this.totalNetProfit();
            }
        },
        permissionChecker: function (permissionName) {
            return appService.permissionChecker(permissionName);
        },
        totalSales: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalSales", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_sales = res.data.data.total_sales;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalOrders: function () {
            this.$store.dispatch("dashboard/totalOrders", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_orders = res.data.data.total_orders;
            }).catch(() => {});
        },
        totalCustomers: function () {
            this.$store.dispatch("dashboard/totalCustomers", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_customers = res.data.data.total_customers;
            }).catch(() => {});
        },
        totalMenuItems: function () {
            this.$store.dispatch("dashboard/totalMenuItems", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_menu_items = res.data.data.total_menu_items;
            }).catch(() => {});
        },
        totalExpenses: function () {
            this.$store.dispatch("dashboard/totalExpenses", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_expenses = res.data.data.total_expenses;
            }).catch(() => {});
        },
        totalNetProfit: function () {
            this.$store.dispatch("dashboard/totalNetProfit", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.net_profit = res.data.data.net_profit;
                this.net_profit_raw = res.data.data.raw_profit || 0;
            }).catch(() => {});
        },
    },
}
</script>