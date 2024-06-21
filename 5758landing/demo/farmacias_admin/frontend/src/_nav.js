export default [
    {
        component: 'CNavItem',
        name: 'Inicio',
        access: 'dashboard',
        to: '/dashboard',
        icon: 'fas fa-tachometer-alt',
    },
    {
        component: 'CNavGroup',
        name: 'Ventas',
        access: 'Ventas',
        to: '',
        icon: 'fas fa-check-circle',
        items: [
            {
                component: 'CNavItem',
                name: 'Listado de ventas',
                access: 'ventas/administrar',
                to: '/ventas/administrar',
            },
            {
                component: 'CNavItem',
                name: 'Envíos',
                access: 'envios/listado',
                to: '/envios/listado',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Inventario',
        access: 'Inventario',
        to: '',
        icon: 'fas fa-box',
        items: [
            {
                component: 'CNavItem',
                name: 'Marcas',
                access: 'inventario/marcas',
                to: '/inventario/marcas',
            },
            {
                component: 'CNavItem',
                name: 'Productos',
                access: 'inventario/productos',
                to: '/inventario/productos',
            },
            {
                component: 'CNavItem',
                name: 'Lotes',
                access: 'inventario/lotes',
                to: '/inventario/lotes',
            },
            {
                component: 'CNavItem',
                name: 'Traslados',
                access: 'inventario/traslados',
                to: '/inventario/traslados',
            },
            {
                component: 'CNavItem',
                name: 'Kardex',
                access: 'inventario/kardex',
                to: '/inventario/kardex',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Localidades',
        access: 'Localidades',
        to: '',
        icon: 'fas fa-store',
        items: [
            {
                component: 'CNavItem',
                name: 'Localidades',
                access: 'localidades/administrar',
                to: '/localidades/administrar',
            },
            {
                component: 'CNavItem',
                name: 'Registro de activos',
                access: 'localidades/activos',
                to: '/localidades/activos',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Clientes',
        access: 'Clientes',
        to: '',
        icon: 'fas fa-users',
        items: [
            {
                component: 'CNavItem',
                name: 'Administrar clientes',
                access: 'clientes/administrar',
                to: '/clientes/administrar',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Proveedores',
        access: 'Proveedores',
        to: '',
        icon: 'fas fa-people-carry',
        items: [
            {
                component: 'CNavItem',
                name: 'Administrar proveedores',
                access: 'proveedores/administrar',
                to: '/proveedores/administrar',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Planilla',
        access: 'Planilla',
        to: '',
        icon: 'fas fa-calculator',
        items: [
            {
                component: 'CNavItem',
                name: 'Administrar planillas',
                access: 'planilla/administrar',
                to: '/planilla/administrar',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Usuarios',
        to: '',
        icon: 'fas fa-user',
        access: 'Usuarios',
        items: [
            {
                component: 'CNavItem',
                access: 'users/admin',
                name: 'Administrar usuarios',
                to: '/usuarios/listado',
            },
            {
                component: 'CNavItem',
                access: 'users/role/admin',
                name: 'Administrar roles',
                to: '/usuarios/roles/listado',
            },
            {
                component: 'CNavItem',
                access: 'users/empleados/admin',
                name: 'Administrar empleados',
                to: '/usuarios/empleados/listado',
            },
        ],
    },
    {
        component: 'CNavGroup',
        name: 'Configuración',
        to: '',
        icon: 'fas fa-cogs',
        access: 'Configuración',
        items: [
            {
                component: 'CNavItem',
                access: 'users/admin',
                name: 'Sistema',
                to: '/configuration/sistema',
            },
        ],
    },
]
