import KitchenGoodsComponent from "../../components/admin/kitchenGoods/KitchenGoodsComponent.vue";
import KitchenGoodsListComponent from "../../components/admin/kitchenGoods/KitchenGoodsListComponent.vue";

export default [
    {
        path: "/admin/kitchen-goods",
        component: KitchenGoodsComponent,
        name: "admin.kitchen-goods",
        redirect: { name: "admin.kitchen-goods.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "kitchen-goods",
            breadcrumb: "kitchen_goods",
        },
        children: [
            {
                path: "",
                component: KitchenGoodsListComponent,
                name: "admin.kitchen-goods.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "kitchen-goods",
                    breadcrumb: "",
                },
            },
        ],
    },
];
