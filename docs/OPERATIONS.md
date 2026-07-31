# Operación y despliegue

## Requisitos de producción

- PHP 8.3 o superior con `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo` y el driver de base correspondiente.
- Node.js 22 para compilar assets.
- MySQL/MariaDB para despliegues con concurrencia media o alta.
- Un proceso supervisor para `queue:work`.
- Cron con acceso al mismo release y archivo `.env`.
- HTTPS y el directorio público del servidor apuntando exclusivamente a `public/`.

Variables mínimas:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dominio.example
APP_TIMEZONE=America/Matamoros

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

DB_BACKUP_ENABLED=true
DB_BACKUP_KEEP_DAYS=14
DB_SLOW_QUERY_MS=500

REPORTES_DISK=local
REPORTES_EXPIRATION_HOURS=24
REPORTES_FAILED_RETENTION_DAYS=7
```

No reutilices `INITIAL_ADMIN_PASSWORD` después del bootstrap. Déjala vacía y rota cualquier valor que haya sido expuesto.

## Despliegue

Haz un respaldo antes de ejecutar migraciones:

```bash
php artisan app:backup-database
```

Secuencia recomendada:

```bash
php artisan down --retry=60
composer install --no-dev --classmap-authoritative --no-interaction
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan reload
php artisan up
```

En un pipeline maduro, compila los assets en CI y despliega un artefacto inmutable en vez de compilar sobre el servidor.

Verificaciones posteriores:

```bash
php artisan about
php artisan migrate:status
php artisan schedule:list
php artisan queue:failed
curl --fail https://dominio.example/up
```

`/up` devuelve error si Laravel, la base de datos o el cache no están disponibles.

## Worker de cola

Ejemplo de Supervisor:

```ini
[program:lote-autos-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/lote-autos/artisan queue:work database --queue=default --sleep=3 --tries=3 --timeout=300 --max-time=3600
directory=/var/www/lote-autos
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/lote-autos-worker.log
stopwaitsecs=310
```

El timeout del worker debe ser menor que `DB_QUEUE_RETRY_AFTER`. La configuración propuesta permite hasta cinco minutos para exportaciones grandes y usa 360 segundos como tiempo de reintento.

## Scheduler

Registra una sola entrada cron por servidor:

```cron
* * * * * cd /var/www/lote-autos && php artisan schedule:run >> /dev/null 2>&1
```

Las tareas usan exclusión mutua y `onOneServer`. Todos los nodos deben compartir un cache compatible con locks atómicos.

El scheduler también ejecuta diariamente `reportes:limpiar-expirados`. El comando elimina los archivos privados cuya descarga expiró y conserva los reportes fallidos durante `REPORTES_FAILED_RETENTION_DAYS` para facilitar el diagnóstico. Puede ejecutarse manualmente con:

```bash
php artisan reportes:limpiar-expirados
```

## Backups

El scheduler ejecuta diariamente:

```bash
php artisan app:backup-database
```

El comando:

- crea copias consistentes para SQLite y MySQL/MariaDB;
- usa `--single-transaction` en MySQL/MariaDB;
- escribe un archivo `.sha256`;
- elimina archivos que exceden `DB_BACKUP_KEEP_DAYS`;
- guarda por defecto en `storage/app/private/backups`.

Ejecutar con retención puntual:

```bash
php artisan app:backup-database --keep=30
```

La copia local no sustituye un respaldo externo. Sincroniza el directorio hacia almacenamiento cifrado, con versionado y credenciales de sólo escritura. Objetivo inicial recomendado: RPO de 24 horas y prueba de restauración mensual.

### Verificación

Linux:

```bash
cd storage/app/private/backups
sha256sum --check database-AAAAmmdd-HHMMSS-xxxx.sqlite.sha256
```

Para MySQL, el archivo de checksum referencia el `.sql` correspondiente.

### Restauración SQLite

1. Activa mantenimiento y detén workers.
2. Verifica el checksum.
3. Conserva una copia de la base actual.
4. Sustituye el archivo configurado en `DB_DATABASE`.
5. Ejecuta `php artisan migrate:status` y pruebas funcionales.
6. Reinicia workers y desactiva mantenimiento.

### Restauración MySQL/MariaDB

Restaura primero en una base vacía de validación:

```bash
mysql --host=HOST --user=USUARIO --password BASE_VALIDACION < database-AAAAmmdd-HHMMSS-xxxx.sql
```

Verifica conteos, folios, saldos de contratos y recibos antes de promoverla. No restaures directamente sobre producción sin conservar la base anterior.

## Rollback

El rollback de aplicación debe desplegar el artefacto anterior y ejecutar:

```bash
php artisan optimize
php artisan reload
```

No ejecutes `migrate:rollback` automáticamente: una migración puede haber transformado o eliminado datos. Si el release nuevo escribió datos incompatibles, restaura el backup previo en una base paralela y valida antes del cambio.

## Monitoreo

Alertas mínimas:

- `/up` sin respuesta o estado distinto de 200;
- jobs en `failed_jobs`;
- antigüedad del último archivo de backup;
- espacio libre en disco;
- errores y consultas lentas en logs;
- crecimiento de cola y tiempo hasta procesar un Job;
- vencimientos o notificaciones programadas sin ejecutar.

Los logs de consultas lentas incluyen SQL con placeholders, conexión y duración; no incluyen bindings para evitar fuga de datos personales.
