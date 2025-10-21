<?php
// Control/ReservaController.php
// Controlador simple para manejar operaciones sobre reservas.

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Modelo/Reserva.php';

class ReservaController
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

    private static function validateDate(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private static function validateTime(string $time): bool
    {
        $t = DateTime::createFromFormat('H:i', $time);
        return $t && $t->format('H:i') === $time;
    }

    private static function getPostInt(string $key): ?int
    {
        $v = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
        return $v === false ? null : $v;
    }

    private static function getPostString(string $key, int $max = 255): string
    {
        $v = filter_input(INPUT_POST, $key, FILTER_DEFAULT);
        $v = is_string($v) ? trim($v) : '';
        return mb_substr($v, 0, $max);
    }

    /**
     * Crear reserva
     * POST: idCancha, fecha (YYYY-MM-DD), hora (HH:MM), nombre
     */
    public static function crear(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') self::badRequest('Usar POST');

        $idCancha = self::getPostInt('idCancha');
        $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING) ?: '';
        $hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING) ?: '';
        $nombre = self::getPostString('nombre', 100);

        if (!$idCancha) self::badRequest('idCancha no válido');
        if (!self::validateDate($fecha)) self::badRequest('fecha no válida (YYYY-MM-DD)');
        if (!self::validateTime($hora)) self::badRequest('hora no válida (HH:MM)');
        if ($nombre === '') self::badRequest('nombre no puede estar vacío');

        $fechaReservaNow = date('Y-m-d H:i:s');
        $reserva = new Reserva($idCancha, $fecha, $hora, $nombre, '', '', 'confirmada', $fechaReservaNow);
        if ($reserva->insertar()) {
            self::respondJson(['success' => true], 201);
        }
        self::respondJson(['error' => 'No se pudo crear la reserva', 'detalle' => $reserva->getMensajeOperacion()], 500);
    }

    /**
     * Obtener reserva por id (GET)
     * GET: id
     */
    public static function obtener(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) self::badRequest('id no válido');

        $modelo = new Reserva(0, '', '', '', '', '', '', '');
        $res = $modelo->obtener($id);
        if (!$res) self::respondJson(['error' => 'Reserva no encontrada'], 404);

        // Mapear objeto a array
        $data = [
            'id' => $res->getId(),
            'cancha_id' => $res->getCanchaId(),
            'fecha' => $res->getFecha(),
            'hora' => $res->getHora(),
            'cliente_nombre' => $res->getClienteNombre(),
            'cliente_email' => $res->getClienteEmail(),
            'cliente_telefono' => $res->getClienteTelefono(),
            'estado' => $res->getEstado(),
            'fecha_reserva' => $res->getFechaReserva()
        ];
        self::respondJson($data, 200);
    }

    /**
     * Actualizar fecha/hora de una reserva
     * POST: id, fecha, hora
     */
    public static function actualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') self::badRequest('Usar POST');

        $id = self::getPostInt('id');
        $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING) ?: '';
        $hora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_STRING) ?: '';

        if (!$id) self::badRequest('id no válido');
        if (!self::validateDate($fecha)) self::badRequest('fecha no válida');
        if (!self::validateTime($hora)) self::badRequest('hora no válida');

        $modelo = new Reserva(0, '', '', '', '', '', '', '');
        $existente = $modelo->obtener($id);
        if (!$existente) self::respondJson(['error' => 'Reserva no encontrada'], 404);

        $existente->setFecha($fecha);
        $existente->setHora($hora);
        if ($existente->modificar($id)) {
            self::respondJson(['success' => true], 200);
        }
        self::respondJson(['error' => 'No se pudo actualizar', 'detalle' => $existente->getMensajeOperacion()], 500);
    }

    /**
     * Cancelar reserva
     * POST: id
     */
    public static function cancelar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') self::badRequest('Usar POST');

        $id = self::getPostInt('id');
        if (!$id) self::badRequest('id no válido');

        $modelo = new Reserva(0, '', '', '', '', '', '', '');
        if ($modelo->cancelarReserva($id)) {
            self::respondJson(['success' => true], 200);
        }
        self::respondJson(['error' => 'No se pudo cancelar', 'detalle' => $modelo->getMensajeOperacion()], 500);
    }

    /**
     * Listar reservas (opcional filtro por fecha)
     * GET: fecha (opcional)
     */
    public static function listar(): void
    {
        $fecha = filter_input(INPUT_GET, 'fecha', FILTER_SANITIZE_STRING);
        $cond = '';
        if ($fecha) {
            $cond = "fecha='" . $fecha . "'";
        }
        $arr = Reserva::listar($cond);
        $out = [];
        foreach ($arr as $r) {
            $out[] = [
                'id' => $r->getId(),
                'cancha_id' => $r->getCanchaId(),
                'fecha' => $r->getFecha(),
                'hora' => $r->getHora(),
                'cliente_nombre' => $r->getClienteNombre(),
                'estado' => $r->getEstado()
            ];
        }
        self::respondJson($out, 200);
    }
}

// Dispatcher simple para llamadas directas a este archivo: ?accion=crear|obtener|actualizar|cancelar|listar
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING) ?: '';
if ($accion) {
    switch ($accion) {
        case 'crear': ReservaController::crear(); break;
        case 'obtener': ReservaController::obtener(); break;
        case 'actualizar': ReservaController::actualizar(); break;
        case 'cancelar': ReservaController::cancelar(); break;
        case 'listar': ReservaController::listar(); break;
        default: ReservaController::respondJson(['error' => 'Acción no válida'], 400);
    }
}
