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
                <div class="table-filter-div flex flex-wrap items-center justify-between gap-3">
                    <div class="form-group flex-1 min-w-[200px]">
                        <input v-model="props.search.name" @input="list(1)" type="text" class="db-field-control" :placeholder="$t('label.search_raw_material')">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="filterStock(false)"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition"
                            :class="!props.search.low_stock ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-700'">
                            {{ $t('label.all_materials') || 'সকল কাঁচামাল' }}
                        </button>
                        <button type="button" @click="filterStock(true)"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition flex items-center gap-1.5"
                            :class="props.search.low_stock ? 'bg-red-600 text-white border-red-600' : 'bg-white dark:bg-gray-800 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800/40'">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            {{ $t('label.low_stock_only') || 'কম স্টক (Low Stock)' }}
                        </button>
                    </div>
                </div>

                <div class="db-table-responsive">
                    <table class="db-table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.name') }}</th>
                                <th class="db-table-head-th">{{ $t('label.category') }}</th>
                                <th class="db-table-head-th">{{ $t('label.unit') }}</th>
                                <th class="db-table-head-th">{{ $t('label.current_stock') }}</th>
                                <th class="db-table-head-th">{{ $t('label.unit_cost') }}</th>
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
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                            :class="item.current_stock > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400'">
                                            {{ Number(item.current_stock).toFixed(2) }} {{ item.unit_code || '' }}
                                        </span>
                                        <span v-if="item.is_low_stock" class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 flex items-center gap-1">
                                            <i class="fa-solid fa-triangle-exclamation"></i> {{ $t('label.low_stock') || 'কম স্টক' }} (Min: {{ item.alert_quantity }})
                                        </span>
                                    </div>
                                </td>
                                <td class="db-table-body-td font-semibold">
                                    {{ currencyFormat(item.cost_per_unit) }}
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
                    alert_quantity: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                    name: "",
                    low_stock: null,
                }
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'] || {};
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
        currencyFormat: function (amount) {
            return appService.currencyFormat(
                amount,
                this.setting?.site_digit_after_decimal_point,
                this.setting?.site_default_currency_symbol,
                this.setting?.site_currency_position
            );
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
                alert_quantity: item.alert_quantity,
                status: item.status,
            };
        },
        filterStock: function (isLowStock) {
            this.props.search.low_stock = isLowStock ? 1 : null;
            this.list(1);
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
