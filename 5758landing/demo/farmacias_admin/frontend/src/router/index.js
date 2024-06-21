import {h, resolveComponent} from 'vue'
import {createRouter, createWebHashHistory} from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout'
import store from "@/store";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: DefaultLayout,
        redirect: '/dashboard',
        children: [
            {
                path: '/dashboard',
                name: 'Dashboard',
                // route level code-splitting
                // this generates a separate chunk (about.[hash].js) for this route
                // which is lazy-loaded when the route is visited.
                component: () =>
                    import(/* webpackChunkName: "dashboard" */ '@/views/windows/default/Dashboard.vue'),
            },
        ],
    },
    {
        path: '/ventas',
        name: 'Ventas',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Listado de ventas',
                path: '/ventas/administrar',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Listado de envíos',
                path: '/envios/listado',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/inventario',
        name: 'Inventario',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Marcas',
                path: '/inventario/marcas',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Productos',
                path: '/inventario/productos',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Lotes',
                path: '/inventario/lotes',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Traslados',
                path: '/inventario/traslados',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Kardex',
                path: '/inventario/kardex',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/localidades',
        name: 'Localidades',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Listado de localidades',
                path: '/localidades/administrar',
                component: () => import('@/views/windows/default/Default.vue'),
            },
            {
                name: 'Activos de empresa',
                path: '/localidades/activos',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/clientes',
        name: 'Clientes',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Administrar clientes',
                path: '/clientes/administrar',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/proveedores',
        name: 'Proveedores',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Administrar proveedores',
                path: '/proveedores/administrar',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/planilla',
        name: 'Planillas',
        component: DefaultLayout,
        redirect: '',
        children: [
            {
                name: 'Administrar planilla',
                path: '/planilla/administrar',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/usuarios',
        name: 'Usuarios',
        component: DefaultLayout,
        redirect: '/usuarios/listado',
        children: [
            {
                name: 'Listado usuarios',
                path: '/usuarios/listado',
                component: () => import('@/views/windows/usuarios/UsuariosListado.vue'),
            },
            {
                name: 'Editar usuario',
                path: '/usuarios/edit/:id',
                component: () => import('@/views/windows/usuarios/UsuariosEditar.vue'),
            },
            {
                name: 'Listado roles',
                path: '/usuarios/roles/listado',
                component: () => import('@/views/windows/usuarios/RolesListado.vue'),
            },
            {
                name: 'Editar rol',
                path: '/usuarios/roles/edit/:id',
                component: () => import('@/views/windows/usuarios/RolesEditar.vue'),
            },
            {
                name: 'Editar empleados',
                path: '/usuarios/empleados/listado',
                component: () => import('@/views/windows/default/Default.vue'),
            },
        ],
    },
    {
        path: '/configuration',
        name: 'Configuración',
        component: DefaultLayout,
        redirect: '/configuration/system',
        children: [
            {
                name: 'Configuración de sistema',
                path: '/configuration/sistema',
                component: () => import('@/views/windows/configuracion/EditarConfig.vue'),
            },
        ],
    },
    {
        path: '/',
        redirect: '/404',
        component: {
            render() {
                return h(resolveComponent('router-view'))
            },
        },
        children: [
            {
                path: '/404',
                name: 'Page404',
                component: () => import('@/views/pages/Page404'),
                meta: {
                    public: true
                }
            },
            {
                path: '/login',
                name: 'Login',
                component: () => import('@/views/pages/Login'),
                meta: {
                    public: true
                }
            },
        ],
    },
]

const router = createRouter({
    history: createWebHashHistory(process.env.BASE_URL),
    routes,
    scrollBehavior() {
        // always scroll to top
        return {top: 0}
    },
})

router.beforeEach((to, from, next) => {
    const rutaPublica = to.matched.some(record => record.meta.public);

    //console.log(to);
    if (to.path === '/404' || to.path === '/login') {
        next();
    }
    else {
        store.dispatch('ValidateLogin', {
            callback: (response) => {

                if (typeof response.logged === 'undefined') {
                    next('/login');
                }
                else {
                    if (!rutaPublica) {
                        if (store.getters.authLogged) {
                            next();
                        }
                        else {
                            window.location.href = '/#/login';
                        }
                    }
                    else {
                        next();
                    }
                }
            }
        });
    }
});

export default router
