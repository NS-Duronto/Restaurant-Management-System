import WastageComponent from "../../components/admin/wastages/WastageComponent.vue";
import WastageListComponent from "../../components/admin/wastages/WastageListComponent.vue";

export default [
    {
        path: "/admin/wastages",
        component: WastageComponent,
        name: "admin.wastages",
        redirect: { name: "admin.wastages.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "kitchen-goods",
            breadcrumb: "wastages",
        },
        children: [
            {
                path: "",
                component: WastageListComponent,
                name: "admin.wastages.list",
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
