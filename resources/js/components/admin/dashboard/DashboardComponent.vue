<template>
    <LoadingComponent :props="loading" />
    <div v-if="demo === 'true' || demo === 'TRUE' || demo === 'True' || demo === '1' || demo === 1"
        class="mb-4 bg-red-100 p-2 pl-4  rounded">
        <h2 class="mb-1">{{ $t('label.reminder') }}</h2>
        <p>{{ $t('label.data_reset') }}</p>
    </div>

    <div class="mb-8">
        <h3 class="capitalize font-bold text-2xl text-primary mb-1.5">{{ visitorMessage() }}</h3>
        <div class="flex items-center justify-between">
            <h4 class="text-xl font-medium capitalize text-secondary">{{ authInfo.name }}</h4>
            <span class="inline-flex items-center px-2 text-xl font-semibold border rounded-full border-primary/20 bg-primary/10 text-primary">
                {{ $t('label.version') }} : {{ setting.app_version }}
            </span>
        </div>
    </div>
    <!--========OVERVIEW START=============-->
    <OverviewComponent />
    <!--========OVERVIEW END=============-->
    <div class="row">
        <!--========SALES SUMMARY START=============-->
        <SalesSummaryComponent />
        <!--========SALES SUMMARY END=============-->

        <!--========CUSTOMER STATS START=============-->
        <CustomerStatsComponent />
        <!--========CUSTOMER STATS END=============-->

        <!--========FEATURED ITEMS START=============-->
        <FeaturedItemsComponent />
        <!--========FEATURED ITEMS END=============-->

        <!--========MOST POPULAR ITEMS START=============-->
        <MostPopularItemsComponent />
        <!--========MOST POPULAR ITEMS END=============-->
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import OverviewComponent from "./OverviewComponent";
import FeaturedItemsComponent from "./FeaturedItemsComponent";
import MostPopularItemsComponent from "./MostPopularItemsComponent";
import SalesSummaryComponent from "./SalesSummaryComponent";
import CustomerStatsComponent from "./CustomerStatsComponent";
import ENV from "../../../config/env";

export default {
    name: "DashboardComponent",
    components: {
        LoadingComponent,
        OverviewComponent,
        FeaturedItemsComponent,
        MostPopularItemsComponent,
        SalesSummaryComponent,
        CustomerStatsComponent
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
