<?php
// Controlador simple para operaciones sobre horarios

// Configurar zona horaria de Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/Horario.php';
require_once __DIR__ . '/../Modelo/Reserva.php';
require_once __DIR__ . '/../Modelo/Cancha.php';

use Carbon\Carbon;

class HorarioController{


    // Verifica si un horario está totalmente ocupado (todas las canchas reservadas)
    public static function horarioTotalmenteOcupado($fecha, $hora): bool
    {
        // Obtener canchas activas
        $c = new Cancha('', '', 0.0, true);
        $canchas = $c->obtenerCanchasActivas();
        $totalCanchas = count($canchas);

        // Contar reservas confirmadas para ese horario y fecha
        $reservas = Reserva::listar("fecha='$fecha' AND hora='$hora' AND estado='confirmada'");
        $totalReservas = count($reservas);

        return $totalReservas >= $totalCanchas;
    }

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
        $fecha = filter_input(INPUT_GET, 'fecha', FILTER_DEFAULT);
        $cancha = filter_input(INPUT_GET, 'cancha', FILTER_VALIDATE_INT);
        $cond = '';
        if ($fecha) {
            // Validar formato de fecha con Carbon
            try {
                $carbonFecha = Carbon::parse($fecha);
                $fecha = $carbonFecha->format('Y-m-d');
            } catch (Exception $e) {
                self::badRequest('Formato de fecha inválido');
            }
            $cond = "fecha='" . $fecha . "'";
        }
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
        $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_SPECIAL_CHARS);
        $cancha = filter_input(INPUT_GET, 'cancha', FILTER_VALIDATE_INT);
        if (!$fecha || !$cancha) self::badRequest('fecha y cancha son requeridos');

        // Validar formato de fecha con Carbon
        try {
            $carbonFecha = Carbon::parse($fecha);
            $fecha = $carbonFecha->format('Y-m-d');
        } catch (Exception $e) {
            self::badRequest('Formato de fecha inválido');
        }

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
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
if ($accion) {
    switch ($accion) {
        case 'listar': HorarioController::listar(); break;
        case 'disponibilidad': HorarioController::disponibilidad(); break;
        default: HorarioController::respondJson(['error' => 'Acción no válida'], 400);
    }
}
