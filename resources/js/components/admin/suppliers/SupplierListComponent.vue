<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('menu.suppliers') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <SupplierCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div">
                    <div class="form-group">
                        <input v-model="props.search.name" @input="list" type="text" class="db-field-control" placeholder="সাপ্লায়ার খুঁজুন...">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.name') }}</th>
                                <th class="db-table-head-th">{{ $t('label.company_name') || 'কোম্পানি' }}</th>
                                <th class="db-table-head-th">{{ $t('label.phone') }}</th>
                                <th class="db-table-head-th">{{ $t('label.email') }}</th>
                                <th class="db-table-head-th">{{ $t('label.status') }}</th>
                                <th class="db-table-head-th">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="suppliers.length > 0">
                            <tr class="db-table-body-tr" v-for="supplier in suppliers" :key="supplier.id">
                                <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-200">{{ supplier.name }}</td>
                                <td class="db-table-body-td">{{ supplier.company_name || '-' }}</td>
                                <td class="db-table-body-td">{{ supplier.phone || '-' }}</td>
                                <td class="db-table-body-td">{{ supplier.email || '-' }}</td>
                                <td class="db-table-body-td">
                                    <span :class="statusClass(supplier.status)">
                                        {{ (supplier.status == 5 || supplier.status == 1) ? ($t('label.active') || 'Active') : ($t('label.inactive') || 'Inactive') }}
                                    </span>
                                </td>
                                <td class="db-table-body-td">
                                    <div class="flex items-center gap-2">
                                        <button @click.prevent="edit(supplier)" class="db-table-action edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button @click.prevent="destroy(supplier.id)" class="db-table-action delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="suppliers.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SupplierCreateComponent from "./SupplierCreateComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "SupplierListComponent",
    components: {
        SupplierCreateComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive")
                }
            },
            props: {
                form: {
                    name: "",
                    company_name: "",
                    phone: "",
                    email: "",
                    address: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                }
            }
        }
    },
    computed: {
        suppliers: function () {
            return this.$store.getters['supplier/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['supplier/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['supplier/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('supplier/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        edit: function (supplier) {
            appService.sideDrawerShow();
            this.$store.dispatch('supplier/edit', supplier.id);
            this.props.form = {
                name: supplier.name,
                company_name: supplier.company_name,
                phone: supplier.phone,
                email: supplier.email,
                address: supplier.address,
                status: supplier.status,
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('supplier/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting supplier');
                    });
                }
            }).catch();
        }
    }
}
</script>
