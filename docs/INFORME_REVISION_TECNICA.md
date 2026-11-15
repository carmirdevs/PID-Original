# Informe de revisión técnica del PID original

**Fecha:** 15 de noviembre de 2026  
**Proyecto:** PID 2.0 — investigación doctoral  
**Objeto:** copia depurada del PID original y comprobación de archivos clave en `carmirdevs/PID-Original`.

## 1. Alcance y método

Inspección estática de controladores, modelos, vistas y clases; inventario; comprobación sintáctica mediante `php -l`; revisión básica de seguridad y del esquema SQL. **No** se ejecutó la aplicación con base de datos, ni se realizaron pruebas de penetración. No se comparó íntegramente cada archivo remoto con la copia local.

## 2. Inventario

- Copia depurada: **972 entradas ZIP**, incluidos directorios.
- **94 archivos PHP**; 94/94 superaron `php -l` con PHP 8.4.24 (solo sintaxis).
- Esquema `Basededatos/escolarizado_estructura_sin_datos.sql`: **16 CREATE TABLE** y **0 INSERT INTO**.
- Directorios: `views/`, `controladores/`, `modelos/`, `clases/`, `config/`, `Basededatos/`, `librerias/` y `template/`.

## 3. Evidencias funcionales

| ID | Archivo | Hallazgo | Alcance |
|---|---|---|---|
| EV-COD-001 | `controladores/controlador_instrumentaciones.php` | `cargarArchivo()` llama a `subir_archivo(array('xlsx'), ...)`; registra fecha y contador de modificaciones. | Implementación estática; ejecución no probada |
| EV-COD-002 | `views/instrumentaciones/_formularioSubirArchivo.php` | Interfaz de carga de instrumentaciones XLSX. | Código inspeccionado |
| EV-COD-003 | `controladores/controlador_instrumentaciones.php` | Funciones para cuatro entregas de reportes y registro temporal. | Código inspeccionado |
| EV-COD-004 | `clases/archivosServidor.php` | Uso de `move_uploaded_file()` y visualización mediante Office Web Viewer. | Código inspeccionado |
| EV-COD-005 | `clases/seguridad.php` | Obtiene `session_id` desde URL y consulta permisos por grupo/método. | Código inspeccionado |
| EV-COD-006 | `views/instrumentaciones/reporteInstrumentaciones.php`, `reporteSemanas.php` | Generación de reportes PDF. | Código inspeccionado |
| EV-COD-007 | `Basededatos/escolarizado_estructura_sin_datos.sql` | 16 tablas: archivos, bloques, carreras, grupos, gruposalumnos, imprimir, instrumentaciones, materias, materia_carrera, menus, metodos, metodo_grupo, periodos, sessiones, usuarios, usuario_carrera. | Esquema comprobado |
| EV-COD-008 | `controladores/controlador_session.php` | Comparación directa de contraseña y token de sesión basado en doble MD5. | Código inspeccionado |

**Contexto histórico confirmado por el equipo:** la última versión aceptaba instrumentaciones **XLSX**, no PDF; la modificación consistía en **sustituir el archivo**, no actualizar estructuradamente actividades o fechas. La generación de reportes PDF es una función distinta. No se ha acreditado un flujo íntegro de autorización académica y versionado de planeaciones.

## 4. Observaciones de seguridad

| ID | Prioridad | Observación | Acción recomendada para PID 2.0 |
|---|---|---|---|
| SEG-01 | Crítica | Comparación directa del campo de contraseña; no se observa `password_verify()`. | `password_hash()`, `password_verify()` y migración segura. |
| SEG-02 | Alta | `session_id` transmitido en URL y generación basada en MD5/tiempo. | Sesiones de servidor con cookies seguras y tokens aleatorios. |
| SEG-03 | Alta | Validación limitada de extensión en subida de archivos. | Verificar MIME, contenido XLSX, tamaño, ubicación y errores. |
| SEG-04 | Media-alta | Visualización de Excel mediante URL pública hacia Office Web Viewer. | Evaluar confidencialidad y acceso autenticado. |
| SEG-05 | Alta | No se encontraron indicadores de protección CSRF en búsqueda estática. | Implementar y comprobar tokens CSRF. |
| SEG-06 | Media | Copias y variantes de controladores y manejo de archivos. | Identificar código activo y retirar variantes obsoletas en PID 2.0. |

**Interpretación:** son indicadores de riesgo, no vulnerabilidades explotadas ni prueba de ausencia absoluta de defensas.

## 5. Correspondencia con PID 2.0

**Conservar conceptualmente:** relaciones docente–asignatura–grupo–periodo; recepción documental; permisos; control temporal de entregas; reportes.

**Diseñar y verificar como capacidades nuevas:** unidades y actividades estructuradas; fechas previstas y reales; avance; solicitud justificada de cambios; aprobación por responsable académico; línea base, versiones íntegras, bitácora y trazabilidad.

**Distinciones fundamentales:** sustituir un XLSX no equivale a editar una actividad; contar modificaciones no equivale a conservar versiones; generar PDF no equivale a aceptar PDF como entrada.

## 6. Integridad y trabajo pendiente

Se comprobó en GitHub la presencia de los archivos principales de controladores, seguridad, autenticación, esquema SQL sin datos y documentación de procedencia.

Quedan pendientes:
- Comparación exhaustiva de inventario y hashes entre copia depurada y repositorio remoto.
- Revisión completa de secretos y del historial Git.
- Auditoría de dependencias y versiones.
- Pruebas funcionales en entorno aislado, sin información institucional real.
- Pruebas de controles de seguridad y permisos por ruta.

## 7. Dictamen

**Apto como antecedente para el diagnóstico estático de P1/P2, con reservas.** No está certificado para producción ni auditado exhaustivamente. Mantener privado el repositorio y evitar datos reales. Posponer el etiquetado histórico definitivo hasta verificar integridad y seguridad.

**Nota metodológica:** inspeccionar instrucciones y estructuras demuestra su presencia en el código, pero no acredita ejecución efectiva ni ausencia absoluta de funcionalidades.
