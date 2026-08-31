<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t('menu.profit_loss_report') }}</h3>
                <div class="db-card-filter">
                    <div class="relative cursor-pointer custom-datepicker">
                        <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                            @update:modelValue="handleDate" v-model="date" range :preset-ranges="presetRanges">
                            <template #yearly="{ label, range, presetDateRange }">
                                <span @click="presetDateRange(range)">{{ label }}</span>
                            </template>
                        </Datepicker>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="p-5">
                <div class="row mb-6">
                    <div class="col-12 sm:col-6 xl:col-4 mb-4">
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-transform hover:-translate-y-1 duration-300">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 text-white text-2xl shadow-inner">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-emerald-100">{{ $t('label.total_income') || 'মোট আয়' }}</h3>
                                    <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ summary.currency_total_income || '৳0.00' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 xl:col-4 mb-4">
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg shadow-red-500/20 transition-transform hover:-translate-y-1 duration-300">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 text-white text-2xl shadow-inner">
                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-red-100">{{ $t('label.total_expense') || 'মোট ব্যয়' }}</h3>
                                    <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ summary.currency_total_expense || '৳0.00' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 xl:col-4 mb-4">
                        <div class="p-5 rounded-2xl text-white shadow-lg transition-transform hover:-translate-y-1 duration-300"
                            :class="summary.net_profit >= 0 ? 'bg-gradient-to-br from-blue-600 to-indigo-600 shadow-blue-500/20' : 'bg-gradient-to-br from-orange-500 to-red-500 shadow-orange-500/20'">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/20 text-white text-2xl shadow-inner">
                                    <i :class="summary.net_profit >= 0 ? 'fa-solid fa-scale-balanced' : 'fa-solid fa-triangle-exclamation'"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold uppercase tracking-wider opacity-80">{{ $t('label.net_profit') || 'নিট লাভ' }}</h3>
                                    <h4 class="font-bold text-2xl leading-tight text-white mt-1">{{ summary.currency_net_profit || '৳0.00' }}</h4>
                                    <span v-if="summary.net_profit !== undefined" class="text-xs font-medium mt-1 inline-block px-2 py-0.5 rounded-full" :class="summary.net_profit >= 0 ? 'bg-white/20 text-white' : 'bg-white/20 text-white'">
                                        {{ summary.net_profit >= 0 ? '▲ লাভ' : '▼ ক্ষতি' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5" v-if="chartReady">
                    <h4 class="font-semibold text-base text-heading dark:text-gray-100 mb-4">
                        <i class="fa-solid fa-chart-area text-orange-500 mr-2"></i>
                        {{ $t('label.daily_breakdown') || 'দৈনিক আয়-ব্যয় বিশ্লেষণ' }}
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-3 font-semibold text-gray-600 dark:text-gray-400">{{ $t('label.date') || 'তারিখ' }}</th>
                                    <th class="text-right py-3 px-3 font-semibold text-emerald-600 dark:text-emerald-400">{{ $t('label.income') || 'আয়' }}</th>
                                    <th class="text-right py-3 px-3 font-semibold text-red-600 dark:text-red-400">{{ $t('label.expense') || 'ব্যয়' }}</th>
                                    <th class="text-right py-3 px-3 font-semibold text-blue-600 dark:text-blue-400">{{ $t('label.profit') || 'লাভ/ক্ষতি' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(date, index) in summary.dates" :key="index" 
                                    class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="py-2.5 px-3 text-gray-700 dark:text-gray-300 font-medium">{{ formatDate(date) }}</td>
                                    <td class="py-2.5 px-3 text-right text-emerald-600 dark:text-emerald-400 font-medium">
                                        {{ currencyFormat(summary.income_per_day[index]) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right text-red-500 dark:text-red-400 font-medium">
                                        {{ currencyFormat(summary.expense_per_day[index]) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-bold"
                                        :class="summary.profit_per_day[index] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-500 dark:text-orange-400'">
                                        {{ currencyFormat(summary.profit_per_day[index]) }}
                                        <i class="ml-1 text-xs" :class="summary.profit_per_day[index] >= 0 ? 'fa-solid fa-caret-up text-emerald-500' : 'fa-solid fa-caret-down text-red-500'"></i>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800/50">
                                    <td class="py-3 px-3 font-bold text-gray-800 dark:text-gray-200">{{ $t('label.total') || 'মোট' }}</td>
                                    <td class="py-3 px-3 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ summary.currency_total_income }}</td>
                                    <td class="py-3 px-3 text-right font-bold text-red-500 dark:text-red-400">{{ summary.currency_total_expense }}</td>
                                    <td class="py-3 px-3 text-right font-bold" :class="summary.net_profit >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-500 dark:text-orange-400'">
                                        {{ summary.currency_net_profit }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!chartReady && !loading.isActive" class="text-center py-16">
                    <i class="fa-solid fa-chart-pie text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $t('message.no_data_available') || 'কোনো ডাটা পাওয়া যায়নি' }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { startOfMonth, endOfMonth, startOfYear, endOfYear, subMonths, subYears } from "date-fns";

export default {
    name: "ProfitLossReportListComponent",
    components: { Datepicker },
    data() {
        return {
            loading: { isActive: false },
            date: null,
            first_date: null,
            last_date: null,
            summary: {},
            chartReady: false,
            presetRanges: [
                { label: "Today", range: [new Date(), new Date()] },
                { label: "This month", range: [startOfMonth(new Date()), endOfMonth(new Date())] },
                { label: "Last month", range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))] },
                { label: "This year", range: [startOfYear(new Date()), endOfYear(new Date())] },
                { label: "Last year", range: [startOfYear(subYears(new Date(), 1)), endOfYear(subYears(new Date(), 1))] },
            ],
        };
    },
    mounted() {
        const date = new Date();
        const startDate = new Date(date.getFullYear(), date.getMonth(), 1);
        const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        this.date = [startDate, endDate];
        this.loadReport();
    },
    methods: {
        handleDate(e) {
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];
            } else {
                this.first_date = null;
                this.last_date = null;
            }
            this.loadReport();
        },
        loadReport() {
            this.loading.isActive = true;
            this.chartReady = false;
            this.$store.dispatch("dashboard/profitSummary", {
                first_date: this.first_date,
                last_date: this.last_date,
            }).then((res) => {
                this.summary = res.data.data || {};
                this.chartReady = !!(this.summary.dates && this.summary.dates.length > 0);
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('bn-BD', { day: '2-digit', month: 'short', year: 'numeric' });
        },
        currencyFormat(value) {
            if (value === undefined || value === null) return '৳0.00';
            return '৳' + parseFloat(value).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
    },
};
</script>
