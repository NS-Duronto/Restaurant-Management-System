import SendToKitchenComponent from "../../components/admin/sendToKitchen/SendToKitchenComponent.vue";
import SendToKitchenListComponent from "../../components/admin/sendToKitchen/SendToKitchenListComponent.vue";

export default [
    {
        path: "/admin/send-to-kitchen",
        component: SendToKitchenComponent,
        name: "admin.send-to-kitchen",
        redirect: { name: "admin.send-to-kitchen.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "send-to-kitchen",
            breadcrumb: "send_to_kitchen",
        },
        children: [
            {
                path: "",
                component: SendToKitchenListComponent,
                name: "admin.send-to-kitchen.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "send-to-kitchen",
                    breadcrumb: "",
                },
            },
        ],
    },
];
