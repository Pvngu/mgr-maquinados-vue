import Products from '../views/products/Index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/products',
                component: Products,
                name: 'admin.products.index',
                meta: {
                    requireAuth: true,
                    menuParent: "products",
                    menuKey: "products",
                    permission: "products_view"
                }
            },
        ]
    }
];
