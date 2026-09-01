<template>
  <LoadingComponent :props="loading" />
  <div class="col-12 xl:col-6">
    <div class="db-card h-full">
      <div class="db-card-header flex items-center justify-between">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-base">
            <i class="fa-solid fa-chart-column"></i>
          </div>
          <div>
            <h3 class="db-card-title text-base font-bold text-gray-900 dark:text-gray-100">{{ $t('label.order_stats') }}</h3>
          </div>
        </div>
        <div id="customer-range" class="cursor-pointer flex items-center gap-2 custom-datepicker">
          <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false" @update:modelValue="customerStates"
            v-model="date" range :preset-ranges="presetRanges">
            <template #yearly="{ label, range, presetDateRange }">
              <span @click="presetDateRange(range)">{{ label }}</span>
            </template>
          </Datepicker>
        </div>
      </div>
      <div class="db-card-body">
        <div id="customer-column-chart" class="min-h-[290px]"></div>
      </div>
    </div>
  </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { endOfMonth, endOfYear, startOfMonth, startOfYear, subMonths, subYears } from 'date-fns';

export default {
  name: "CustomerStatsComponent",
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
    this.customerStates();
  },
  beforeUnmount() {
    if (this.chartInstance) {
      this.chartInstance.destroy();
      this.chartInstance = null;
    }
  },
  methods: {
    customerStates: function (e) {
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

      this.$store.dispatch("dashboard/customerStates", date).then((res) => {
        let options = {
          series: [{
            name: this.$t('menu.customers') || 'Customers',
            data: res.data.data.total_customers || [],
          }],
          chart: {
            type: 'bar',
            height: 290,
            fontFamily: 'inherit',
            parentHeightOffset: 0,
            zoom: { enabled: false },
            toolbar: { show: false },
            background: 'transparent'
          },
          theme: {
            mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '45%',
              borderRadius: 6,
              borderRadiusApplication: 'end'
            },
          },
          stroke: {
            show: true,
            width: 0,
            colors: ['transparent']
          },
          xaxis: {
            categories: res.data.data.times || [],
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
              formatter: (val) => `${Math.round(val)}`
            }
          },
          fill: {
            type: 'gradient',
            gradient: {
              type: 'vertical',
              shadeIntensity: 1,
              gradientToColors: ['#6366F1'],
              inverseColors: false,
              opacityFrom: 0.95,
              opacityTo: 0.75,
              stops: [0, 100]
            }
          },
          colors: ['#3B82F6'],
          tooltip: {
            theme: 'dark',
            style: {
              fontSize: '12px',
              fontFamily: 'inherit',
            }
          },
          grid: {
            borderColor: '#374151',
            strokeDashArray: 4,
            opacity: 0.2,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } }
          },
          dataLabels: { enabled: false },
        };

        const chartEl = document.querySelector("#customer-column-chart");
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
  },
}
</script>