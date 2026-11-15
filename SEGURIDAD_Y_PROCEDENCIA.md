# PID original — copia de estudio depurada

Este repositorio contiene una copia de referencia del código histórico, no una versión operativa de producción.

## Exclusiones
- `config/config.php` original, por contener credenciales de acceso.
- Volcado SQL original con registros institucionales; se incluye solamente estructura de tablas.
- Archivos XLSX de usuarios, registros de errores, carpetas de validación del servidor y archivos de despliegue no necesarios.
- El ZIP original se conserva separadamente en almacenamiento institucional autorizado; **no debe publicarse ni incorporarse al historial de Git**.

## Configuración local
Copiar `config/config.php.example` a `config/config.php` únicamente en un entorno de prueba y utilizar credenciales nuevas. No conectar a bases institucionales reales.

## Limitaciones
La inspección estática no garantiza que el código sea seguro ni que sus funciones se ejecuten correctamente. Se requiere revisión manual antes de cualquier publicación y pruebas en entorno aislado.

## Precaución
Rotar las credenciales presentes en el archivo histórico si aún estuvieran activas.
