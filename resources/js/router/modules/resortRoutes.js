import ResortSettingComponent from "../../components/admin/resort/ResortSettingComponent.vue";

export default [
    {
        path: "/admin/resort-settings",
        component: ResortSettingComponent,
        name: "admin.resortSettings",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "resort-settings",
            breadcrumb: "resort_settings",
        },
    },
];
