import Clients from '../views/clients/Index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/clients',
                component: Clients,
                name: 'admin.clients.index',
                meta: {
                    requireAuth: true,
                    menuParent: "clients",
                    menuKey: "clients",
                    permission: "clients_view"
                }
            },
        ]
    }
];
