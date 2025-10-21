# UNCO_FAI_TUDW_PWD_2025_TP5_LIB

Sistema de Reservas de Canchas de Fútbol

## Correr programa

### Instalar dependencias con Composer
Ejecutar:
php composer.phar install

### Si aparece error de OpenSSL

**Solución:**
1. Abrir el archivo `C:\xampp\php\php.ini` (o donde tengas PHP instalado)
2. Buscar la línea: `;extension=openssl`
3. Quitar el `;` para que quede: `extension=openssl`
4. Guardar el archivo
5. Ejecutar nuevamente: `php composer.phar install`

### 3. Ejecutar el proyecto
- Iniciar Apache y MySQL desde XAMPP
- Abrir en el navegador: `http://localhost/UNCO_FAI_TUDW_PWD_2025_TP5_LIB/Vista/Reserva/calendario.php`

## Librerías utilizadas

- **Carbon** (nesbot/carbon): Manejo avanzado de fechas y horarios
- **Symfony Mailer**: Envío de correos electrónicos de confirmación

## Estructura del proyecto

Control/          → Controladores
Modelo/           → Modelos y conexión a BD
Vista/            → Vistas (HTML/PHP)
vendor/           → Librerías (generado automáticamente al instalar composer)
composer.json     → Dependencias del proyecto


