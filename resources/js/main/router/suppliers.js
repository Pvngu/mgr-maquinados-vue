import Suppliers from '../views/suppliers/Index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/suppliers',
                component: Suppliers,
                name: 'admin.suppliers.index',
                meta: {
                    requireAuth: true,
                    menuParent: "suppliers",
                    menuKey: "suppliers",
                    permission: "suppliers_view"
                }
            },
        ]
    }
];
