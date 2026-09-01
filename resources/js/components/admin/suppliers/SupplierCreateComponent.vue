<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer">
        <div class="drawer-dialog">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.suppliers') }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" :placeholder="$t('label.supplier_name')">
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="company" class="db-field-title">{{ $t("label.company_name") }}</label>
                            <input v-model="props.form.company_name" v-bind:class="errors.company_name ? 'invalid' : ''" type="text"
                                id="company" class="db-field-control" :placeholder="$t('label.company_name_placeholder')">
                            <small class="db-field-alert" v-if="errors.company_name">{{ errors.company_name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="phone" class="db-field-title">{{ $t("label.phone") }}</label>
                            <input v-model="props.form.phone" v-bind:class="errors.phone ? 'invalid' : ''" type="text"
                                id="phone" class="db-field-control" :placeholder="$t('label.phone_placeholder')">
                            <small class="db-field-alert" v-if="errors.phone">{{ errors.phone[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="email" class="db-field-title">{{ $t("label.email") }}</label>
                            <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="email"
                                id="email" class="db-field-control" :placeholder="$t('label.email_placeholder')">
                            <small class="db-field-alert" v-if="errors.email">{{ errors.email[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <label for="address" class="db-field-title">{{ $t("label.address") }}</label>
                            <textarea v-model="props.form.address" v-bind:class="errors.address ? 'invalid' : ''"
                                id="address" class="db-field-control" :placeholder="$t('label.address_placeholder')"></textarea>
                            <small class="db-field-alert" v-if="errors.address">{{ errors.address[0] }}</small>
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
    name: "SupplierCreateComponent",
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
            return { title: this.$t('button.add_supplier') };
        },
    },
    methods: {
        addReset: function () {
            this.errors = {};
            this.$store.dispatch('supplier/reset').then().catch();
            this.props.form = {
                name: "",
                company_name: "",
                phone: "",
                email: "",
                address: "",
                status: statusEnum.ACTIVE,
            };
        },
        reset: function () {
            this.errors = {};
            appService.sideDrawerHide();
            this.$store.dispatch('supplier/reset').then().catch();
            this.props.form = {
                name: "",
                company_name: "",
                phone: "",
                email: "",
                address: "",
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('supplier/save', this.props).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.successFlip(this.$store.getters['supplier/temp'].isEditing, this.$t('menu.suppliers'));
                    this.props.form = {
                        name: "",
                        company_name: "",
                        phone: "",
                        email: "",
                        address: "",
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
