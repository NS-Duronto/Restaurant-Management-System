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
            <div class="col-12 sm:col-6 xl:col-3">
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
            <div class="col-12 sm:col-6 xl:col-3">
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
            <div class="col-12 sm:col-6 xl:col-3">
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
            <div class="col-12 sm:col-6 xl:col-3">
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
        this.totalSales();
        this.totalOrders();
        this.totalCustomers();
        this.totalMenuItems();
    },
    methods: {
        handleDate: function (e) {
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];
                this.totalSales();
                this.totalOrders();
                this.totalCustomers();
                this.totalMenuItems();
            } else {
                this.first_date = null;
                this.last_date = null;
                this.totalSales();
                this.totalOrders();
                this.totalCustomers();
                this.totalMenuItems();
            }
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
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalOrders", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_orders = res.data.data.total_orders;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalCustomers: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalCustomers", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_customers = res.data.data.total_customers;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        totalMenuItems: function () {
            this.loading.isActive = true;
            this.$store.dispatch("dashboard/totalMenuItems", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.total_menu_items = res.data.data.total_menu_items;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>