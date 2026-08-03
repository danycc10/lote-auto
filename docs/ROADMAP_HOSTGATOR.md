# Roadmap para instalaciones independientes en HostGator

Este roadmap parte de una instalación completa e independiente por lote: dominio o subdominio, código, `.env`, base de datos, almacenamiento y administrador propios. No se implementa multitenencia compartida.

## Estado de las fases

### Fase 1 — Compatibilidad y despliegue seguro

Estado: completada en `ed5c8d7`.

- Verificación ejecutable de PHP, extensiones, permisos, base de datos, cache, enlace de storage y assets.
- Compatibilidad con `public_html` sin publicar el código ni `.env`.
- Validaciones estrictas específicas de producción.

### Fase 2 — Cola y scheduler en cPanel

Estado: completada en `a40a961`.

- Worker de cola acotado, ejecutado cada minuto por el scheduler.
- Límites de tiempo y reintentos compatibles con hosting compartido.
- Latidos persistentes para detectar cron o worker detenidos.

### Fase 3 — Respaldo externo

Estado: completada en `5f747fa`.

- Respaldo local con checksum SHA-256 y retención.
- Réplica a S3 o almacenamiento compatible con S3.
- Prefijo y credenciales independientes por lote.

### Fase 4 — Aprovisionamiento por lote

Estado: completada en `47567f3`.

- Comando idempotente para registrar nombre, slug, UUID y versión de la instalación.
- Creación o validación del administrador inicial.
- Protección contra aprovisionamiento accidental de una instalación existente.

### Fase 5 — Visibilidad operativa

Estado: completada en `db42b5d`.

- Panel de salud restringido por permiso.
- Estado de scheduler, cola, backups, almacenamiento y trabajos fallidos.
- Ayuda contextual, actualización Livewire y ocultamiento de secretos.

### Fase 6 — Seguridad y retención

Estado: completada en `1bb2ad9` y `b783c63`.

- Permisos del menú alineados con los permisos reales de cada ruta.
- Rotación diaria de logs y cifrado de sesiones recomendados para producción.
- Limpieza programada de tokens, restablecimientos y registros históricos de cola.

## Activación obligatoria por cada lote

Estas acciones dependen de la cuenta de cPanel y no se realizan desde el repositorio:

1. Crear una base de datos y un usuario MySQL exclusivos.
2. Crear el dominio o subdominio y apuntarlo a `public/`, o aplicar la estructura segura documentada para `public_html`.
3. Crear un `.env` único con `APP_KEY`, URL HTTPS, SMTP, base de datos y credenciales S3 propias.
4. Ejecutar migraciones y `lote:aprovisionar` una sola vez.
5. Crear el cron de cPanel cada minuto para `php artisan schedule:run`.
6. Ejecutar `php artisan hosting:verificar --strict` y resolver todos los errores.
7. Generar un respaldo y comprobar una restauración antes de operar con datos reales.

## Seguimiento posterior al lanzamiento

- Revisar semanalmente el panel de salud y los trabajos fallidos.
- Probar trimestralmente una restauración completa en un entorno aislado.
- Aplicar actualizaciones de dependencias primero en una copia del lote y desplegar después del respaldo.
- Migrar a VPS si los reportes exceden los límites de ejecución del plan, el cron no puede correr cada minuto o el almacenamiento se aproxima a su cuota.

La guía exacta de variables, comandos y estructura del hosting está en [HOSTGATOR.md](HOSTGATOR.md).
