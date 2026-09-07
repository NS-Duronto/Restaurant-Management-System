<template>
    <div id="wastageDetailsModal" class="modal">
        <div class="modal-dialog !w-[700px] max-w-full">
            <div class="modal-header">
                <h3 class="modal-title font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-red-600"></i>
                    <span>{{ $t('label.wastage_voucher') || 'অপচয় ভাউচার' }}: #{{ wastage.wastage_no }}</span>
                </h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="close"></button>
            </div>
            <div class="modal-body" v-if="wastage.id">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4 p-3 bg-gray-50 dark:bg-gray-800/60 rounded-xl">
                    <div>
                        <span class="text-xs text-gray-500 block">{{ $t('label.date') || 'তারিখ' }}</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ wastage.date }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">{{ $t('label.logged_by') || 'এন্ট্রি করেছেন' }}</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ wastage.user_name || 'Staff' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 block">{{ $t('label.total_loss_amount') || 'মোট ক্ষতি' }}</span>
                        <span class="font-bold text-red-600 dark:text-red-400 text-base">
                            {{ currencyFormat(wastage.total_loss_amount) }}
                        </span>
                    </div>
                </div>

                <div class="db-table-responsive mb-4">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.raw_material') || 'কাঁচামাল' }}</th>
                                <th class="db-table-head-th">{{ $t('label.quantity') || 'পরিমাণ' }}</th>
                                <th class="db-table-head-th">{{ $t('label.cost_per_unit') || 'দর' }}</th>
                                <th class="db-table-head-th">{{ $t('label.loss_amount') || 'মোট ক্ষতি' }}</th>
                                <th class="db-table-head-th">{{ $t('label.reason') || 'কারণ' }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body">
                            <tr class="db-table-body-tr" v-for="item in wastage.items" :key="item.id">
                                <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-200">
                                    {{ item.kitchen_goods_name }}
                                </td>
                                <td class="db-table-body-td font-medium">
                                    {{ item.quantity }} {{ item.unit_code || item.unit_name }}
                                </td>
                                <td class="db-table-body-td">
                                    {{ currencyFormat(item.cost_per_unit) }}
                                </td>
                                <td class="db-table-body-td font-bold text-red-600 dark:text-red-400">
                                    {{ currencyFormat(item.total_cost) }}
                                </td>
                                <td class="db-table-body-td">
                                    <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                        {{ item.reason }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-amber-50/70 dark:bg-amber-950/30 rounded-lg text-sm text-gray-700 dark:text-gray-300" v-if="wastage.note">
                    <strong>{{ $t('label.note') || 'মন্তব্য' }}:</strong> {{ wastage.note }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import appService from "../../../services/appService";

export default {
    name: "WastageShowComponent",
    props: {
        wastage: {
            type: Object,
            default: () => ({})
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
        }
    },
    methods: {
        currencyFormat: function (amount) {
            return appService.currencyFormat(
                amount,
                this.setting?.site_digit_after_decimal_point,
                this.setting?.site_default_currency_symbol,
                this.setting?.site_currency_position
            );
        },
        close: function () {
            appService.modalHide('#wastageDetailsModal');
        }
    }
}
</script>
