<template>
    <LoadingComponent :props="loading" />

    <!--========HEADER & QUICK ACTIONS START=============-->
    <div class="mt-6 mb-8 p-6 rounded-3xl bg-white dark:bg-gradient-to-r dark:from-gray-900 dark:via-gray-900 dark:to-gray-800/90 border border-gray-200/80 dark:border-gray-800/80 shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                {{ visitorMessage() }} <span class="text-orange-500 dark:text-orange-400">{{ authInfo.name }}</span>
            </h2>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 relative z-10 w-full lg:w-auto">
            <router-link :to="{ name: 'admin.pos' }"
                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white shadow-lg shadow-orange-500/25 transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-cash-register"></i>
                <span>{{ $t('menu.pos') }}</span>
            </router-link>

            <router-link :to="{ name: 'admin.table.order' }"
                class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/90 dark:hover:bg-gray-700/90 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 shadow-sm transition-all">
                <i class="fa-solid fa-chair text-orange-500"></i>
                <span>{{ $t('menu.table_orders') }}</span>
            </router-link>

            <router-link :to="{ name: 'admin.kitchen-display-system' }"
                class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/90 dark:hover:bg-gray-700/90 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 shadow-sm transition-all">
                <i class="fa-solid fa-fire-burner text-amber-500"></i>
                <span>{{ $t('menu.k_d_s') }}</span>
            </router-link>

            <router-link :to="{ name: 'admin.expenses' }"
                class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 rounded-xl font-semibold text-xs sm:text-sm bg-gray-50 hover:bg-gray-100 dark:bg-gray-800/90 dark:hover:bg-gray-700/90 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 shadow-sm transition-all">
                <i class="fa-solid fa-wallet text-rose-500"></i>
                <span>{{ $t('menu.expenses') }}</span>
            </router-link>
        </div>
    </div>
    <!--========HEADER & QUICK ACTIONS END=============-->

    <!--========OVERVIEW START=============-->
    <OverviewComponent />
    <!--========OVERVIEW END=============-->

    <!--========ANALYTICS & CHARTS START=============-->
    <div class="row g-4 mb-8">
        <SalesSummaryComponent />
        <CustomerStatsComponent />
    </div>
    <!--========ANALYTICS & CHARTS END=============-->

    <!--========OPERATIONS & POPULAR ITEMS START=============-->
    <div class="row g-4 mb-8">
        <RecentOrdersComponent />
        <MostPopularItemsComponent />
    </div>
    <!--========OPERATIONS & POPULAR ITEMS END=============-->

    <!--========FEATURED ITEMS START=============-->
    <div class="row g-4">
        <FeaturedItemsComponent />
    </div>
    <!--========FEATURED ITEMS END=============-->
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import OverviewComponent from "./OverviewComponent";
import FeaturedItemsComponent from "./FeaturedItemsComponent";
import MostPopularItemsComponent from "./MostPopularItemsComponent";
import SalesSummaryComponent from "./SalesSummaryComponent";
import CustomerStatsComponent from "./CustomerStatsComponent";
import RecentOrdersComponent from "./RecentOrdersComponent";
import ENV from "../../../config/env";

export default {
    name: "DashboardComponent",
    components: {
        LoadingComponent,
        OverviewComponent,
        FeaturedItemsComponent,
        MostPopularItemsComponent,
        SalesSummaryComponent,
        CustomerStatsComponent,
        RecentOrdersComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            demo: ENV.DEMO
        };
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendSetting/lists").then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    computed: {
        authInfo: function () {
            return this.$store.getters.authInfo;
        },
        setting: function () {
            return this.$store.getters["frontendSetting/lists"];
        },
    },
    methods: {
        visitorMessage: function () {
            let greet;
            let myDate = new Date();
            let hrs = myDate.getHours();
            if (hrs < 12) {
                greet = this.$t('message.good_morning');
            } else if (hrs >= 12 && hrs <= 17) {
                greet = this.$t('message.good_afternoon');
            } else if (hrs >= 17 && hrs <= 24) {
                greet = this.$t('message.good_evening');
            }
            return greet;
        }
    }
}
</script>
