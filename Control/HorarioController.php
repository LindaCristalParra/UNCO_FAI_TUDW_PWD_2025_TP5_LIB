<?php
// Control/HorarioController.php
// Controlador simple para operaciones sobre horarios

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Modelo/Horario.php';

class HorarioController
{
    public static function respondJson($data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private static function badRequest(string $msg): void
    {
        self::respondJson(['error' => $msg], 400);
    }

    // Listar horarios (opcional filtro por fecha y cancha)
    public static function listar(): void
    {
        $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);
        $cancha = filter_input(INPUT_GET, 'cancha', FILTER_VALIDATE_INT);
        $cond = '';
        if ($fecha) $cond = "fecha='" . $fecha . "'";
        if ($cancha) {
            $cond = $cond ? $cond . " AND cancha_id='" . $cancha . "'" : "cancha_id='" . $cancha . "'";
        }

        $arr = Horario::listar($cond);
        $out = [];
        foreach ($arr as $h) {
            $out[] = [
                'id' => $h->getId(),
                'cancha_id' => $h->getCanchaId(),
                'fecha' => $h->getFecha(),
                'hora' => $h->getHora(),
                'disponible' => $h->isDisponible()
            ];
        }
        self::respondJson($out, 200);
    }

    // Obtener disponibilidad por cancha y fecha
    public static function disponibilidad(): void
    {
        $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);
        $cancha = filter_input(INPUT_GET, 'cancha', FILTER_VALIDATE_INT);
        if (!$fecha || !$cancha) self::badRequest('fecha y cancha son requeridos');

        $h = new Horario(0, '', '', true);
        $arr = $h->obtenerDisponibilidadPorCancha($fecha, $cancha);
        $out = [];
        foreach ($arr as $hr) {
            $out[] = [
                'id' => $hr->getId(),
                'hora' => $hr->getHora()
            ];
        }
        self::respondJson($out, 200);
    }
}

// Dispatcher simple: ?accion=listar|disponibilidad
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING) ?: '';
if ($accion) {
    switch ($accion) {
        case 'listar': HorarioController::listar(); break;
        case 'disponibilidad': HorarioController::disponibilidad(); break;
        default: HorarioController::respondJson(['error' => 'Acción no válida'], 400);
    }
}
