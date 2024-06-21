<?php
$accessConfigModule[] = [
    'module' => 'Dashboard',
    'access' => [
        [
            'name' => 'Dashboard',
            'slug' => 'dashboard',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Configuración',
    'access' => [
        [
            'name' => 'Configuración',
            'slug' => 'configuration',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Ventas',
    'access' => [
        [
            'name' => 'Administrar ventas',
            'slug' => 'ventas/administrar',
        ],
        [
            'name' => 'Administrar envíos',
            'slug' => 'envios/listado',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Proveedores',
    'access' => [
        [
            'name' => 'Administrar proveedores',
            'slug' => 'proveedores/administrar',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Clientes',
    'access' => [
        [
            'name' => 'Administrar clientes',
            'slug' => 'clientes/administrar',
        ],
    ],
];


$accessConfigModule[] = [
    'module' => 'Planillas',
    'access' => [
        [
            'name' => 'Administrar planillas',
            'slug' => 'planilla/administrar',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Usuarios',
    'access' => [
        [
            'name' => 'Administrar roles',
            'slug' => 'users/role/admin',
        ],
        [
            'name' => 'Administrar usuarios',
            'slug' => 'users/admin',
        ],
        [
            'name' => 'Administrar empleados',
            'slug' => 'users/empleados/admin',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Inventario',
    'access' => [
        [
            'name' => 'Marcas',
            'slug' => 'inventario/marcas',
        ],
        [
            'name' => 'Productos',
            'slug' => 'inventario/productos',
        ],
        [
            'name' => 'Lotes',
            'slug' => 'inventario/lotes',
        ],
        [
            'name' => 'Traslados',
            'slug' => 'inventario/traslados',
        ],
        [
            'name' => 'Kardex',
            'slug' => 'inventario/kardex',
        ],
    ],
];


$accessConfigModule[] = [
    'module' => 'Localidades',
    'access' => [
        [
            'name' => 'Localidades',
            'slug' => 'localidades/administrar',
        ],
        [
            'name' => 'Registro de activos',
            'slug' => 'localidades/activos',
        ],
    ],
];

$accessConfigModule[] = [
    'module' => 'Planilla',
    'access' => [
        [
            'name' => 'Administra planilla',
            'slug' => 'planilla/administrar',
        ],
    ],
];


define("LgcAccessConfig", $accessConfigModule);
