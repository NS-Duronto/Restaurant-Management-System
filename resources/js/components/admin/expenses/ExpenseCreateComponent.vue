<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" @click="addReset" />

    <div id="sidebar" class="drawer">
        <div class="drawer-dialog">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ $t('menu.expenses') }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <!-- Category -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="expense_category" class="db-field-title required">{{ $t("label.expense_category") }}</label>
                            <vue-select class="db-field-control" id="expense_category"
                                v-model="props.form.expense_category_id" :options="categories" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                :placeholder="$t('label.select_category')" />
                            <small class="db-field-alert" v-if="errors.expense_category_id">{{ errors.expense_category_id[0] }}</small>
                        </div>

                        <!-- Date -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="expense_date" class="db-field-title required">{{ $t("label.expense_date") }}</label>
                            <input v-model="props.form.date" type="date" id="expense_date" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.date">{{ errors.date[0] }}</small>
                        </div>

                        <!-- Amount -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="amount" class="db-field-title required">{{ $t("label.amount") }}</label>
                            <input v-model="props.form.amount" v-bind:class="errors.amount ? 'invalid' : ''" type="number" step="0.01" min="1"
                                id="amount" class="db-field-control" :placeholder="$t('label.amount')">
                            <small class="db-field-alert" v-if="errors.amount">{{ errors.amount[0] }}</small>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="payment_method" class="db-field-title">{{ $t("label.payment_method") }}</label>
                            <select v-model="props.form.payment_method" id="payment_method" class="db-field-control">
                                <option :value="1">{{ $t('label.cash') }}</option>
                                <option :value="2">{{ $t('label.bank') }}</option>
                                <option :value="3">{{ $t('label.mobile_banking') }}</option>
                                <option :value="4">{{ $t('label.other') }}</option>
                            </select>
                        </div>

                        <!-- Receipt Voucher Image Upload -->
                        <div class="form-col-12">
                            <label for="file" class="db-field-title">{{ $t("label.receipt_voucher") }}</label>
                            <input type="file" id="file" @change="changeFile" class="db-field-control">
                            <small class="db-field-alert" v-if="errors.file">{{ errors.file[0] }}</small>
                        </div>

                        <!-- Description -->
                        <div class="form-col-12">
                            <label for="description" class="db-field-title">{{ $t("label.description") }}</label>
                            <textarea v-model="props.form.description" id="description" class="db-field-control" :placeholder="$t('label.expense_title')"></textarea>
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
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "ExpenseCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ['props'],
    data() {
        return {
            loading: {
                isActive: false
            },
            errors: {},
            file: null,
        }
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_expense') };
        },
        categories: function () {
            return this.$store.getters['expenseCategory/lists'] || [];
        },
    },
    mounted() {
        this.$store.dispatch('expenseCategory/lists', { paginate: 0 }).then().catch();
    },
    methods: {
        changeFile: function (e) {
            this.file = e.target.files[0];
        },
        addReset: function () {
            this.errors = {};
            this.file = null;
            this.$store.dispatch('expense/reset').then().catch();
            this.props.form = {
                expense_category_id: null,
                date: new Date().toISOString().slice(0, 10),
                amount: "",
                payment_method: "Cash",
                description: "",
            };
        },
        reset: function () {
            this.errors = {};
            this.file = null;
            appService.sideDrawerHide();
            this.$store.dispatch('expense/reset').then().catch();
            this.props.form = {
                expense_category_id: null,
                date: new Date().toISOString().slice(0, 10),
                amount: "",
                payment_method: "Cash",
                description: "",
            };
        },
        save: function () {
            try {
                this.loading.isActive = true;
                const formData = new FormData();
                formData.append('expense_category_id', this.props.form.expense_category_id || '');
                formData.append('title', this.props.form.description || 'Expense');
                formData.append('date', this.props.form.date || '');
                formData.append('amount', this.props.form.amount || '');
                formData.append('payment_method', this.props.form.payment_method || 1);
                formData.append('note', this.props.form.description || '');
                if (this.file) {
                    formData.append('file', this.file);
                }

                this.$store.dispatch('expense/save', { form: formData, search: this.props.search }).then((res) => {
                    this.loading.isActive = false;
                    appService.sideDrawerHide();
                    alertService.successFlip(this.$store.getters['expense/temp'].isEditing, this.$t('menu.expenses'));
                    this.addReset();
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
