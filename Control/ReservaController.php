<?php
// Control/ReservaController.php
// Controlador simple para manejar operaciones sobre reservas.

// Configurar zona horaria de Argentina
date_default_timezone_set('America/Argentina/Buenos_Aires');

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/Reserva.php';
require_once __DIR__ . '/../Modelo/Cancha.php';
require_once __DIR__ . '/../Modelo/Servicio/EmailService.php';

use Carbon\Carbon;

class ReservaController{
    
    /**
     * Crear reserva desde vista/acción
     * Recibe datos individuales, valida y crea la reserva usando Carbon
     * Devuelve array con success, error, fecha, hora
     */
    public static function crearReserva($idCancha, $anio, $mes, $dia, $hora,  $nombre, $email, $telefono): array
    {   
        $respuesta = [];
        if (!$idCancha || !$anio || !$mes || !$dia || !$hora || !$nombre || !$email) {
            $respuesta = ['success' => false, 'error' => 'Datos incompletos'];
        }
        $fechaCarbon = Carbon::createFromDate($anio, $mes, $dia);
        $fecha = $fechaCarbon->format('Y-m-d');
            
        $fechaReservaNow = Carbon::now()->format('Y-m-d H:i:s');
        $reserva = new Reserva($idCancha, $fecha, $hora, $nombre, $email, $telefono, 'confirmada', $fechaReservaNow);

        if ($reserva->insertar()) {
            // Obtener el nombre de la cancha
            $canchas = Cancha::listar("id=" . $idCancha);
            $nombreCancha = !empty($canchas) ? $canchas[0]->getNombre() : 'Cancha ' . $idCancha;
            
            // Enviar email de confirmación 
            $datosEmail = [
                'nombre' => $nombre,
                'fecha' => $fecha,
                'hora' => $hora,
                'cancha' => $nombreCancha
            ];
            EmailService::enviarConfirmacion($email, $datosEmail);
            // Nota: si falla el email, no afecta el resultado de la reserva
            
            return [
                'success' => true,
                'fecha' => $fecha,
                'hora' => $hora,
                'cancha' => $nombreCancha
            ];
        } else {
            return [
                'success' => false,
                'error' => 'No se pudo crear la reserva',
                'detalle' => $reserva->getMensajeOperacion()
            ];
        }
    }
}

