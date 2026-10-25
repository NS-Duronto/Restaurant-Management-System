<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('menu.send_to_kitchen') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <div class="dropdown-group">
                        <ExportComponent />
                        <div class="dropdown-list db-card-filter-dropdown-list transition-all duration-300 scale-y-0 origin-top">
                            <PrintComponent :props="printObj" />
                        </div>
                    </div>
                    <SendToKitchenCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div">
                    <div class="form-group">
                        <input v-model="props.search.issue_no" @input="list" type="text" class="db-field-control" :placeholder="$t('label.search_issue_voucher')">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe" id="print">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.reference_no') }}</th>
                                <th class="db-table-head-th">{{ $t('label.date') }}</th>
                                <th class="db-table-head-th">{{ $t('label.total_items') }}</th>
                                <th class="db-table-head-th">{{ $t('label.issued_by') }}</th>
                                <th class="db-table-head-th">{{ $t('label.note') }}</th>
                                <th class="db-table-head-th hidden-print">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="issues.length > 0">
                            <tr class="db-table-body-tr" v-for="item in issues" :key="item.id">
                                <td class="db-table-body-td font-bold text-orange-600 dark:text-orange-400">#{{ item.send_no }}</td>
                                <td class="db-table-body-td">{{ item.date }}</td>
                                <td class="db-table-body-td font-semibold">{{ item.total_items }} {{ $t('label.items') }}</td>
                                <td class="db-table-body-td font-medium">{{ item.user_name || $t('label.storekeeper') }}</td>
                                <td class="db-table-body-td">{{ item.note || '-' }}</td>
                                <td class="db-table-body-td hidden-print">
                                    <div class="flex items-center gap-2">
                                        <SmIconSidebarModalEditComponent @click="edit(item)" />
                                        <button @click.prevent="destroy(item.id)" class="db-table-action delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="issues.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SendToKitchenCreateComponent from "./SendToKitchenCreateComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import ExportComponent from "../components/buttons/export/ExportComponent.vue";
import PrintComponent from "../components/buttons/export/PrintComponent.vue";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent.vue";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "SendToKitchenListComponent",
    components: {
        SendToKitchenCreateComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox,
        ExportComponent,
        PrintComponent,
        SmIconSidebarModalEditComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            printObj: {
                id: "print",
                popTitle: this.$t('menu.send_to_kitchen')
            },
            props: {
                form: {
                    date: new Date().toISOString().slice(0, 10),
                    note: "",
                    items: [
                        { kitchen_goods_id: null, quantity: "" }
                    ]
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    issue_no: "",
                }
            }
        }
    },
    computed: {
        issues: function () {
            return this.$store.getters['sendToKitchen/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['sendToKitchen/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['sendToKitchen/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('sendToKitchen/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        edit: function (item) {
            this.loading.isActive = true;
            this.$store.dispatch('sendToKitchen/show', item.id).then((res) => {
                this.loading.isActive = false;
                const issue = res.data.data;
                this.props.form = {
                    date: issue.raw_date || issue.date,
                    note: issue.note || '',
                    items: issue.items && issue.items.length > 0 ? issue.items.map(i => ({
                        kitchen_goods_id: i.kitchen_goods_id,
                        quantity: i.quantity,
                    })) : [{ kitchen_goods_id: null, quantity: "" }]
                };
                this.$store.dispatch('sendToKitchen/edit', item.id);
                appService.sideDrawerShow();
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response?.data?.message || 'Error loading issue details');
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('sendToKitchen/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting issue');
                    });
                }
            }).catch();
        }
    }
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
