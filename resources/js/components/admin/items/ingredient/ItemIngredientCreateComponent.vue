<template>
    <LoadingComponent :props="loading" />

    <button type="button" @click="add" data-modal="#ingredientModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-add-circle-line"></i>
        <span>{{ $t('button.add_ingredient') || 'Add Ingredient' }}</span>
    </button>

    <div id="ingredientModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.ingredients") || 'Recipe / Ingredients' }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="kitchen_goods_id" class="db-field-title required">
                                {{ $t("label.kitchen_goods") || 'Raw Material (কাঁচামাল)' }}
                            </label>
                            <select v-model="props.form.kitchen_goods_id" @change="onGoodsChange"
                                :class="errors.kitchen_goods_id ? 'invalid' : ''" id="kitchen_goods_id" class="db-field-control">
                                <option value="">{{ $t('label.select_raw_material') || '-- Select Material --' }}</option>
                                <option v-for="good in kitchenGoods" :key="good.id" :value="good.id">
                                    {{ good.name }} ({{ good.unit_code || good.unit_name }})
                                </option>
                            </select>
                            <small class="db-field-alert" v-if="errors.kitchen_goods_id">{{ errors.kitchen_goods_id[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="quantity" class="db-field-title required">
                                {{ $t("label.quantity") || 'Quantity' }}
                                <span v-if="selectedUnit" class="text-xs text-primary">({{ selectedUnit }})</span>
                            </label>
                            <input v-on:keypress="numberOnly($event)" v-model="props.form.quantity"
                                :class="errors.quantity ? 'invalid' : ''" type="text" id="quantity"
                                class="db-field-control" placeholder="e.g. 0.250 or 1" />
                            <small class="db-field-alert" v-if="errors.quantity">{{ errors.quantity[0] }}</small>
                        </div>

                        <div class="form-col-12" v-if="selectedGood">
                            <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-lg text-sm flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $t('label.unit_cost') || 'Unit Cost' }}: </span>
                                    <span>{{ currencyFormat(selectedGood.cost_per_unit) }} / {{ selectedGood.unit_code }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $t('label.total_cost') || 'Est. Cost' }}: </span>
                                    <span class="font-bold text-amber-600 dark:text-amber-400">
                                        {{ currencyFormat((selectedGood.cost_per_unit * (Number(props.form.quantity) || 0))) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "ItemIngredientCreateComponent",
    components: { LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            errors: {},
        };
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
        },
        kitchenGoods: function () {
            return this.$store.getters['kitchenGoods/lists'];
        },
        selectedGood: function () {
            if (!this.props.form.kitchen_goods_id) return null;
            return this.kitchenGoods.find(g => g.id == this.props.form.kitchen_goods_id) || null;
        },
        selectedUnit: function () {
            return this.selectedGood?.unit_code || this.selectedGood?.unit_name || '';
        }
    },
    mounted() {
        this.loadKitchenGoods();
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
        loadKitchenGoods: function () {
            this.$store.dispatch('kitchenGoods/lists', { paginate: 0, status: 5 });
        },
        onGoodsChange: function () {
            if (this.selectedGood) {
                this.props.form.unit_id = this.selectedGood.unit_id;
            }
        },
        add: function () {
            appService.modalShow('#ingredientModal');
        },
        numberOnly: function (e) {
            return appService.floatNumber(e);
        },
        reset: function () {
            appService.modalHide();
            appService.modalHide('#ingredientModal');
            this.$store.dispatch("itemIngredient/reset").then().catch();
            this.errors = {};
            if (this.props?.form) {
                this.props.form.kitchen_goods_id = "";
                this.props.form.quantity = "";
                this.props.form.unit_id = null;
            }
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("itemIngredient/save", this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.modalHide();
                    appService.modalHide('#ingredientModal');
                    alertService.success(this.$t("message.save_success"));
                    this.reset();
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                    if (err.response?.data?.message && !this.errors.kitchen_goods_id && !this.errors.quantity) {
                        alertService.error(err.response.data.message);
                    }
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
