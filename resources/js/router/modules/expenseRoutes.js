import ExpenseComponent from "../../components/admin/expenses/ExpenseComponent.vue";
import ExpenseListComponent from "../../components/admin/expenses/ExpenseListComponent.vue";
import ExpenseCategoryComponent from "../../components/admin/expenses/ExpenseCategoryComponent.vue";
import ExpenseCategoryListComponent from "../../components/admin/expenses/ExpenseCategoryListComponent.vue";

export default [
    {
        path: "/admin/expenses",
        component: ExpenseComponent,
        name: "admin.expenses",
        redirect: { name: "admin.expenses.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "expenses",
            breadcrumb: "expenses",
        },
        children: [
            {
                path: "",
                component: ExpenseListComponent,
                name: "admin.expenses.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "expenses",
                    breadcrumb: "",
                },
            },
        ],
    },
    {
        path: "/admin/expense-categories",
        component: ExpenseCategoryComponent,
        name: "admin.expense-categories",
        redirect: { name: "admin.expense-categories.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "expenses",
            breadcrumb: "expense_categories",
        },
        children: [
            {
                path: "",
                component: ExpenseCategoryListComponent,
                name: "admin.expense-categories.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "expenses",
                    breadcrumb: "",
                },
            },
        ],
    },
];
