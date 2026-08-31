<template>
    <div id="receiptModal" class="modal">
        <div class="modal-dialog max-w-[380px] rounded-2xl overflow-hidden shadow-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800" :dir="direction">
            <!-- Modal Header Actions (Non-print) -->
            <div class="modal-header p-3.5 bg-gray-50 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between hidden-print">
                <!-- Slip Switcher -->
                <div class="flex bg-gray-200 dark:bg-gray-700 p-0.5 rounded-lg text-xs font-bold">
                    <button type="button" @click="activeSlip = 'customer'"
                        class="px-2.5 py-1 rounded-md transition"
                        :class="activeSlip === 'customer' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300'">
                        {{ $t('label.customer_invoice') || 'বিল রিসিট' }}
                    </button>
                    <button type="button" @click="activeSlip = 'kot'"
                        class="px-2.5 py-1 rounded-md transition"
                        :class="activeSlip === 'kot' ? 'bg-orange-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300'">
                        {{ $t('label.kot_slip') || 'KOT স্লিপ' }}
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" v-print="printObj"
                        class="flex items-center gap-1.5 py-1.5 px-3 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow-sm transition">
                        <i class="fa-solid fa-print"></i>
                        <span>{{ $t('button.print') || 'প্রিন্ট' }}</span>
                    </button>
                    <button type="button" @click="reset"
                        class="p-1.5 text-gray-400 hover:text-red-500 text-base">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Printable Body -->
            <div class="modal-body p-4 text-gray-800 dark:text-gray-100" id="print">
                <!-- ================= 1. CUSTOMER INVOICE SLIP ================= -->
                <div v-if="activeSlip === 'customer'">
                    <div class="text-center pb-3 border-b border-dashed border-gray-400">
                        <h3 class="text-xl font-extrabold text-orange-500 mb-0.5">{{ company.company_name || 'সহজ রেস্টুরেন্ট' }}</h3>
                        <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ branch.address }}</h4>
                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400">মোবাইল: {{ branch.phone }}</h5>
                    </div>

                    <div class="py-2 border-b border-dashed border-gray-400 text-xs">
                        <div class="flex justify-between items-center font-bold mb-1">
                            <span>অর্ডার: #{{ order.order_serial_no }}</span>
                            <span v-if="order.table_name || order.dining_table_name" class="px-2 py-0.5 bg-orange-100 dark:bg-gray-800 text-orange-600 rounded text-[11px]">
                                {{ order.table_name || order.dining_table_name }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-[11px]">
                            <span>তারিখ: {{ order.order_date }}</span>
                            <span>সময়: {{ order.order_time }}</span>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="w-full my-2 text-xs">
                        <thead class="border-b border-dashed border-gray-400 font-bold">
                            <tr>
                                <th class="py-1 text-left w-8">পরিমাণ</th>
                                <th class="py-1 text-left">খাবারের বিবরণ</th>
                                <th class="py-1 text-right">মূল্য</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dashed divide-gray-200 dark:divide-gray-800">
                            <tr v-for="(item, idx) in orderItems" :key="idx" class="py-1">
                                <td class="py-1 align-top font-bold text-gray-700 dark:text-gray-300">{{ item.quantity }}x</td>
                                <td class="py-1 align-top">
                                    <div class="font-semibold">{{ item.item_name }}</div>
                                    <div v-if="item.item_variations && Object.keys(item.item_variations).length" class="text-[10px] text-gray-500">
                                        <span v-for="(v, vIdx) in item.item_variations" :key="vIdx">{{ v.variation_name }}: {{ v.name }} </span>
                                    </div>
                                </td>
                                <td class="py-1 align-top text-right font-semibold">{{ item.total_without_tax_currency_price }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Calculation Summary -->
                    <div class="py-2 border-t border-dashed border-gray-400 text-xs space-y-1">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>সাবটোটাল:</span>
                            <span>{{ order.subtotal_without_tax_currency_price }}</span>
                        </div>
                        <div v-if="order.total_tax > 0" class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>ভ্যাট / ট্যাক্স:</span>
                            <span>{{ order.total_tax_currency_price }}</span>
                        </div>
                        <div v-if="order.discount > 0" class="flex justify-between text-emerald-600 font-semibold">
                            <span>ডিসকাউন্ট:</span>
                            <span>-{{ order.discount_currency_price }}</span>
                        </div>
                        <div class="flex justify-between font-black text-sm pt-1 border-t border-dashed border-gray-300 text-gray-900 dark:text-white">
                            <span>সর্বমোট বিল:</span>
                            <span class="text-orange-500">{{ order.total_currency_price }}</span>
                        </div>
                    </div>

                    <!-- Payment Note & Change Return -->
                    <div class="py-2 border-t border-b border-dashed border-gray-400 text-xs space-y-0.5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">পেমেন্ট মেথড:</span>
                            <span class="font-bold">{{ posPaymentMethodEnumArray[order.pos_payment_method] || 'ক্যাশ' }}</span>
                        </div>
                        <div v-if="order.pos_received_amount > 0" class="flex justify-between">
                            <span class="text-gray-500">গৃহীত টাকা (Received):</span>
                            <span class="font-bold">{{ order.pos_received_currency_amount || order.pos_received_amount }}</span>
                        </div>
                        <div v-if="order.change_return > 0 || order.cash_back_amount > 0" class="flex justify-between text-emerald-600 font-black">
                            <span>খুচরা ফেরত (Change Return):</span>
                            <span>{{ order.change_return || order.cash_back_currency_amount }}</span>
                        </div>
                    </div>

                    <div class="text-center pt-3 pb-1">
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300">আমাদের রেস্টুরেন্টে আসার জন্য ধন্যবাদ!</p>
                        <p class="text-[10px] text-gray-400">আবার আসবেন</p>
                    </div>
                </div>

                <!-- ================= 2. KOT (KITCHEN ORDER TICKET) ================= -->
                <div v-else class="kot-section">
                    <div class="text-center pb-2 border-b-2 border-dashed border-black">
                        <h2 class="text-lg font-black tracking-wider uppercase">*** KOT (KITCHEN TICKET) ***</h2>
                        <div class="text-xs font-bold mt-1">অর্ডার নং: #{{ order.order_serial_no }}</div>
                    </div>

                    <div class="py-2 border-b border-dashed border-black text-xs font-bold flex justify-between">
                        <div>
                            <span>টেবিল: </span>
                            <span class="text-sm underline">{{ order.table_name || order.dining_table_name || 'ডাইনিং টেবিল' }}</span>
                        </div>
                        <div>
                            <span v-if="order.token">টোকেন: #{{ order.token }}</span>
                            <span v-else>{{ order.order_time }}</span>
                        </div>
                    </div>

                    <table class="w-full my-2 text-xs">
                        <thead class="border-b border-black font-black">
                            <tr>
                                <th class="py-1 text-left w-12">পরিমাণ</th>
                                <th class="py-1 text-left">রান্নার আইটেম</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dashed divide-gray-300">
                            <tr v-for="(item, idx) in orderItems" :key="idx" class="py-1.5">
                                <td class="py-1.5 text-base font-black align-top">{{ item.quantity }} x</td>
                                <td class="py-1.5 align-top">
                                    <div class="font-bold text-sm">{{ item.item_name }}</div>
                                    <div v-if="item.item_variations && Object.keys(item.item_variations).length" class="text-xs text-gray-600">
                                        <span v-for="(v, vIdx) in item.item_variations" :key="vIdx">[{{ v.name }}] </span>
                                    </div>
                                    <div v-if="item.instruction" class="text-xs text-red-600 font-bold">
                                        নোট: {{ item.instruction }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center pt-2 border-t border-black text-[10px] font-bold">
                        সময়: {{ order.order_date }} {{ order.order_time }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import print from "vue3-print-nb";
import appService from "../../../services/appService";
import displayModeEnum from "../../../enums/modules/displayModeEnum";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";

export default {
    name: "ReceiptComponent",
    props: {
        order: Object
    },
    data() {
        return {
            activeSlip: 'customer',
            printObj: {
                id: "print",
                popTitle: this.$t("menu.order_receipt") || "Receipt",
            },
            posPaymentMethodEnumArray: {
                [posPaymentMethodEnum.CASH]: this.$t("label.cash") || "ক্যাশ",
                [posPaymentMethodEnum.CARD]: this.$t("label.card") || "কার্ড",
                [posPaymentMethodEnum.MOBILE_BANKING]: this.$t("label.mobile_banking") || "মোবাইল ব্যাংকিং",
                [posPaymentMethodEnum.OTHER]: this.$t("label.other") || "অন্যান্য",
            },
            orderTypeEnum: orderTypeEnum,
        }
    },
    computed: {
        company: function () {
            return this.$store.getters['company/lists'] || {};
        },
        branch: function () {
            return this.$store.getters['backendGlobalState/branchShow'] || {};
        },
        orderItems: function () {
            return this.$store.getters['posOrder/orderItems'] || [];
        },
        direction: function () {
            return this.$store.getters['frontendLanguage/show']?.display_mode === displayModeEnum.RTL ? 'rtl' : 'ltr';
        },
    },
    mounted() {
        this.$store.dispatch("company/lists").then().catch();
    },
    methods: {
        reset: function () {
            appService.modalHide('#receiptModal');
        },
    },
    directives: {
        print
    },
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
