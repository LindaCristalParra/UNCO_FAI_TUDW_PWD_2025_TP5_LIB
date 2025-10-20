
<?php
// metodoEncapsulado.php — controlador simple y documentado para acciones sobre reservas

// Siempre devolver JSON
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Modelo/Conector/BaseDatos.php';
require_once __DIR__ . '/../Modelo/Reserva.php';

/**
 * Enviar una respuesta JSON y terminar.
 *
 * @param mixed $data
 * @param int $status HTTP status code
 */
function respondJson($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/** Enviar respuesta 400 con mensaje simple */
function badRequest(string $message): void
{
    respondJson(['error' => $message], 400);
}

/** Validar fecha YYYY-MM-DD */
function validateDate(string $date): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/** Validar hora HH:MM (24h) */
function validateTime(string $time): bool
{
    $t = DateTime::createFromFormat('H:i', $time);
    return $t && $t->format('H:i') === $time;
}

/** Obtener entero de POST o null */
function getPostInt(string $key): ?int
{
    $val = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
    return $val === false ? null : $val;
}

/** Obtener string de POST (sanitizado y truncado) */
function getPostString(string $key, int $maxLen = 255): string
{
    $val = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
    $val = is_string($val) ? trim($val) : '';
    return mb_substr($val, 0, $maxLen);
}

// Acción esperada por GET: crear, actualizar, cancelar
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING) ?: '';

// Validación básica de la acción
if (!in_array($accion, ['crear', 'actualizar', 'cancelar'], true)) {
    respondJson(['error' => 'Acción no válida'], 400);
}

try {
    switch ($accion) {
        case 'crear':
            // Requiere POST con idCancha, fecha (YYYY-MM-DD), hora (HH:MM) y nombre
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                badRequest('Usar POST para crear');
            }

            $idCancha = getPostInt('idCancha');
            $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING) ?: '';
            $hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING) ?: '';
            $nombre = getPostString('nombre', 100);

            if (!$idCancha) badRequest('idCancha no válido');
            if (!validateDate($fecha)) badRequest('fecha no válida (YYYY-MM-DD)');
            if (!validateTime($hora)) badRequest('hora no válida (HH:MM)');
            if ($nombre === '') badRequest('nombre no puede estar vacío');

            // Crear reserva (el modelo requiere más campos; usamos valores por defecto para los opcionales)
            $fechaReservaNow = date('Y-m-d H:i:s');
            $reserva = new Reserva($idCancha, $fecha, $hora, $nombre, '', '', 'confirmada', $fechaReservaNow);
            if ($reserva->insertar()) {
                respondJson(['success' => true], 201);
            }
            respondJson(['error' => 'No se pudo crear la reserva', 'detalle' => $reserva->getMensajeOperacion()], 500);

        case 'actualizar':
            // Requiere POST con id, fecha, hora
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                badRequest('Usar POST para actualizar');
            }

            $id = getPostInt('id');
            $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING) ?: '';
            $hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING) ?: '';

            if (!$id) badRequest('id no válido');
            if (!validateDate($fecha)) badRequest('fecha no válida (YYYY-MM-DD)');
            if (!validateTime($hora)) badRequest('hora no válida (HH:MM)');

            // Cargar reserva existente y modificar
            $modelo = new Reserva(0, '', '', '', '', '', '', '');
            $existente = $modelo->obtener($id);
            if (!$existente) respondJson(['error' => 'Reserva no encontrada'], 404);

            $existente->setFecha($fecha);
            $existente->setHora($hora);
            if ($existente->modificar($id)) {
                respondJson(['success' => true], 200);
            }
            respondJson(['error' => 'No se pudo actualizar', 'detalle' => $existente->getMensajeOperacion()], 500);

        case 'cancelar':
            // Requiere POST con id
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                badRequest('Usar POST para cancelar');
            }

            $id = getPostInt('id');
            if (!$id) badRequest('id no válido');

            $modelo = new Reserva(0, '', '', '', '', '', '', '');
            if ($modelo->cancelarReserva($id)) {
                respondJson(['success' => true], 200);
            }
            respondJson(['error' => 'No se pudo cancelar', 'detalle' => $modelo->getMensajeOperacion()], 500);
    }
} catch (Throwable $e) {
    // Registro mínimo y mensaje genérico
    error_log('metodoEncapsulado error: ' . $e->getMessage());
    respondJson(['error' => 'Error interno del servidor'], 500);
}

