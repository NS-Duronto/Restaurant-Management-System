<template>
    <div class="col-12 xl:col-6">
        <div class="db-card h-full flex flex-col justify-between">
            <div>
                <div class="db-card-header flex items-center justify-between pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center text-base">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="db-card-title text-base font-bold text-gray-900 dark:text-gray-100">{{ $t('menu.pos_orders') }}</h3>
                    </div>
                    <router-link :to="{ name: 'admin.pos.orders' }" class="text-xs font-semibold text-orange-500 hover:text-orange-600 flex items-center gap-1 transition">
                        <span>{{ $t('button.see_all') }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </router-link>
                </div>

                <div class="db-card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm" v-if="orders.length > 0">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800/80 text-[11px] uppercase tracking-wider text-gray-400">
                                    <th class="py-3 px-4">{{ $t('label.order_id') }}</th>
                                    <th class="py-3 px-3">{{ $t('label.customer') }}</th>
                                    <th class="py-3 px-3">{{ $t('label.amount') }}</th>
                                    <th class="py-3 px-3">{{ $t('label.status') }}</th>
                                    <th class="py-3 px-4 text-right">{{ $t('label.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                                <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors">
                                    <td class="py-3 px-4 font-semibold text-gray-900 dark:text-white">
                                        <span class="text-xs text-orange-500 font-mono">#{{ order.order_serial_no }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-xs text-gray-700 dark:text-gray-300 font-medium truncate max-w-[120px]">
                                        {{ order.customer_name || $t('label.walking_customer') }}
                                    </td>
                                    <td class="py-3 px-3 text-xs font-bold text-gray-900 dark:text-orange-400">
                                        {{ order.total_amount_price }}
                                    </td>
                                    <td class="py-3 px-3 text-xs">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                            :class="orderStatusClass(order.status)">
                                            <span class="w-1.5 h-1.5 rounded-full" :class="statusDotClass(order.status)"></span>
                                            {{ enums.orderStatusEnumArray[order.status] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <router-link :to="{ name: 'admin.pos.orders.show', params: { id: order.id } }"
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-orange-500 hover:bg-orange-50 dark:hover:bg-orange-500/20 transition">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-else class="text-center py-8 text-gray-400 text-xs">
                            <i class="fa-solid fa-receipt text-2xl text-gray-300 dark:text-gray-700 mb-2 block"></i>
                            {{ $t('message.no_data_available') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";

export default {
    name: "RecentOrdersComponent",
    data() {
        return {
            loading: false,
            orders: [],
            enums: {
                orderStatusEnum: orderStatusEnum,
                orderTypeEnum: orderTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PROCESSING]: this.$t("label.processing"),
                    [orderStatusEnum.PREPARING]: this.$t("label.preparing"),
                    [orderStatusEnum.PREPARED]: this.$t("label.prepared"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                },
            }
        };
    },
    mounted() {
        this.fetchRecentOrders();
    },
    methods: {
        fetchRecentOrders() {
            this.loading = true;
            this.$store.dispatch("posOrder/lists", {
                paginate: 1,
                page: 1,
                per_page: 5,
                order_column: "id",
                order_type: "desc"
            }).then((res) => {
                this.orders = res.data.data || [];
                this.loading = false;
            }).catch(() => {
                this.loading = false;
            });
        },
        orderStatusClass(status) {
            if (status === orderStatusEnum.DELIVERED) {
                return "bg-emerald-500/10 text-emerald-500 dark:text-emerald-400";
            } else if (status === orderStatusEnum.PREPARING || status === orderStatusEnum.PROCESSING) {
                return "bg-amber-500/10 text-amber-500 dark:text-amber-400";
            } else if (status === orderStatusEnum.PREPARED || status === orderStatusEnum.ACCEPT) {
                return "bg-blue-500/10 text-blue-500 dark:text-blue-400";
            } else if (status === orderStatusEnum.CANCELED || status === orderStatusEnum.REJECTED) {
                return "bg-red-500/10 text-red-500 dark:text-red-400";
            }
            return "bg-gray-500/10 text-gray-500 dark:text-gray-400";
        },
        statusDotClass(status) {
            if (status === orderStatusEnum.DELIVERED) {
                return "bg-emerald-500";
            } else if (status === orderStatusEnum.PREPARING || status === orderStatusEnum.PROCESSING) {
                return "bg-amber-500 animate-pulse";
            } else if (status === orderStatusEnum.PREPARED || status === orderStatusEnum.ACCEPT) {
                return "bg-blue-500";
            } else if (status === orderStatusEnum.CANCELED || status === orderStatusEnum.REJECTED) {
                return "bg-red-500";
            }
            return "bg-gray-400";
        }
    }
};
</script>
