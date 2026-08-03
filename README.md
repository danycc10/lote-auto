# Lote Autos

Sistema administrativo para inventario, apartados, clientes, contratos de financiamiento, cobranza, recibos y seguimiento comercial.

## Stack

- PHP 8.3 y Laravel 13
- Livewire 3, Blade y Tailwind CSS 3
- SQLite para desarrollo/pruebas; MySQL o MariaDB recomendado en producción
- Cola y cache en base de datos por defecto

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm ci
npm run build
```

Configura `INITIAL_ADMIN_EMAIL` e `INITIAL_ADMIN_PASSWORD` únicamente para el primer despliegue y ejecuta:

```bash
php artisan db:seed
```

La contraseña inicial debe tener al menos 12 caracteres e incluir mayúscula, minúscula, número y símbolo. Si la configuración ya estaba almacenada en caché, ejecuta `php artisan config:clear` antes del seeder.

El seeder no crea ni promueve usuarios si esas variables no están configuradas.

Para desarrollo:

```bash
composer dev
```

Este comando inicia servidor, worker de cola, logs y Vite. El scheduler se puede ejecutar en otra terminal con:

```bash
php artisan schedule:work
```

## Verificación

```bash
composer analyse
php artisan test
vendor/bin/pint --test
composer audit --locked
npm audit --audit-level=high
npm run build
```

El CI bloquea errores nuevos de PHPStan, pruebas fallidas, vulnerabilidades altas y errores de compilación frontend. Pint sigue siendo informativo mientras se liquida la deuda de formato heredada.

## Arquitectura principal

- `app/Livewire`: interfaz y coordinación de formularios.
- `app/Services/Financiamiento`: reglas transaccionales de contratos, cuotas, pagos, recibos y estados de cuenta.
- `app/Services/Apartados`: creación, conversión y vencimiento de reservas.
- `app/Jobs`: trabajo asíncrono con reintentos.
- `app/Enums`: estados y fórmulas válidas del dominio.
- `tests/Feature/Financiamiento`: invariantes contables y concurrencia.
- `tests/Feature/Security`: autorización y administración de roles.
- `tests/Feature/Operations`: health checks, configuración y backups.

Los nuevos contratos guardan una versión explícita de su fórmula financiera. Los contratos históricos conservan `plana_v1`; los nuevos usan `anualidad_v1`.

## Operación

Consulta [docs/OPERATIONS.md](docs/OPERATIONS.md) antes de desplegar. Incluye:

- variables obligatorias;
- worker y scheduler;
- despliegue y rollback;
- backups, verificación y restauración;
- health checks y observabilidad.

Para instalaciones independientes por lote en HostGator/cPanel consulta también [docs/HOSTGATOR.md](docs/HOSTGATOR.md).

El estado de las fases implementadas y la lista de activación por lote están en [docs/ROADMAP_HOSTGATOR.md](docs/ROADMAP_HOSTGATOR.md).

El endpoint `/up` comprueba arranque, base de datos y cache.
