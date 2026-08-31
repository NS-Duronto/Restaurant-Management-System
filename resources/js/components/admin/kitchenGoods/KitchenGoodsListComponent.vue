<template>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $t('menu.kitchen_goods') }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <KitchenGoodsCreateComponent :props="props" />
                </div>
            </div>
            <div class="db-card-body">
                <div class="table-filter-div">
                    <div class="form-group">
                        <input v-model="props.search.name" @input="list" type="text" class="db-field-control" placeholder="কাঁচামাল খুঁজুন...">
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.name') }}</th>
                                <th class="db-table-head-th">{{ $t('label.category') }}</th>
                                <th class="db-table-head-th">{{ $t('label.unit') }}</th>
                                <th class="db-table-head-th">{{ $t('label.current_stock') || 'বর্তমান স্টক' }}</th>
                                <th class="db-table-head-th">{{ $t('label.unit_cost') || 'একক ক্রয়মূল্য' }}</th>
                                <th class="db-table-head-th">{{ $t('label.status') }}</th>
                                <th class="db-table-head-th">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="kitchenGoods.length > 0">
                            <tr class="db-table-body-tr" v-for="item in kitchenGoods" :key="item.id">
                                <td class="db-table-body-td font-semibold text-gray-800 dark:text-gray-200">{{ item.name }}</td>
                                <td class="db-table-body-td">{{ item.category_name || item.kitchen_goods_category_name || '-' }}</td>
                                <td class="db-table-body-td">{{ item.unit_name || item.unit_code || '-' }}</td>
                                <td class="db-table-body-td">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                        :class="item.current_stock > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400'">
                                        {{ Number(item.current_stock).toFixed(2) }} {{ item.unit_code || '' }}
                                    </span>
                                </td>
                                <td class="db-table-body-td font-semibold">
                                    {{ currencyFormat(item.cost_per_unit, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                                </td>
                                <td class="db-table-body-td">
                                    <span :class="statusClass(item.status)">
                                        {{ (item.status == 5 || item.status == 1) ? ($t('label.active') || 'Active') : ($t('label.inactive') || 'Inactive') }}
                                    </span>
                                </td>
                                <td class="db-table-body-td">
                                    <div class="flex items-center gap-2">
                                        <button @click.prevent="edit(item)" class="db-table-action edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button @click.prevent="destroy(item.id)" class="db-table-action delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-800 pt-4" v-if="kitchenGoods.length > 0">
                    <PaginationTextComponent :page="paginationPage" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import KitchenGoodsCreateComponent from "./KitchenGoodsCreateComponent.vue";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "KitchenGoodsListComponent",
    components: {
        KitchenGoodsCreateComponent,
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
                    kitchen_goods_category_id: null,
                    unit_id: null,
                    cost_per_unit: "",
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
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        kitchenGoods: function () {
            return this.$store.getters['kitchenGoods/lists'] || [];
        },
        pagination: function () {
            return this.$store.getters['kitchenGoods/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['kitchenGoods/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('kitchenGoods/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        edit: function (item) {
            appService.sideDrawerShow();
            this.$store.dispatch('kitchenGoods/edit', item.id);
            this.props.form = {
                name: item.name,
                kitchen_goods_category_id: item.kitchen_goods_category_id,
                unit_id: item.unit_id,
                cost_per_unit: item.cost_per_unit,
                status: item.status,
            };
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                if (res.isConfirmed) {
                    this.loading.isActive = true;
                    this.$store.dispatch('kitchenGoods/destroy', { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.clearPromt();
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response?.data?.message || 'Error deleting item');
                    });
                }
            }).catch();
        }
    }
}
</script>
