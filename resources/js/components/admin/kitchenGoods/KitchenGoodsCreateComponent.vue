<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer">
        <div class="drawer-dialog">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.kitchen_goods') }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" placeholder="কাঁচামালের নাম (চাল, তেল ইত্যাদি)">
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="category" class="db-field-title required">{{ $t("label.category") }}</label>
                            <vue-select class="db-field-control" id="category"
                                v-model="props.form.kitchen_goods_category_id" :options="categories" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="ক্যাটাগরি নির্বাচন করুন" />
                            <small class="db-field-alert" v-if="errors.kitchen_goods_category_id">{{ errors.kitchen_goods_category_id[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="unit" class="db-field-title required">{{ $t("label.unit") }}</label>
                            <vue-select class="db-field-control" id="unit"
                                v-model="props.form.unit_id" :options="units" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="একক (Kg / Ltr)" />
                            <small class="db-field-alert" v-if="errors.unit_id">{{ errors.unit_id[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="cost_per_unit" class="db-field-title">{{ $t("label.unit_cost") }}</label>
                            <input v-model="props.form.cost_per_unit" v-bind:class="errors.cost_per_unit ? 'invalid' : ''" type="number" step="0.01"
                                id="cost_per_unit" class="db-field-control" placeholder="একক ক্রয়মূল্য">
                            <small class="db-field-alert" v-if="errors.cost_per_unit">{{ errors.cost_per_unit[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status"
                                            type="radio" id="inactive" class="custom-radio-field">
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-save"></i>
                                    <span>{{ $t("label.save") }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-close"></i>
                                    <span>{{ $t("button.close") }}</span>
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
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "KitchenGoodsCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
            },
            errors: {},
        }
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_kitchen_good') || 'কাঁচামাল যোগ করুন' };
        },
        categories: function () {
            return this.$store.getters['kitchenGoodsCategory/lists'] || [];
        },
        units: function () {
            return this.$store.getters['unit/lists'] || [];
        },
    },
    mounted() {
        this.$store.dispatch('kitchenGoodsCategory/lists', { paginate: 0 }).then().catch();
        this.$store.dispatch('unit/lists', { paginate: 0 }).then().catch();
    },
    methods: {
        addReset: function () {
            this.errors = {};
            this.$store.dispatch('kitchenGoods/reset').then().catch();
            this.props.form = {
                name: "",
                kitchen_goods_category_id: null,
                unit_id: null,
                cost_per_unit: "",
                status: statusEnum.ACTIVE,
            };
        },
        reset: function () {
            this.errors = {};
            appService.sideDrawerHide();
            this.$store.dispatch('kitchenGoods/reset').then().catch();
            this.props.form = {
                name: "",
                kitchen_goods_category_id: null,
                unit_id: null,
                cost_per_unit: "",
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('kitchenGoods/save', this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.successFlip(this.$store.getters['kitchenGoods/temp'].isEditing, this.$t('menu.kitchen_goods'));
                    this.props.form = {
                        name: "",
                        kitchen_goods_category_id: null,
                        unit_id: null,
                        cost_per_unit: "",
                        status: statusEnum.ACTIVE,
                    };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        }
    }
}
</script>
