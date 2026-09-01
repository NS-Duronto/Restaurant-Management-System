<template>
    <LoadingComponent :props="loading" />
    <div class="col-12 xl:col-6">
        <div class="db-card h-full">
            <div class="db-card-header flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-chart-area"></i>
                    </div>
                    <div>
                        <h3 class="db-card-title text-base font-bold text-gray-900 dark:text-gray-100">{{ $t('label.sales_summary') }}</h3>
                    </div>
                </div>
                <div id="sales-range" class="cursor-pointer flex items-center gap-2 custom-datepicker">
                    <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                        @update:modelValue="salesSummary" v-model="date" range :preset-ranges="presetRanges">
                        <template #yearly="{ label, range, presetDateRange }">
                            <span @click="presetDateRange(range)">{{ label }}</span>
                        </template>
                    </Datepicker>
                </div>
            </div>
            <div class="db-card-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                    <div class="p-3.5 rounded-xl bg-orange-500/5 dark:bg-orange-500/10 border border-orange-500/15 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-500 text-white flex items-center justify-center text-lg shadow-md shadow-orange-500/25">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t("label.total_sales") }}</span>
                            <h3 class="font-extrabold text-xl leading-tight text-gray-900 dark:text-white mt-0.5">{{ total_sales || defaultZeroAmount }}</h3>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/15 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500 text-white flex items-center justify-center text-lg shadow-md shadow-amber-500/25">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $t("label.avg_sales_per_day") }}</span>
                            <h3 class="font-extrabold text-xl leading-tight text-gray-900 dark:text-white mt-0.5">{{ avg_per_day || defaultZeroAmount }}</h3>
                        </div>
                    </div>
                </div>

                <div id="sales-area-chart" class="min-h-[260px]"></div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { endOfMonth, startOfMonth, subMonths } from 'date-fns';

export default {
    name: "SalesSummaryComponent",
    components: { LoadingComponent, Datepicker },
    data() {
        return {
            loading: {
                isActive: false,
            },
            chartInstance: null,
            date: null,
            first_date: null,
            last_date: null,
            total_sales: null,
            avg_per_day: null,
            presetRanges: [
                { label: 'Today', range: [new Date(), new Date()] },
                { label: 'This month', range: [startOfMonth(new Date()), endOfMonth(new Date())] },
                {
                    label: 'Last month',
                    range: [startOfMonth(subMonths(new Date(), 1)), endOfMonth(subMonths(new Date(), 1))],
                },
            ]
        };
    },
    mounted() {
        const date = new Date();
        const startDate = new Date(date.getFullYear(), date.getMonth(), 1);
        const endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        this.date = [startDate, endDate];
        this.salesSummary();
    },
    computed: {
        setting: function () {
            return this.$store.getters["frontendSetting/lists"];
        },
        defaultZeroAmount: function () {
            return (this.setting?.site_default_currency_symbol || '৳') + '0.00';
        }
    },
    beforeUnmount() {
        if (this.chartInstance) {
            this.chartInstance.destroy();
            this.chartInstance = null;
        }
    },
    methods: {
        salesSummary: function (e) {
            let date = {
                first_date: '',
                last_date: '',
            };
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];
                date.first_date = e[0];
                date.last_date = e[1];
            }

            this.loading.isActive = true;
            this.$store.dispatch("dashboard/salesSummary", date).then((res) => {
                this.total_sales = res.data.data.total_sales;
                this.avg_per_day = res.data.data.avg_per_day;
                const salesData = res.data.data.per_day_sales || [];
                const days = salesData.map((_, i) => `${i + 1}`);
                const currencySymbol = this.setting?.site_default_currency_symbol || '৳';

                let options = {
                    series: [{
                        name: this.$t('label.sales') || 'Sales',
                        data: salesData,
                    }],
                    chart: {
                        type: 'area',
                        height: 260,
                        fontFamily: 'inherit',
                        parentHeightOffset: 0,
                        zoom: { enabled: false },
                        toolbar: { show: false },
                        background: 'transparent'
                    },
                    theme: {
                        mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
                    },
                    xaxis: {
                        categories: days,
                        tooltip: { enabled: false },
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: {
                                colors: '#9CA3AF',
                                fontSize: '11px',
                                fontFamily: 'inherit'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#9CA3AF',
                                fontSize: '11px',
                                fontFamily: 'inherit'
                            },
                            formatter: (val) => `${val}`
                        }
                    },
                    stroke: {
                        width: 3,
                        lineCap: "round",
                        curve: "smooth",
                    },
                    colors: ["#F97316"],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 90, 100],
                            colorStops: [
                                {
                                    offset: 0,
                                    color: '#F97316',
                                    opacity: 0.4
                                },
                                {
                                    offset: 100,
                                    color: '#F97316',
                                    opacity: 0.0
                                }
                            ]
                        }
                    },
                    grid: {
                        borderColor: '#374151',
                        strokeDashArray: 4,
                        opacity: 0.2,
                        xaxis: { lines: { show: false } },
                        yaxis: { lines: { show: true } }
                    },
                    tooltip: {
                        theme: 'dark',
                        style: {
                            fontSize: '12px',
                            fontFamily: 'inherit'
                        },
                        y: {
                            formatter: function (val) {
                                return `${currencySymbol}${val}`;
                            }
                        }
                    },
                    dataLabels: { enabled: false },
                };

                const chartEl = document.querySelector("#sales-area-chart");
                if (chartEl) {
                    if (this.chartInstance) {
                        this.chartInstance.updateOptions(options);
                    } else {
                        this.chartInstance = new ApexCharts(chartEl, options);
                        this.chartInstance.render();
                    }
                }
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    }
}
</script>