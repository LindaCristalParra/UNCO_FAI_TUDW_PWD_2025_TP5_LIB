<?php
// Control/CanchaController.php
// Controlador simple para operaciones sobre canchas

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Modelo/Cancha.php';

class CanchaController
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

    public static function listarActivas(): void
    {
        $c = new Cancha('', '', 0.0, true);
        $arr = $c->obtenerCanchasActivas();
        $out = [];
        foreach ($arr as $cancha) {
            $out[] = [
                'id' => $cancha->getId(),
                'nombre' => $cancha->getNombre(),
                'descripcion' => $cancha->getDescripcion(),
                'precio_hora' => $cancha->getPrecioHora(),
                'activa' => $cancha->getActiva()
            ];
        }
        self::respondJson($out, 200);
    }

    public static function obtenerPorNombre(): void
    {
        $nombre = filter_input(INPUT_GET, 'nombre', FILTER_SANITIZE_STRING);
        if (!$nombre) self::badRequest('nombre requerido');

        $c = new Cancha('', '', 0.0, true);
        $res = $c->obtener($nombre);
        if (!$res) self::respondJson(['error' => 'Cancha no encontrada'], 404);
        $out = [
            'id' => $res->getId(),
            'nombre' => $res->getNombre(),
            'descripcion' => $res->getDescripcion(),
            'precio_hora' => $res->getPrecioHora(),
            'activa' => $res->getActiva()
        ];
        self::respondJson($out, 200);
    }
}

// Dispatcher simple: ?accion=listarActivas|obtenerPorNombre
$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING) ?: '';
if ($accion) {
    switch ($accion) {
        case 'listarActivas': CanchaController::listarActivas(); break;
        case 'obtenerPorNombre': CanchaController::obtenerPorNombre(); break;
        default: CanchaController::respondJson(['error' => 'Acción no válida'], 400);
    }
}
