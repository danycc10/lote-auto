# Despliegue por lote en HostGator

Cada lote debe utilizar una instalación, base de datos, archivo `.env`, almacenamiento y cuenta administrativa independientes.

## Verificación previa

Selecciona PHP 8.3 en MultiPHP Manager y ejecuta por SSH:

```bash
php artisan hosting:verificar --strict
```

No publiques la instalación si el comando devuelve errores. Los avisos deben revisarse antes de habilitar usuarios reales.

## Estructura de archivos

Para un dominio adicional o subdominio, configura su document root hacia:

```text
/home/USUARIO/apps/NOMBRE_LOTE/public
```

Para el dominio principal, conserva la aplicación fuera de `public_html`:

```text
/home/USUARIO/apps/NOMBRE_LOTE
/home/USUARIO/public_html
```

Copia únicamente el contenido de `public/` a `public_html`, ajusta en `public_html/index.php` las rutas hacia `vendor/autoload.php`, `bootstrap/app.php` y `storage/framework/maintenance.php`, y configura:

```dotenv
APP_PUBLIC_PATH=/home/USUARIO/public_html
```

Nunca copies `.env`, `artisan`, `composer.json`, `vendor/`, `storage/` ni el código de la aplicación dentro de la raíz pública.

## Secuencia de despliegue

Compila los assets antes de transferir el release. En el servidor:

```bash
php artisan down --retry=60
php artisan app:backup-database
composer install --no-dev --classmap-authoritative --no-interaction
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan hosting:verificar --strict
php artisan up
```

Después del despliegue comprueba `/up`, el inicio de sesión, una imagen pública, un documento privado y la generación de un reporte.

## Cron y cola en hosting compartido

Configura la cola para que el scheduler procese trabajos en intervalos acotados:

```dotenv
QUEUE_CONNECTION=database
HOSTING_QUEUE_WORKER_MODE=cron
DB_QUEUE_RETRY_AFTER=360
HOSTING_QUEUE_TIMEOUT=300
HOSTING_QUEUE_MAX_TIME=50
```

Agrega un único cron cada minuto desde cPanel, sustituyendo la ruta del usuario y confirmando el binario PHP 8.3 disponible en la cuenta:

```cron
* * * * * cd /home/USUARIO/apps/NOMBRE_LOTE && /opt/cpanel/ea-php83/root/usr/bin/php artisan schedule:run >> storage/logs/cron.log 2>&1
```

El scheduler utiliza bloqueo de base de datos para impedir workers programados simultáneos. `HOSTING_QUEUE_MAX_TIME` limita el ciclo completo, pero no interrumpe un Job que ya esté ejecutándose. Si el plan no permite que una exportación individual alcance `HOSTING_QUEUE_TIMEOUT`, limita el rango del reporte o utiliza un VPS.

En VPS con Supervisor configura `HOSTING_QUEUE_WORKER_MODE=supervisor`. Si otro sistema administra el worker, usa `external`.

## Backup externo

El respaldo local comparte el mismo riesgo que la cuenta de hosting. Configura un bucket S3 o un proveedor compatible con S3:

```dotenv
DB_BACKUP_REMOTE_DISK=s3
DB_BACKUP_REMOTE_PREFIX=backups/IDENTIFICADOR_LOTE
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_S3_SERVER_SIDE_ENCRYPTION=AES256
```

`app:backup-database` copia el respaldo y su archivo SHA-256 al disco remoto y aplica la misma retención configurada. Usa credenciales limitadas al prefijo de ese lote; no compartas claves entre instalaciones.
