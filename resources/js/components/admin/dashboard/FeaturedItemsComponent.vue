<template>
    <LoadingComponent :props="loading" />
    <div class="col-12 xl:col-6">
        <div class="db-card">
            <div class="db-card-header">
                <div class="db-card-title">{{ $t('label.featured_items') }}</div>
            </div>
            <div class="db-card-body">
                <ul class="grid grid-cols-2 sm:grid-cols-4 gap-[18px]">
                    <li class="w-full rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800/40 overflow-hidden hover:border-orange-500/50 transition-colors shadow-sm" v-if="featured_items.length > 0"
                        v-for="featured_item in featured_items" :key="featured_item">
                        <img class="w-full h-24 object-cover rounded-t-2xl" :src="featured_item.thumb" alt="product">
                        <h4 class="text-xs p-2.5 font-bold capitalize text-heading dark:text-gray-100 truncate">{{ featured_item.name }}</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
export default {
    name: "FeaturedItemsComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },

            featured_items: {},
        };
    },
    mounted() {
        this.featuredItems();
    },
    methods: {
        featuredItems: function () {
            this.loading.isActive = true;
            this.$store.dispatch('dashboard/featuredItems').then(res => {
                this.featured_items = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>
