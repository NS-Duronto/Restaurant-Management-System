import ItemComponent from "../../components/admin/items/ItemComponent";
import ItemListComponent from "../../components/admin/items/ItemListComponent";
import ItemShowComponent from "../../components/admin/items/ItemShowComponent";
import ItemCategoryComponent from "../../components/admin/settings/ItemCategory/ItemCategoryComponent";
import ItemCategoryListComponent from "../../components/admin/settings/ItemCategory/ItemCateogryListComponent";
import ItemCategoryShowComponent from "../../components/admin/settings/ItemCategory/ItemCategoryShowComponent";
import TaxComponent from "../../components/admin/settings/Tax/TaxComponent";
import TaxListComponent from "../../components/admin/settings/Tax/TaxListComponent";

export default [
    {
        path: '/admin/items',
        component: ItemComponent,
        name: 'admin.items',
        redirect: {name: 'admin.items.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'items',
            breadcrumb: 'items'
        },
        children: [
            {
                path: '',
                component: ItemListComponent,
                name: 'admin.items.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'items',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: ItemShowComponent,
                name: "admin.item.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "items",
                    breadcrumb: "view",
                },
            }
        ]
    },
    {
        path: '/admin/item-categories',
        component: ItemCategoryComponent,
        name: 'admin.itemCategories',
        redirect: { name: 'admin.itemCategories.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'item-categories',
            breadcrumb: 'item_categories'
        },
        children: [
            {
                path: '',
                component: ItemCategoryListComponent,
                name: 'admin.itemCategories.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'item-categories',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: ItemCategoryShowComponent,
                name: "admin.settings.itemCategory.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "item-categories",
                    breadcrumb: "view",
                },
            }
        ]
    },
    {
        path: '/admin/taxes',
        component: TaxComponent,
        name: 'admin.taxes',
        redirect: { name: 'admin.taxes.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'taxes',
            breadcrumb: 'taxes'
        },
        children: [
            {
                path: '',
                component: TaxListComponent,
                name: 'admin.taxes.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'taxes',
                    breadcrumb: ''
                },
            }
        ]
    }
]
