import PurchaseComponent from "../../components/admin/purchases/PurchaseComponent.vue";
import PurchaseListComponent from "../../components/admin/purchases/PurchaseListComponent.vue";

export default [
    {
        path: "/admin/purchases",
        component: PurchaseComponent,
        name: "admin.purchases",
        redirect: { name: "admin.purchases.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "purchases",
            breadcrumb: "purchases",
        },
        children: [
            {
                path: "",
                component: PurchaseListComponent,
                name: "admin.purchases.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "purchases",
                    breadcrumb: "",
                },
            },
        ],
    },
];
