import { createRouter, createWebHistory } from "vue-router";
import ENV from '../config/env';
import appService from "../services/appService";
import DashboardComponent from "../components/admin/dashboard/DashboardComponent";
import NotFoundComponent from "../components/frontend/otherPage/NotFoundComponent";
import ExceptionComponent from "../components/frontend/otherPage/ExceptionComponent";
import store from "../store";
import authRoutes from "./modules/authRoutes";
import settingRoutes from "./modules/settingRoutes";
import offerRoutes from "./modules/offerRoutes";
import itemRoutes from "./modules/itemRoutes";
import customerRoutes from "./modules/customerRoutes";
import administratorRoutes from "./modules/administratorRoutes";
import employeeRoutes from "./modules/employeeRoutes";
import salesReportRoutes from "./modules/salesReportRoutes";
import itemsReportRoutes from "./modules/itemsReportRoutes";
import posRoutes from "./modules/posRoutes";
import profileRoutes from "./modules/profileRoutes";
import posOrderRoutes from "./modules/posOrderRoutes";
import transactionRoutes from "./modules/transactionRoutes";
import creditBalanceReportRoutes from "./modules/creditBalanceReportRoutes";
import tableOrderRoutes from "./modules/tableOrderRoutes";
import adminTableOrderRoutes from "./modules/adminTableOrderRoutes";
import diningTableRoutes from "./modules/diningTableRoutes";
import kitchenDisplaySystemRoutes from "./modules/kitchenDisplaySystemRoutes";
import orderStatusScreenRoutes from "./modules/orderStatusScreenRoutes";
import waiterRoutes from "./modules/waiterRoutes";
import chefRoutes from "./modules/chefRoutes";
import expenseRoutes from "./modules/expenseRoutes";
import supplierRoutes from "./modules/supplierRoutes";
import purchaseRoutes from "./modules/purchaseRoutes";
import kitchenGoodsRoutes from "./modules/kitchenGoodsRoutes";
import sendToKitchenRoutes from "./modules/sendToKitchenRoutes";
import profitLossReportRoutes from "./modules/profitLossReportRoutes";
import resortRoutes from "./modules/resortRoutes";
import ResortLandingComponent from "../components/frontend/resort/ResortLandingComponent.vue";
import RmsFoodComponent from "../components/frontend/resort/RmsFoodComponent.vue";


const baseRoutes = [
    {
        path: "/",
        component: ResortLandingComponent,
        name: "resort.landing",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/rms",
        name: "frontend.rms",
        redirect: to => {
            const slug = to.query.table || "table-1";
            return { name: "table.menu.table", params: { slug: slug } };
        },
        meta: {
            isFrontend: false,
            isTable: true,
            auth: false,
        },
    },
    {
        path: "/dashboard",
        redirect: { name: "admin.dashboard" },
    },
    {
        path: "/:pathMatch(.*)*",
        name: "route.notFound",
        component: NotFoundComponent,
        meta: {
            isFrontend: true,
        },
    },
    {
        path: "/exception",
        name: "route.exception",
        component: ExceptionComponent,
    },
    {
        path: "/admin/dashboard",
        component: DashboardComponent,
        name: "admin.dashboard",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
];

export const routes = baseRoutes.concat(
    authRoutes,
    settingRoutes,
    offerRoutes,
    itemRoutes,
    customerRoutes,
    administratorRoutes,
    employeeRoutes,
    salesReportRoutes,
    itemsReportRoutes,
    profileRoutes,
    posRoutes,
    posOrderRoutes,
    transactionRoutes,
    creditBalanceReportRoutes,
    tableOrderRoutes,
    adminTableOrderRoutes,
    diningTableRoutes,
    kitchenDisplaySystemRoutes,
    orderStatusScreenRoutes,
    waiterRoutes,
    chefRoutes,
    expenseRoutes,
    supplierRoutes,
    purchaseRoutes,
    kitchenGoodsRoutes,
    sendToKitchenRoutes,
    profitLossReportRoutes,
    resortRoutes
);

const permission = store.getters.authPermission;
appService.recursiveRouter(routes, permission);

const API_URL = ENV.API_URL;
const router = createRouter({
    linkActiveClass: "active",
    mode: 'history',
    history: createWebHistory(),
    routes,
});

function resolveLandingRoute(permission) {
    if (Array.isArray(permission) && permission.length) {
        const accessible = permission.find((p) => p.access);
        if (accessible && accessible.url) {
            const match = router.getRoutes()
                .filter((r) =>
                    r.meta.isFrontend === false &&
                    r.meta.permissionUrl === accessible.url &&
                    r.name
                )
                .sort((a, b) => a.path.length - b.path.length)[0];
            if (match) {
                return { name: match.name };
            }
        }
    }
    // No accessible permission found — avoid bouncing them to a dashboard
    // they can't see; land on the exception page instead.
    return { name: "route.exception" };
}

function isSessionExpired() {
    const lastActivity = store.getters.lastActivity;
    const sessionLifetimeMinutes = Number(
        store.getters.sessionLifetime ||
        store.getters['frontendSetting/lists']?.site_session_lifetime ||
        120
    );
    if (lastActivity && sessionLifetimeMinutes > 0) {
        return (Date.now() - Number(lastActivity)) > (sessionLifetimeMinutes * 60 * 1000);
    }
    return false;
}

router.beforeEach((to, from, next) => {
    if (to.meta.auth === true) {
        if (!store.getters.authStatus || isSessionExpired()) {
            if (store.getters.authStatus) {
                store.commit('authLogout');
            }
            next({ name: "auth.login" });
            return;
        }

        if (to.meta.isFrontend === false) {
            if (to.meta.access === false) {
                next({
                    name: "route.exception",
                });
            } else {
                next();
            }
        } else {
            next();
        }
    } else if (to.name === "auth.login" && store.getters.authStatus) {
        if (isSessionExpired()) {
            store.commit('authLogout');
            next();
            return;
        }
        next(resolveLandingRoute(store.getters.authPermission));
    } else {
        next();
    }
});
export default router;
