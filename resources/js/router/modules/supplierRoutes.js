import SupplierComponent from "../../components/admin/suppliers/SupplierComponent.vue";
import SupplierListComponent from "../../components/admin/suppliers/SupplierListComponent.vue";

export default [
    {
        path: "/admin/suppliers",
        component: SupplierComponent,
        name: "admin.suppliers",
        redirect: { name: "admin.suppliers.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "suppliers",
            breadcrumb: "suppliers",
        },
        children: [
            {
                path: "",
                component: SupplierListComponent,
                name: "admin.suppliers.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "suppliers",
                    breadcrumb: "",
                },
            },
        ],
    },
];
