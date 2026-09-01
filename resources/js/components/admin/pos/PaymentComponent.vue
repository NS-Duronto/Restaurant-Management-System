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
                        <button type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.CASH ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="selectPaymentMethod(posPaymentMethodEnum.CASH)">
                            <i class="fa-solid fa-money-bill-wave text-base"></i>
                            <span>{{ $t("label.cash") }}</span>
                        </button>
                        <button type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.CARD ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="selectPaymentMethod(posPaymentMethodEnum.CARD)">
                            <i class="fa-solid fa-credit-card text-base"></i>
                            <span>{{ $t("label.card") }}</span>
                        </button>
                        <button type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.MOBILE_BANKING ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="selectPaymentMethod(posPaymentMethodEnum.MOBILE_BANKING)">
                            <i class="fa-solid fa-mobile-screen-button text-base"></i>
                            <span>{{ $t("label.mobile_banking") }}</span>
                        </button>
                        <button type="button"
                            class="flex flex-col items-center justify-center gap-1.5 rounded-xl py-2.5 px-2 border transition text-xs font-semibold"
                            :class="props.form.pos_payment_method === posPaymentMethodEnum.OTHER ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-orange-500'"
                            @click="selectPaymentMethod(posPaymentMethodEnum.OTHER)">
                            <i class="fa-solid fa-ellipsis text-base"></i>
                            <span>{{ $t("label.other") }}</span>
                        </button>
                    </nav>
                </div>

                <!-- 1. Cash Payment Panel with Note Exchange & Quick Denominations -->
                <div v-if="props.form.pos_payment_method === posPaymentMethodEnum.CASH">
                    <!-- Quick Note Chips -->
                    <div class="mb-3">
                        <label class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1.5 flex items-center justify-between">
                            <span>{{ $t("label.quick_notes") }}</span>
                            <span class="text-orange-500 text-[10px]">{{ $t("label.quick_notes_hint") }}</span>
                        </label>
                        <div class="grid grid-cols-5 gap-1.5">
                            <button type="button" @click="setReceivedAmount(props.form.total)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-orange-50 hover:bg-orange-100 dark:bg-gray-800 border-orange-200 dark:border-gray-700 text-orange-600 dark:text-orange-400 truncate" :title="$t('label.exact_amount')">
                                {{ $t('label.exact') }}
                            </button>
                            <button type="button" @click="setReceivedAmount(100)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                {{ currencyFormat(100, 0, setting.site_default_currency_symbol, setting.site_currency_position) }}
                            </button>
                            <button type="button" @click="setReceivedAmount(500)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                {{ currencyFormat(500, 0, setting.site_default_currency_symbol, setting.site_currency_position) }}
                            </button>
                            <button type="button" @click="setReceivedAmount(1000)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                {{ currencyFormat(1000, 0, setting.site_default_currency_symbol, setting.site_currency_position) }}
                            </button>
                            <button type="button" @click="setReceivedAmount(2000)"
                                class="py-1.5 px-1 rounded-lg border text-center font-bold text-xs transition bg-white hover:bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                                {{ currencyFormat(2000, 0, setting.site_default_currency_symbol, setting.site_currency_position) }}
                            </button>
                        </div>
                    </div>

                    <!-- Enter Received Amount Input -->
                    <div class="mb-3">
                        <label class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1 block">
                            {{ $t("label.enter_received_amount") }}
                        </label>
                        <div class="relative">
                            <input id="cashInput" ref="cashInput" type="text" v-model="receivedAmount" @input="onReceivedInput" v-on:keypress="floatNumber($event)"
                                :placeholder="$t('label.enter_received_amount')"
                                class="h-11 w-full rounded-xl border py-1.5 px-4 pr-12 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-bold text-base focus:border-orange-500 focus:outline-none">
                            <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-semibold">{{ setting.site_default_currency_symbol || '৳' }}</span>
                        </div>
                    </div>

                    <!-- Live Change Return Alert Card -->
                    <div v-if="changeReturnAmount > 0" class="mb-4 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/30 flex items-center justify-between animate-pulse">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <div>
                                <span class="text-xs text-emerald-800 dark:text-emerald-300 font-bold block">{{ $t("label.return_change") }}</span>
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400">{{ $t("label.change_return_desc") }}</span>
                            </div>
                        </div>
                        <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                            {{ currencyFormat(changeReturnAmount, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                        </span>
                    </div>
                </div>

                <!-- 2. Card Payment Panel: Enter last 4 digits of card -->
                <div v-if="props.form.pos_payment_method === posPaymentMethodEnum.CARD" class="mb-4">
                    <label class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5 block">
                        {{ $t('label.enter_card_last_4_digits') }}
                    </label>
                    <input id="cardInput" type="text" maxlength="4" ref="cardInput" v-model="cardDigits" v-on:keypress="onlyNumber($event)"
                        :placeholder="$t('label.enter_card_last_4_digits')"
                        class="h-11 w-full rounded-xl border py-1.5 px-4 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-bold text-base tracking-widest focus:border-orange-500 focus:outline-none" required>
                </div>

                <!-- 3. MFS Payment Panel: Enter Transaction ID -->
                <div v-if="props.form.pos_payment_method === posPaymentMethodEnum.MOBILE_BANKING" class="mb-4">
                    <label class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5 block">
                        {{ $t('label.enter_transaction_id') }}
                    </label>
                    <input id="mfs-trans" type="text" ref="mfsInput" v-model="mfsTransId"
                        :placeholder="$t('label.enter_transaction_id')"
                        class="h-11 w-full rounded-xl border py-1.5 px-4 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-semibold text-sm focus:border-orange-500 focus:outline-none">
                </div>

                <!-- 4. Other Payment Panel: Enter payment note -->
                <div v-if="props.form.pos_payment_method === posPaymentMethodEnum.OTHER" class="mb-4">
                    <label class="capitalize font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1.5 block">
                        {{ $t('label.enter_payment_note') }}
                    </label>
                    <input id="other-trans" type="text" ref="otherInput" v-model="otherNote"
                        :placeholder="$t('label.enter_payment_note')"
                        class="h-11 w-full rounded-xl border py-1.5 px-4 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-semibold text-sm focus:border-orange-500 focus:outline-none">
                </div>

                <!-- On-Screen Keypad -->
                <div class="grid grid-cols-4 gap-2 mb-4">
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
            receivedAmount: "",
            cardDigits: "",
            mfsTransId: "",
            otherNote: "",
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
        onlyNumber(e) {
            return appService.onlyNumber(e);
        },
        setReceivedAmount: function (amount) {
            this.receivedAmount = String(amount);
        },
        onReceivedInput: function (e) {
            this.receivedAmount = e.target.value;
        },
        pressKey: function (val) {
            if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CASH) {
                this.receivedAmount += String(val);
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CARD) {
                if (val !== '.' && this.cardDigits.length < 4) {
                    this.cardDigits += String(val);
                }
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.MOBILE_BANKING) {
                this.mfsTransId += String(val);
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.OTHER) {
                this.otherNote += String(val);
            }
        },
        backspaceKey: function () {
            if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CASH) {
                this.receivedAmount = this.receivedAmount.slice(0, -1);
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CARD) {
                this.cardDigits = this.cardDigits.slice(0, -1);
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.MOBILE_BANKING) {
                this.mfsTransId = this.mfsTransId.slice(0, -1);
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.OTHER) {
                this.otherNote = this.otherNote.slice(0, -1);
            }
        },
        clearKey: function () {
            if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CASH) {
                this.receivedAmount = "";
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.CARD) {
                this.cardDigits = "";
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.MOBILE_BANKING) {
                this.mfsTransId = "";
            } else if (this.props.form.pos_payment_method === this.posPaymentMethodEnum.OTHER) {
                this.otherNote = "";
            }
        },
        reset: function () {
            this.receivedAmount = "";
            this.cardDigits = "";
            this.mfsTransId = "";
            this.otherNote = "";
            this.$props.props.form.pos_payment_note = "";
            this.$props.props.form.pos_received_amount = null;
            appService.modalHide('#orderpayment');
        },
        selectPaymentMethod: function (method) {
            this.$props.props.form.pos_payment_method = method;
            this.$props.props.form.pos_payment_note = "";
        },
        confirmOrder: function () {
            try {
                // Validate payment methods
                if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.CASH) {
                    this.$props.props.form.pos_received_amount = this.receivedAmount ? Number(this.receivedAmount) : this.$props.props.form.total;
                    this.$props.props.form.pos_payment_note = "";
                } else if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.CARD) {
                    if (!this.cardDigits || this.cardDigits.trim().length !== 4) {
                        alertService.error("Please enter last 4 digits of card");
                        return;
                    }
                    this.$props.props.form.pos_received_amount = null;
                    this.$props.props.form.pos_payment_note = this.cardDigits.trim();
                } else if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.MOBILE_BANKING) {
                    if (!this.mfsTransId || !this.mfsTransId.trim()) {
                        alertService.error("Please enter Transaction ID");
                        return;
                    }
                    this.$props.props.form.pos_received_amount = null;
                    this.$props.props.form.pos_payment_note = this.mfsTransId.trim();
                } else if (this.$props.props.form.pos_payment_method === this.posPaymentMethodEnum.OTHER) {
                    if (!this.otherNote || !this.otherNote.trim()) {
                        alertService.error("Please enter payment note");
                        return;
                    }
                    this.$props.props.form.pos_received_amount = null;
                    this.$props.props.form.pos_payment_note = this.otherNote.trim();
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
