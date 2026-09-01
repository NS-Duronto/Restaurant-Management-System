<template>
    <LoadingComponent :props="loading" />
    <div class="col-12 xl:col-6">
        <div class="db-card h-full flex flex-col justify-between">
            <div>
                <div class="db-card-header flex items-center justify-between pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center text-base">
                            <i class="fa-solid fa-fire"></i>
                        </div>
                        <h3 class="db-card-title text-base font-bold text-gray-900 dark:text-gray-100">{{ $t('label.most_popular_items') }}</h3>
                    </div>
                    <router-link :to="{ name: 'admin.items' }" class="text-xs font-semibold text-orange-500 hover:text-orange-600 flex items-center gap-1 transition">
                        <span>{{ $t('button.see_all') }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </router-link>
                </div>

                <div class="db-card-body p-0">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-800/60" v-if="popular_items && popular_items.length > 0">
                        <li v-for="(popular_item, index) in popular_items" :key="popular_item.id || index"
                            class="p-3.5 flex items-center justify-between hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors rounded-xl group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="relative flex-shrink-0">
                                    <img class="w-12 h-12 rounded-xl object-cover border border-gray-200 dark:border-gray-800" :src="popular_item.thumb" :alt="popular_item.name">
                                    <span class="absolute -top-1.5 -left-1.5 w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-extrabold text-white shadow"
                                        :class="index === 0 ? 'bg-amber-500' : index === 1 ? 'bg-slate-400' : index === 2 ? 'bg-amber-700' : 'bg-gray-700'">
                                        {{ index + 1 }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-orange-500 transition-colors truncate">
                                        {{ popular_item.name }}
                                    </h4>
                                    <span class="inline-block mt-0.5 text-[11px] font-medium text-orange-500 dark:text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-md">
                                        {{ popular_item.category_name }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 pl-3">
                                <span class="font-extrabold text-sm text-gray-900 dark:text-white block">{{ popular_item.currency_price }}</span>
                            </div>
                        </li>
                    </ul>

                    <div v-else class="text-center py-8 text-gray-400 text-xs">
                        <i class="fa-solid fa-burger text-2xl text-gray-300 dark:text-gray-700 mb-2 block"></i>
                        {{ $t('message.no_data_available') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
export default {
    name: "MostPopularItemsComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            popular_items: [],
        };
    },
    mounted() {
        this.popularItems();
    },
    methods: {
        popularItems: function () {
            this.loading.isActive = true;
            this.$store.dispatch('dashboard/mostPopularItems').then(res => {
                this.popular_items = res.data.data;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>
