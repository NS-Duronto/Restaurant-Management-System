<template>
    <LoadingComponent :props="loading" />
    <div class="mb-9">
        <div class="flex items-center justify-between mb-6">
            <h4 class="font-bold text-[24px] leading-[34px] capitalize text-heading dark:text-gray-100">{{ $t("menu.overview") }}</h4>
            <div class="relative cursor-pointer custom-datepicker">
                <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                    @update:modelValue="handleDate" v-model="date" range :preset-ranges="presetRanges">
                    <template #yearly="{ label, range, presetDateRange }">
                        <span @click="presetDateRange(range)">{{ label }}</span>
                    </template>
                </Datepicker>
            </div>
        </div>
        <div class="row">
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 bg-orange-500 text-white shadow-md shadow-orange-500/20 transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-orange-100">{{ $t('label.total_sales') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ total_sales }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 bg-blue-600 text-white shadow-md shadow-blue-500/20 transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-blue-100">{{ $t('label.total_orders') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ total_orders }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 bg-emerald-600 text-white shadow-md shadow-emerald-500/20 transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-emerald-100">{{ $t('label.total_customers') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ total_customers }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 bg-purple-600 text-white shadow-md shadow-purple-500/20 transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i class="fa-solid fa-burger"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-purple-100">{{ $t('label.total_menu_items') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ total_menu_items }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 bg-red-500 text-white shadow-md shadow-red-500/20 transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i class="fa-solid fa-arrow-trend-down"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-red-100">{{ $t('label.total_expense') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ total_expenses }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-3 mb-4">
                <div class="p-5 rounded-2xl flex items-center gap-4 text-white shadow-md transition-transform hover:-translate-y-1 duration-300"
                    :class="net_profit_raw >= 0 ? 'bg-teal-600 shadow-teal-500/20' : 'bg-amber-600 shadow-amber-500/20'">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm text-white text-2xl shadow-inner">
                        <i :class="net_profit_raw >= 0 ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-triangle-exclamation'"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider opacity-80">{{ $t('label.net_profit') }}</h3>
                        <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ net_profit }}</h4>
                        <span class="text-xs mt-0.5 inline-block px-2 py-0.5 rounded-full bg-white/20" v-if="net_profit">
                            {{ net_profit_raw >= 0 ? '▲ ' + $t('label.profit') : '▼ ' + $t('label.loss') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
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
                    range: [startOfYear(subYears(new Date(), 1)), endOfYear(subYears(new Date(), 1))],
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
            this.totalExpenses();
            this.totalNetProfit();
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