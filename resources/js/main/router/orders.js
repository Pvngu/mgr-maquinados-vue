import Orders from '../views/orders/Index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/orders',
                component: Orders,
                name: 'admin.orders.index',
                meta: {
                    requireAuth: true,
                    menuParent: "orders",
                    menuKey: "orders",
                    permission: "orders_view"
                }
            },
        ]
    }
]
