import Categories from '../views/categories/Index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/categories',
                component: Categories,
                name: 'admin.categories.index',
                meta: {
                    requireAuth: true,
                    menuParent: "categories",
                    menuKey: "categories",
                    permission: "categories_view"
                }
            },
        ]
    }
];
