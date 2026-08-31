<template>
    <section class="col-12 pt-4 pb-4">
        <router-link to="" @click="$router.go(-1)" class="mb-3 inline-flex items-center gap-2 text-primary">
            <i class="lab lab-undo lab-font-size-16"></i>
            <span class="text-xs font-medium leading-6">{{ $t('label.back_to_orders') }}</span>
        </router-link>
        <div class="flex items-start flex-col md:flex-row gap-6">
            <div class="w-full">
                <div class="p-4 mb-6 rounded-2xl shadow-xs bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800">
                    <h3 class="text-sm leading-6 mb-1 font-medium text-heading dark:text-gray-100">{{ $t("label.order_id") }}: <span
                            class="text-primary font-bold">#{{ order.order_serial_no }}</span></h3>
                    <p class="text-xs font-light mb-3 text-gray-500 dark:text-gray-400">{{ order.order_datetime }}</p>
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="text-sm capitalize text-gray-600 dark:text-gray-400">{{ $t("label.order_type") }}:</span>
                        <span class="text-sm capitalize text-heading dark:text-gray-200">
                            {{ enums.orderTypeEnumArray[order.order_type] }}
                        </span>
                    </div>

                    <OrderStatusComponent :props="order" />
                    <div>
                        <h3 class="font-medium mb-2 text-heading dark:text-gray-100">{{ orderBranch.name }}</h3>
                        <div class="flex items-center justify-between gap-5">
                            <div class="flex items-start justify-start gap-2.5">
                                <i class="lab lab-location leading-none mt-1.5 flex-shrink-0 lab-font-size-14 text-primary"></i>
                                <span class="text-sm leading-6 text-heading dark:text-gray-300">{{ orderBranch.address }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" v-if="parseInt(order.status) === parseInt(enums.orderStatusEnum.REJECTED)">
                        <h3 class="capitalize font-medium text-sm leading-6 mb-2 text-red-500">{{ $t("label.reason") }}:</h3>
                        <p class="text-sm text-heading dark:text-gray-200 mb-2">{{ order.reason }}</p>
                    </div>
                </div>
                <div class="p-4 mb-6 rounded-2xl shadow-xs bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800"
                    v-if="orderAddress && order.order_type === enums.orderTypeEnum.DELIVERY">
                    <h3 class="text-sm leading-6 font-medium mb-2 text-heading dark:text-gray-100">{{ $t("label.delivery_address") }}</h3>
                    <div class="flex items-start justify-start gap-2.5">
                        <i class="lab lab-location leading-none mt-1.5 flex-shrink-0 lab-font-size-14 text-primary"></i>
                        <span class="text-sm leading-6 text-heading dark:text-gray-300">
                            {{ orderAddress.apartment ? orderAddress.apartment + ', ' : '' }}
                            {{ orderAddress.address }}
                        </span>
                    </div>
                </div>

                <div v-if="parseInt(order.status) !== parseInt(enums.orderStatusEnum.REJECTED) && parseInt(order.status) !== parseInt(enums.orderStatusEnum.CANCELED)"
                    class="p-4 rounded-2xl shadow-xs bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800">
                    <h3 class="capitalize font-medium text-sm leading-6 mb-2 text-heading dark:text-gray-100">{{ $t("label.payment_info") }}</h3>
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <span class="capitalize text-sm leading-6 text-gray-500 dark:text-gray-400">{{ $t("label.method") }}:</span>
                            <span class="text-sm text-heading dark:text-gray-200" v-if="order.transaction">
                                {{ order.transaction.payment_method }} ({{ order.transaction.transaction_no }})
                            </span>
                            <span class="text-sm text-heading dark:text-gray-200" v-else-if="order.source === enums.orderTypeEnum.POS">
                                {{ enums.posPaymentMethodEnumArray[order.pos_payment_method] ?
                                    enums.posPaymentMethodEnumArray[order.pos_payment_method] :
                                    enums.paymentTypeEnumArray[order.payment_method] }}
                            </span>
                            <span class="text-sm text-heading dark:text-gray-200" v-else>
                                {{ enums.paymentTypeEnumArray[order.payment_method] }}
                            </span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="capitalize text-sm leading-6 text-gray-500 dark:text-gray-400">{{ $t("label.status") }}:</span>
                            <span class="capitalize text-sm leading-6 font-semibold"
                                :class="enums.paymentStatusEnum.PAID === order.payment_status ? 'text-green-600 dark:text-green-400' : 'text-[#FB4E4E] dark:text-red-400'">
                                {{ enums.paymentStatusEnumArray[order.payment_status] }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-full rounded-2xl shadow-xs bg-white dark:bg-gray-900 border border-transparent dark:border-gray-800">
                <div class="p-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-medium text-sm leading-6 capitalize mb-4 text-heading dark:text-gray-100">{{ $t('label.order_details') }}</h3>
                    <div class="pl-3">
                        <div class="mb-3 pb-3 border-b last:mb-0 last:pb-0 last:border-b-0 border-gray-100 dark:border-gray-800"
                            v-if="orderItems.length > 0" v-for="item in orderItems" :key="item">
                            <div class="flex items-center gap-3 relative">
                                <h3
                                    class="absolute top-5 -left-3 text-sm w-[26px] h-[26px] leading-[26px] text-center rounded-full text-white bg-heading dark:bg-orange-500">
                                    {{ item.quantity }}</h3>
                                <img class="w-16 h-16 rounded-lg flex-shrink-0 object-cover" :src="item.item_image" alt="thumbnail">
                                <div class="w-full">
                                    <a href="#"
                                        class="text-sm font-medium capitalize transition text-heading dark:text-gray-200 hover:text-primary dark:hover:text-primary hover:underline">{{
                                            item.item_name
                                        }}</a>
                                    <p v-if="item.item_variations.length > 0" class="capitalize text-xs mb-1.5 text-gray-500 dark:text-gray-400">
                                        <span v-for="variation in item.item_variations" :key="variation">
                                            <span class="capitalize text-xs w-fit whitespace-nowrap">
                                                {{ variation.variation_name }}:&nbsp;
                                            </span>
                                            <span class="text-xs">
                                                {{ variation.name }}
                                            </span>
                                        </span>
                                    </p>

                                    <h3 class="text-xs font-semibold text-heading dark:text-gray-200">{{ item.total_currency_price }}</h3>
                                </div>
                            </div>
                            <ul class="flex flex-col gap-1.5 mt-2">
                                <li class="flex gap-1" v-if="item.item_extras.length > 0">
                                    <h3 class="capitalize text-xs w-fit whitespace-nowrap text-gray-500 dark:text-gray-400">{{
                                        $t('label.extras')
                                        }}:</h3>
                                    <p class="text-xs text-gray-700 dark:text-gray-300" v-for="(extra, index) in item.item_extras">
                                        {{ extra.name }}<span v-if="index + 1 < item.item_extras.length">, </span>
                                    </p>
                                </li>
                                <li class="flex gap-1" v-if="item.instruction">
                                    <h3 class="capitalize text-xs w-fit whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $t('label.instruction') }}:</h3>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">{{ item.instruction }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="rounded-xl border border-[#EFF0F6] dark:border-gray-800">
                        <ul class="flex flex-col gap-2 p-3 border-b border-dashed border-[#EFF0F6] dark:border-gray-800">
                            <li class="flex items-center justify-between text-heading dark:text-gray-200">
                                <span class="text-sm leading-6 capitalize text-gray-600 dark:text-gray-400">{{ $t("label.subtotal") }}</span>
                                <span class="text-sm leading-6 capitalize font-medium">
                                    {{ order.subtotal_currency_price }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-heading dark:text-gray-200">
                                <span class="text-sm leading-6 capitalize text-gray-600 dark:text-gray-400">{{ $t("label.discount") }}</span>
                                <span class="text-sm leading-6 capitalize font-medium">
                                    {{ order.discount_currency_price }}
                                </span>
                            </li>
                            <li class="flex items-center justify-between text-heading dark:text-gray-200"
                                v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                                <span class="text-sm leading-6 capitalize text-gray-600 dark:text-gray-400">{{ $t("label.delivery_charge") }}</span>
                                <span class="text-sm leading-6 capitalize font-medium text-[#1AB759] dark:text-emerald-400">
                                    {{ order.delivery_charge_currency_price }}</span>
                            </li>
                        </ul>
                        <div class="flex items-center justify-between p-3">
                            <h4 class="text-sm leading-6 font-semibold capitalize text-heading dark:text-gray-100">{{ $t("label.total") }}</h4>
                            <h5 class="text-sm leading-6 font-semibold capitalize text-heading dark:text-gray-100">
                                {{ order.total_currency_price }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>

import LoadingComponent from "./LoadingComponent";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import paymentTypeEnum from "../../../enums/modules/paymentTypeEnum";
import OrderStatusComponent from "./OrderStatusComponent";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";


export default {
    name: "OrderDetailsComponent",
    components: { LoadingComponent, OrderStatusComponent },
    props: {
        order: Object,
        orderItems: Object,
        orderBranch: Object,
        orderAddress: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                orderStatusEnum: orderStatusEnum,
                orderTypeEnum: orderTypeEnum,
                paymentStatusEnum: paymentStatusEnum,
                paymentTypeEnum: paymentTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.ACCEPT]: this.$t("label.accept"),
                    [orderStatusEnum.PREPARING]: this.$t("label.preparing"),
                    [orderStatusEnum.OUT_FOR_DELIVERY]: this.$t("label.out_for_delivery"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.TAKEAWAY]: this.$t("label.takeaway"),
                    [orderTypeEnum.DINING_TABLE]: this.$t("label.dining_table")
                },
                paymentStatusEnumArray: {
                    [paymentStatusEnum.PAID]: this.$t("label.paid"),
                    [paymentStatusEnum.UNPAID]: this.$t("label.unpaid")
                },
                paymentTypeEnumArray: {
                    [paymentTypeEnum.CASH_ON_DELIVERY]: this.$t("label.cash_on_delivery"),
                    [paymentTypeEnum.E_WALLET]: this.$t("label.e_wallet"),
                    [paymentTypeEnum.PAYPAL]: this.$t("label.paypal")
                },
                posPaymentMethodEnumArray: {
                    [posPaymentMethodEnum.CASH]: this.$t("label.cash"),
                    [posPaymentMethodEnum.CARD]: this.$t("label.card"),
                    [posPaymentMethodEnum.MOBILE_BANKING]: this.$t("label.mobile_banking"),
                    [posPaymentMethodEnum.OTHER]: this.$t("label.other"),
                },
            },
        };
    }
}
</script>