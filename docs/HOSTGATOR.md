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
