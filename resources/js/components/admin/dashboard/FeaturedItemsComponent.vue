<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header flex items-center justify-between pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3 class="db-card-title text-base font-bold text-gray-900 dark:text-gray-100">{{ $t('label.featured_items') }}</h3>
                </div>
                <router-link :to="{ name: 'admin.items' }" class="text-xs font-semibold text-orange-500 hover:text-orange-600 flex items-center gap-1 transition">
                    <span>{{ $t('button.see_all') }}</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </router-link>
            </div>
            <div class="db-card-body">
                <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" v-if="featured_items && featured_items.length > 0">
                    <li class="rounded-2xl border border-gray-200/80 dark:border-gray-800/80 bg-white dark:bg-gray-900/60 overflow-hidden hover:border-orange-500/50 transition-all duration-300 shadow-sm hover:shadow-md group flex flex-col justify-between"
                        v-for="featured_item in featured_items" :key="featured_item.id || featured_item.name">
                        <div class="relative overflow-hidden aspect-[4/3]">
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" :src="featured_item.thumb" :alt="featured_item.name">
                            <span v-if="featured_item.currency_price" class="absolute bottom-2 right-2 bg-gray-900/80 backdrop-blur-md text-white text-[11px] font-bold px-2 py-0.5 rounded-md shadow">
                                {{ featured_item.currency_price }}
                            </span>
                        </div>
                        <div class="p-3">
                            <h4 class="text-xs font-bold capitalize text-gray-900 dark:text-gray-100 group-hover:text-orange-500 transition-colors truncate">{{ featured_item.name }}</h4>
                            <span class="text-[10px] text-gray-400 block truncate mt-0.5" v-if="featured_item.category_name">{{ featured_item.category_name }}</span>
                        </div>
                    </li>
                </ul>

                <div v-else class="text-center py-8 text-gray-400 text-xs">
                    <i class="fa-solid fa-utensils text-2xl text-gray-300 dark:text-gray-700 mb-2 block"></i>
                    {{ $t('message.no_data_available') }}
                </div>
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
            featured_items: [],
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
