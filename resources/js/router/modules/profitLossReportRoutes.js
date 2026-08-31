import ProfitLossReportComponent from "../../components/admin/profitLossReport/ProfitLossReportComponent.vue";
import ProfitLossReportListComponent from "../../components/admin/profitLossReport/ProfitLossReportListComponent.vue";

export default [
    {
        path: "/admin/profit-loss-report",
        component: ProfitLossReportComponent,
        name: "admin.profit-loss-report",
        redirect: { name: "admin.profit-loss-report.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "profit-loss-report",
            breadcrumb: "profit_loss_report",
        },
        children: [
            {
                path: "",
                component: ProfitLossReportListComponent,
                name: "admin.profit-loss-report.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "profit-loss-report",
                    breadcrumb: "",
                },
            },
        ],
    },
];
