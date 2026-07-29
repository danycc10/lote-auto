<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modo demo
    |--------------------------------------------------------------------------
    |
    | Convierte la aplicación web en una experiencia de solo lectura. Las
    | tareas de consola permanecen disponibles para preparar y restablecer los
    | datos sintéticos de la instancia demo.
    |
    */

    'enabled' => (bool) env('APP_DEMO_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Escrituras administrativas por consola
    |--------------------------------------------------------------------------
    |
    | Permite ejecutar seeders para preparar o restablecer los datos sintéticos.
    | Los comandos programados de negocio se detienen de forma independiente.
    |
    */

    'allow_console_writes' => (bool) env('DEMO_ALLOW_CONSOLE_WRITES', true),

    /*
    |--------------------------------------------------------------------------
    | Rutas de escritura permitidas
    |--------------------------------------------------------------------------
    |
    | Estas operaciones son necesarias para entrar, salir y confirmar la
    | identidad, pero no modifican los datos de negocio de la demostración.
    |
    */

    'allowed_write_routes' => [
        'login.store',
        'logout',
        'two-factor.login.store',
        'password.confirm.store',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rutas sensibles bloqueadas
    |--------------------------------------------------------------------------
    |
    | Aunque sean consultas GET, estas rutas exponen secretos o credenciales
    | de la cuenta compartida y no deben estar disponibles en la demostración.
    |
    */

    'blocked_routes' => [
        'two-factor.qr-code',
        'two-factor.recovery-codes',
        'two-factor.secret-key',
    ],

    /*
    |--------------------------------------------------------------------------
    | Acciones Livewire bloqueadas
    |--------------------------------------------------------------------------
    |
    | Livewire transporta lecturas y escrituras por el mismo endpoint. Por
    | ello se interceptan únicamente métodos con intención mutadora.
    |
    */

    'blocked_livewire_method_prefixes' => [
        'add',
        'actualizar',
        'cambiar',
        'confirmTwoFactorAuthentication',
        'confirmarCancelacion',
        'confirmarEnvio',
        'create',
        'crear',
        'delete',
        'disable',
        'eliminar',
        'enable',
        'enviar',
        'guardar',
        'leave',
        'logoutOtherBrowserSessions',
        'marcar',
        'regenerate',
        'remove',
        'save',
        'subir',
        'toggle',
        'update',
    ],
];
