<template>
    <div class="mb-4 flex items-center justify-between">
        <h4 class="text-base font-semibold text-gray-800 dark:text-gray-100">
            {{ $t('label.recipe_ingredients') || 'খাবারের রেসিপি ও কাঁচামাল (Recipe & BOM)' }}
        </h4>
        <ItemIngredientCreateComponent :props="ingredientProps" />
    </div>

    <!-- Recipe Cost & Profit Margin Analysis Card -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-5" v-if="summary">
        <div class="db-card p-4 border border-blue-100 dark:border-blue-900/30 bg-blue-50/40 dark:bg-blue-950/20">
            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium block mb-1">
                {{ $t('label.selling_price') || 'খাবারের বিক্রয় মূল্য' }}
            </span>
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100">
                {{ currencyFormat(summary.item_price) }}
            </span>
        </div>

        <div class="db-card p-4 border border-amber-100 dark:border-amber-900/30 bg-amber-50/40 dark:bg-amber-950/20">
            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium block mb-1">
                {{ $t('label.recipe_cost') || 'মোট কাঁচামাল খরচ (Food Cost)' }}
            </span>
            <span class="text-xl font-bold text-amber-600 dark:text-amber-400">
                {{ currencyFormat(summary.total_recipe_cost) }}
            </span>
        </div>

        <div class="db-card p-4 border"
            :class="summary.gross_profit >= 0 ? 'border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/40 dark:bg-emerald-950/20' : 'border-red-200 dark:border-red-900/50 bg-red-50/40 dark:bg-red-950/20'">
            <span class="text-xs font-medium block mb-1"
                :class="summary.gross_profit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'">
                {{ $t('label.gross_profit') || 'গ্রস লাভ (প্রতি খাবারে)' }}
            </span>
            <span class="text-xl font-bold"
                :class="summary.gross_profit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'">
                {{ currencyFormat(summary.gross_profit) }}
            </span>
        </div>

        <div class="db-card p-4 border"
            :class="summary.gross_profit_margin_percent >= 0 ? 'border-purple-100 dark:border-purple-900/30 bg-purple-50/40 dark:bg-purple-950/20' : 'border-red-200 dark:border-red-900/50 bg-red-50/40 dark:bg-red-950/20'">
            <span class="text-xs font-medium block mb-1"
                :class="summary.gross_profit_margin_percent >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400'">
                {{ $t('label.profit_margin') || 'প্রফিট মার্জিন (%)' }}
            </span>
            <div class="flex items-center gap-2">
                <span class="text-xl font-bold"
                    :class="summary.gross_profit_margin_percent >= 0 ? 'text-purple-600 dark:text-purple-400' : 'text-red-600 dark:text-red-400'">
                    {{ summary.gross_profit_margin_percent }}%
                </span>
                <span class="px-2 py-0.5 rounded text-xs font-semibold"
                    :class="summary.gross_profit_margin_percent < 0 ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : (summary.gross_profit_margin_percent >= 50 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300')">
                    {{ summary.gross_profit_margin_percent < 0 ? 'Loss Making' : (summary.gross_profit_margin_percent >= 50 ? 'High Margin' : 'Standard') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Ingredients Table -->
    <div class="db-card" v-if="ingredients.length > 0">
        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.raw_material") || 'কাঁচামাল' }}</th>
                        <th class="db-table-head-th">{{ $t("label.quantity") || 'পরিমাণ' }}</th>
                        <th class="db-table-head-th">{{ $t("label.unit_cost") || 'দর (প্রতি ইউনিট)' }}</th>
                        <th class="db-table-head-th">{{ $t("label.line_cost") || 'খরচ' }}</th>
                        <th class="db-table-head-th">{{ $t("label.available_stock") || 'বর্তমান স্টক' }}</th>
                        <th class="db-table-head-th">{{ $t("label.action") || 'অ্যাকশন' }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body">
                    <tr class="db-table-body-tr" v-for="item in ingredients" :key="item.id">
                        <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-200">
                            {{ item.kitchen_goods_name }}
                        </td>
                        <td class="db-table-body-td font-medium">
                            {{ item.quantity }} {{ item.unit_code || item.unit_name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ currencyFormat(item.cost_per_unit) }}
                        </td>
                        <td class="db-table-body-td font-bold text-amber-600 dark:text-amber-400">
                            {{ currencyFormat(item.total_cost) }}
                        </td>
                        <td class="db-table-body-td">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                :class="item.current_stock > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400'">
                                {{ Number(item.current_stock).toFixed(2) }} {{ item.unit_code || '' }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <div class="flex items-center gap-2">
                                <SmIconModalEditComponent @click="edit(item)" />
                                <SmIconDeleteComponent @click="destroy(item.id)" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-8 text-center bg-white dark:bg-gray-900 rounded-xl border border-dashed border-gray-300 dark:border-gray-800" v-else>
        <i class="fa-solid fa-mortar-pestle text-4xl text-gray-400 dark:text-gray-600 mb-3 block"></i>
        <h5 class="text-base font-semibold text-gray-700 dark:text-gray-300">{{ $t('message.no_ingredients_added') || 'এখনও কোনো কাঁচামাল যোগ করা হয়নি' }}</h5>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
            {{ $t('message.ingredients_instruction') || 'উপরের "Add Ingredient" বাটনে ক্লিক করে এই খাবারের জন্য প্রয়োজনীয় উপাদান (চাল, তেল, মাংস ইত্যাদি) যোগ করুন।' }}
        </p>
    </div>
</template>

<script>
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent";
import SmIconModalEditComponent from "../../components/buttons/SmIconModalEditComponent";
import ItemIngredientCreateComponent from "./ItemIngredientCreateComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "ItemIngredientListComponent",
    components: {
        ItemIngredientCreateComponent,
        SmIconModalEditComponent,
        SmIconDeleteComponent
    },
    props: {
        item: { type: Number, required: true },
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            ingredientProps: {
                id: 0,
                form: {
                    kitchen_goods_id: "",
                    quantity: "",
                    unit_id: null,
                },
                search: {
                    id: 0,
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'asc',
                }
            },
        };
    },
    mounted() {
        this.ingredientProps.id = this.item;
        this.ingredientProps.search.id = this.item;
        this.list();
        this.loadSummary();
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
        },
        ingredients: function () {
            return this.$store.getters['itemIngredient/lists'] || [];
        },
        summary: function () {
            return this.$store.getters['itemIngredient/summary'] || null;
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
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch('itemIngredient/lists', this.ingredientProps.search).finally(() => {
                this.loading.isActive = false;
            });
        },
        loadSummary: function () {
            this.$store.dispatch('itemIngredient/summary', this.item);
        },
        edit: function (ingredient) {
            this.ingredientProps.form = {
                kitchen_goods_id: ingredient.kitchen_goods_id,
                quantity: ingredient.quantity,
                unit_id: ingredient.unit_id,
            };
            this.$store.dispatch('itemIngredient/edit', ingredient.id);
            appService.modalShow('#ingredientModal');
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('itemIngredient/destroy', {
                        item: this.item,
                        id: id,
                        search: this.ingredientProps.search
                    }).then(() => {
                        this.loading.isActive = false;
                        this.loadSummary();
                        alertService.clear();
                        alertService.successItem(this.$t('message.delete_success'));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || err);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err);
                }
            }).catch(() => {});
        }
    }
};
</script>
