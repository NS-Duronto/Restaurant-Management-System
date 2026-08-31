<template>
    <LoadingComponent :props="loading" />

    <div id="orderpayment" class="modal">
        <div class="modal-dialog max-w-[460px] w-full">
            <div class="modal-header pb-3 border-b border-[#D9DBE9] dark:border-gray-800">
                <h3 class="capitalize font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-cash-register text-orange-500"></i>
                    {{ $t("label.order_payment") }}
                </h3>
                <button class="modal-close fa-regular fa-circle-xmark text-gray-400 hover:text-red-500 text-lg" @click="reset"></button>
            </div>
            <div class="modal-body">
                <!-- Total Amount Card -->
                <div class="mb-4">
                    <div class="flex justify-between items-center h-14 w-full rounded-xl py-2 px-4 bg-orange-50 dark:bg-gray-800/80 border border-orange-500/20">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $t("label.total_amount") }}</span>
                        <span class="text-orange-500 dark:text-orange-400 text-xl font-bold">
                            {{ currencyFormat(props.form.total, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </span>
                    </div>
                </div>

                <!-- Payment Method Tabs -->
                <div class="mb-4">
                    <h3 class="capitalize font-semibold text-xs text-gray-600 dark:text-gray-400 mb-2">{{ $t("label.select_payment_method") }}</h3>
                    <nav class="grid grid-cols-4 gap-2">
                        <button data-tab="#cash" type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.CASH ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="paymentMethod(posPaymentMethodEnum.CASH, 'cashInput')">
                            <i class="fa-solid fa-money-bill-wave text-base"></i>
                            <span>{{ $t("label.cash") }}</span>
                        </button>
                        <button data-tab="#card" type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.CARD ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="paymentMethod(posPaymentMethodEnum.CARD, 'cardInput')">
                            <i class="fa-solid fa-credit-card text-base"></i>
                            <span>{{ $t("label.card") }}</span>
                        </button>
                        <button data-tab="#mfs" type="button" onclick="createkeyboard('mfs')"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.MOBILE_BANKING ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="paymentMethod(posPaymentMethodEnum.MOBILE_BANKING)">
                            <i class="fa-solid fa-mobile-screen-button text-base"></i>
                            <span>{{ $t("label.mobile_banking") }}</span>
                        </button>
                        <button data-tab="#otherpay" type="button" onclick="createkeyboard('otherpay')"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.OTHER ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="paymentMethod(posPaymentMethodEnum.OTHER)">
                            <i class="fa-solid fa-ellipsis text-base"></i>
                            <span>{{ $t("label.other") }}</span>
                        </button>
                    </nav>
                </div>

                <!-- Cash Payment Panel with Note Exchange & Quick Denominations -->
                <div id="cash" class="data-tab" v-if="props.form.pos_payment_method === posPaymentMethodEnum.CASH">
                    <!-- Quick Note Chips -->
                    <div class="mb-3">
                        <label class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1.5 block flex items-center justify-between">
                            <span>{{ $t("label.quick_notes") || 'ক্যাশ নোট বাটন' }}</span>
                            <span class="text-orange-500 text-[10px]">এক ক্লিকে নোট সিলেক্ট করুন</span>
                        </label>
                        <div class="grid grid-cols-5 gap-1.5">
                            <button type="button" @click="setReceivedAmount(props.form.total)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-orange-50 hover:bg-orange-100 dark:bg-gray-800 border-orange-200 dark:border-gray-700 text-orange-600 dark:text-orange-400 truncate" title="সমান টাকা">
                                সমান
                            </button>
                            <button type="button" @click="setReceivedAmount(100)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                ৳১০০
                            </button>
                            <button type="button" @click="setReceivedAmount(500)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                ৳৫০০
                            </button>
                            <button type="button" @click="setReceivedAmount(1000)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                ৳১,০০০
                            </button>
                            <button type="button" @click="setReceivedAmount(2000)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                ৳২,০০০
                            </button>
                        </div>
                    </div>

                    <!-- Received Amount Input -->
                    <div class="mb-3">
                        <label class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1 block">{{ $t("label.received_amount") }} (নোট গ্রহণ)</label>
                        <div class="relative">
                            <input id="cashInput" ref="cashInput" type="text" v-model="receivedAmount" @input="onReceivedInput" v-on:keypress="floatNumber($event)"
                                placeholder="কাস্টমার কত টাকা দিল..."
                                class="h-11 w-full rounded-xl border py-1.5 px-4 pr-12 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-bold text-base focus:border-orange-500 focus:outline-none">
                            <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-semibold">৳</span>
                        </div>
                    </div>

                    <!-- Live Change Return Alert Card -->
                    <div v-if="changeReturnAmount > 0" class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/30 flex items-center justify-between animate-pulse">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <div>
                                <span class="text-xs text-emerald-800 dark:text-emerald-300 font-bold block">{{ $t("label.return_change") || 'খুচরা ফেরত দিন' }}</span>
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400">কাস্টমারকে ফেরতযোগ্য টাকা</span>
                            </div>
                        </div>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                            {{ currencyFormat(changeReturnAmount, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </span>
                    </div>
                </div>

                <!-- Card Payment Panel -->
                <div id="card" class="data-tab" v-if="props.form.pos_payment_method === posPaymentMethodEnum.CARD">
                    <div class="mb-4">
                        <h3 class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5">{{ $t('label.enter_card_last_4_digits') }}</h3>
                        <input id="cardInput" type="number" ref="cardInput"
                            class="h-11 w-full rounded-xl border py-1.5 px-4 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-semibold" required>
                    </div>
                </div>

                <!-- MFS Payment Panel -->
                <div id="mfs" class="data-tab" v-if="props.form.pos_payment_method === posPaymentMethodEnum.MOBILE_BANKING">
                    <div class="mb-4">
                        <h3 class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5">{{ $t('label.enter_transaction_id') }}</h3>
                        <input id="mfs-trans" type="text" ref="mfsInput"
                            placeholder="bKash / Nagad TrxID"
                            class="h-11 w-full rounded-xl border py-1.5 px-4 placeholder:text-xs border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-semibold">
                    </div>
                    <div class="board grid grid-cols-10 justify-between gap-1 mb-4"></div>
                </div>

                <!-- Other Payment Panel -->
                <div id="otherpay" class="data-tab" v-if="props.form.pos_payment_method === posPaymentMethodEnum.OTHER">
                    <div class="mb-4">
                        <h3 class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5">{{ $t('label.enter_payment_note') }}</h3>
                        <input id="other-trans" type="text" ref="otherInput"
                            class="h-11 w-full rounded-xl border py-1.5 px-4 placeholder:text-xs border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-semibold">
                    </div>
                    <div class="board grid grid-cols-10 justify-between gap-1 mb-4"></div>
                </div>

                <!-- On-Screen Keypad -->
                <div class="grid grid-cols-4 gap-2 mb-4"
                    v-if="props.form.pos_payment_method === posPaymentMethodEnum.CASH || props.form.pos_payment_method === posPaymentMethodEnum.CARD">
                    <button type="button" @click="pressKey('1')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">1</button>
                    <button type="button" @click="pressKey('2')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">2</button>
                    <button type="button" @click="pressKey('3')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">3</button>
                    <button type="button" @click="backspaceKey" class="num bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold transition shadow-sm row-span-2">
                        <i class="fa-solid fa-delete-left text-base"></i>
                    </button>
                    <button type="button" @click="pressKey('4')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">4</button>
                    <button type="button" @click="pressKey('5')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">5</button>
                    <button type="button" @click="pressKey('6')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">6</button>
                    <button type="button" @click="pressKey('7')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">7</button>
                    <button type="button" @click="pressKey('8')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">8</button>
                    <button type="button" @click="pressKey('9')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">9</button>
                    <button type="button" @click="clearKey" class="num bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl p-2.5 flex items-center justify-center text-xs font-bold transition shadow-sm row-span-2">
                        C
                    </button>
                    <button type="button" @click="pressKey('00')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">00</button>
                    <button type="button" @click="pressKey('0')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">0</button>
                    <button type="button" @click="pressKey('.')" class="num bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl p-2.5 flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-200 transition shadow-sm">.</button>
                </div>

                <!-- Confirm Order & Settle Button -->
                <button @click="confirmOrder" type="button"
                    class="rounded-xl text-sm py-3 px-4 font-bold w-full text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check-circle"></i>
                    {{ $t("label.confirm_and_print") }}
                </button>
            </div>
        </div>
    </div>

    <ReceiptComponent :order="order" />
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import ReceiptComponent from "./ReceiptComponent";
import posPaymentMethodEnum from "../../../enums/modules/posPaymentMethodEnum";
import sourceEnum from "../../../enums/modules/sourceEnum";
import isAdvanceOrderEnum from "../../../enums/modules/isAdvanceOrderEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import _ from "lodash";

export default {
    name: "PaymentComponent",
    components: { LoadingComponent, ReceiptComponent },
    props: {
        props: Object,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            order: {},
            posPaymentMethodEnum: posPaymentMethodEnum,
            inputIdName: "cashInput",
            receivedAmount: "",
        };
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        changeReturnAmount: function () {
            const received = Number(this.receivedAmount) || 0;
            const total = Number(this.props.form.total) || 0;
            return received > total ? received - total : 0;
        }
    },
    methods: {
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        setReceivedAmount: function (amount) {
            this.receivedAmount = String(amount);
            if (this.$refs.cashInput) {
                this.$refs.cashInput.value = this.receivedAmount;
            }
        },
        onReceivedInput: function (e) {
            this.receivedAmount = e.target.value;
        },
        pressKey: function (val) {
            this.receivedAmount += String(val);
            if (this.$refs.cashInput) {
                this.$refs.cashInput.value = this.receivedAmount;
            }
        },
        backspaceKey: function () {
            this.receivedAmount = this.receivedAmount.slice(0, -1);
            if (this.$refs.cashInput) {
                this.$refs.cashInput.value = this.receivedAmount;
            }
        },
        clearKey: function () {
            this.receivedAmount = "";
            if (this.$refs.cashInput) {
                this.$refs.cashInput.value = "";
            }
        },
        reset: function () {
            this.receivedAmount = "";
            Object.keys(this.$refs).forEach(refName => {
                if (this.$refs[refName] && this.$refs[refName].value !== undefined) {
                    this.$refs[refName].value = "";
                }
            });
            this.$props.props.form.pos_payment_note = "";
            this.$props.props.form.pos_received_amount = null;
            appService.modalHide('#orderpayment');
        },
        paymentMethod: function (method, Idname = "") {
            if (Idname) {
                this.inputIdName = Idname;
            }
            this.receivedAmount = "";
            Object.keys(this.$refs).forEach(refName => {
                if (this.$refs[refName] && this.$refs[refName].value !== undefined) {
                    this.$refs[refName].value = "";
                }
            });
            this.$props.props.form.pos_payment_method = method;
            this.$props.props.form.pos_payment_note = "";
        },
        confirmOrder: function () {
            try {
                if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.CASH) {
                    this.$props.props.form.pos_received_amount = this.receivedAmount || (this.$refs.cashInput ? this.$refs.cashInput.value : null);
                } else {
                    this.$props.props.form.pos_received_amount = null;
                }

                if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.CARD && this.$refs.cardInput) {
                    this.$props.props.form.pos_payment_note = this.$refs.cardInput.value;
                } else if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.MOBILE_BANKING && this.$refs.mfsInput) {
                    this.$props.props.form.pos_payment_note = this.$refs.mfsInput.value;
                } else if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.OTHER && this.$refs.otherInput) {
                    this.$props.props.form.pos_payment_note = this.$refs.otherInput.value;
                } else {
                    this.$props.props.form.pos_payment_note = "";
                }

                this.loading.isActive = true;
                this.$store.dispatch("defaultAccess/show").then((res) => {
                    this.$props.props.form.branch_id = res.data.data.branch_id;
                    this.$store.dispatch('posOrder/save', this.$props.props.form).then(orderResponse => {
                        this.$props.props.form.token = "";
                        this.$props.props.form.subtotal = null;
                        this.$props.props.form.discount = 0;
                        this.$props.props.form.delivery_time = null;
                        this.$props.props.form.delivery_charge = null;
                        this.$props.props.form.total = 0;
                        this.$props.props.form.order_type = orderTypeEnum.DINING_TABLE;
                        this.$props.props.form.is_advance_order = isAdvanceOrderEnum.NO;
                        this.$props.props.form.source = sourceEnum.POS;
                        this.$props.props.form.address_id = null;
                        this.$props.props.form.dining_table_id = null;
                        this.$props.props.form.coupon_id = null;
                        this.$props.props.form.items = [];
                        this.$props.props.form.pos_payment_method = this.posPaymentMethodEnum.CASH;
                        this.$props.props.form.pos_payment_note = null;
                        this.$props.props.form.pos_received_amount = null;

                        appService.modalHide('#orderpayment');
                        this.$store.dispatch('posCart/resetCart').then(() => {
                            this.loading.isActive = false;
                        }).catch();

                        this.$store.dispatch('posOrder/show', orderResponse.data.data.id).then(showRes => {
                            this.order = showRes.data.data;
                            this.loading.isActive = false;
                            this.reset();
                            appService.modalShow('#receiptModal');
                        }).catch((error) => {
                            this.loading.isActive = false;
                            alertService.error(error.response?.data?.message || 'Error loading order');
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        if (typeof err.response?.data?.errors === 'object') {
                            _.forEach(err.response.data.errors, (error) => {
                                alertService.error(error[0]);
                            });
                        } else {
                            alertService.error(err.response?.data?.message || 'Error saving order');
                        }
                    });
                }).catch(() => {
                    this.loading.isActive = false;
                });

            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
