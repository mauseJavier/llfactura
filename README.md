# LLFactura - Facturación Electrónica


LLFactura es una aplicación web de facturación electrónica diseñada para simplificar la emisión, gestión y almacenamiento de facturas digitales de manera eficiente y segura.

## Características

- **Generación de facturas electrónicas** en cumplimiento con las normativas vigentes.
- **Gestor de clientes y productos** para facilitar la facturación.
- **Envió automático de facturas** por correo electrónico.
- **Reportes y análisis de ventas** en tiempo real.
- **Seguridad y almacenamiento en la nube** para acceso desde cualquier dispositivo.
- **Integración con sistemas contables y APIs externas**.

## Tecnologías Utilizadas

- **Backend:** Laravel 11 con Livewire 3
- **Base de Datos:** MySQL (Percona Server 8.0)
- **Frontend:** Blade + Livewire
- **Contenedores:** Docker
- **Automatización de Procesos:** n8n

## Instalación

### Requisitos previos

- Docker y Docker Compose
- PHP 8.2+
- Composer
- Node.js y NPM

### Pasos de instalación

1. Clonar el repositorio:
   ```sh
   git clone https://github.com/tuusuario/llfactura.git
   cd llfactura
   ```
2. Copiar el archivo de configuración:
   ```sh
   cp .env.example .env
   ```
3. Configurar las variables de entorno en `.env`.
4. Construir y ejecutar los contenedores Docker:
   ```sh
   docker-compose up -d --build
   ```
5. Otorgar permisos a las carpetas necesarias:
   ```sh
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```
6. Instalar dependencias:
   ```sh
   composer install
   npm install && npm run build
   ```
7. Generar la clave de aplicación y ejecutar migraciones:
   ```sh
   php artisan key:generate
   php artisan migrate --seed
   ```

## Uso

1. Acceder a la aplicación desde el navegador:
   ```
   http://localhost
   ```
2. Iniciar sesión y comenzar a emitir facturas.

## Contribuir

Las contribuciones son bienvenidas. Si deseas contribuir, por favor sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -m 'Agrega nueva funcionalidad'`).
4. Envía un pull request.

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más información.

## Contacto

Para consultas o soporte, visita [LLFactura](https://llfactura.com) o contáctanos en [soporte@llfactura.com](mailto\:soporte@llfactura.com).

