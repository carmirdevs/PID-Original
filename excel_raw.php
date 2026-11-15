<?php
/**
 * excel_raw.php (mejorado + diagnóstico)
 * Sirve el binario del Excel desde el FS y expone modo diagnóstico con ?mode=info
 *
 * Configura aquí tu ruta real de /archivos si la detección automática no coincide.
 * Ejemplo:
 *   define('ARCHIVOS_BASE_DIR', '/var/www/ESOLARIZADO/archivos');
 */

// -------- CONFIGURACIÓN OPCIONAL --------
// define('ARCHIVOS_BASE_DIR', '/ruta/absoluta/a/archivos');
// ----------------------------------------

// Si no definiste ARCHIVOS_BASE_DIR, intenta detectar automáticamente
if (!defined('ARCHIVOS_BASE_DIR')) {
    $candidates = array(
        realpath(__DIR__ . '/../archivos'),
        realpath(__DIR__ . '/archivos'),
        realpath(__DIR__ . '/../../archivos'),
    );
    foreach ($candidates as $cand) {
        if ($cand && is_dir($cand)) { define('ARCHIVOS_BASE_DIR', $cand); break; }
    }
    if (!defined('ARCHIVOS_BASE_DIR')) {
        define('ARCHIVOS_BASE_DIR', '');
    }
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

// Salida de diagnóstico
if ($mode === 'info') {
    header('Content-Type: application/json; charset=utf-8');
    $info = array(
        'ARCHIVOS_BASE_DIR' => ARCHIVOS_BASE_DIR,
        'file' => $file,
        'resolved_path' => $file ? (ARCHIVOS_BASE_DIR ? (ARCHIVOS_BASE_DIR . DIRECTORY_SEPARATOR . $file) : null) : null,
        'resolved_exists' => ($file && ARCHIVOS_BASE_DIR) ? (is_file(ARCHIVOS_BASE_DIR . DIRECTORY_SEPARATOR . $file) ? true : false) : null,
        'resolved_readable' => ($file && ARCHIVOS_BASE_DIR) ? (is_readable(ARCHIVOS_BASE_DIR . DIRECTORY_SEPARATOR . $file) ? true : false) : null,
        'cwd' => getcwd(),
        'script_dir' => __DIR__,
        'candidates_checked' => isset($candidates) ? $candidates : array(),
        'php_user' => function_exists('posix_geteuid') ? posix_geteuid() : 'n/a',
    );
    echo json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

if (!$file) {
    http_response_code(400);
    echo "Falta parámetro 'file'";
    exit;
}

if (!ARCHIVOS_BASE_DIR) {
    http_response_code(500);
    echo "ARCHIVOS_BASE_DIR no válido. Define la constante correctamente en excel_raw.php";
    exit;
}

$path = ARCHIVOS_BASE_DIR . DIRECTORY_SEPARATOR . $file;

if (!is_file($path)) {
    http_response_code(404);
    echo "Archivo no encontrado: " . htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
    exit;
}

if (!is_readable($path)) {
    http_response_code(403);
    echo "Sin permisos de lectura sobre: " . htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
    exit;
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$ctype = ($ext === 'xlsx')
    ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    : 'application/vnd.ms-excel';

header('Content-Type: ' . $ctype);
header('Content-Disposition: inline; filename="' . basename($file) . '"');
header('X-Content-Type-Options: nosniff');
header('Content-Length: ' . filesize($path));
readfile($path);
