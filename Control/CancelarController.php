<?php
// Controlador para la gestión de cancelación de reservas
// Maneja búsqueda de reservas por email y validación de cancelaciones

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/Reserva.php';
require_once __DIR__ . '/../Modelo/Cancha.php';
require_once __DIR__ . '/../Modelo/Servicio/EmailService.php';

use Carbon\Carbon;

class CancelarController
{
    /**
     * Busca todas las reservas activas de un email
     * @param string $email Email del cliente
     * @return array Reservas formateadas para la vista
     */
    public static function buscarReservasPorEmail(string $email): array
    {
        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $respuesta = [
                'exito' => false,
                'mensaje' => 'Email inválido',
                'reservas' => []
            ];
        }
        
        // Buscar reservas confirmadas del email
        $reservas = Reserva::listar("cliente_email='" . $email . "' AND estado='confirmada'");
        
        if (empty($reservas)) {
            $respuesta = [
                'exito' => true,
                'mensaje' => 'No se encontraron reservas activas para este email',
                'reservas' => []
            ];
        }
        
        // Formatear reservas para la vista
        $reservasFormateadas = [];
        foreach ($reservas as $reserva) {
            // Obtener información de la cancha usando listar
            $canchas = Cancha::listar("id=" . $reserva->getCanchaId());
            $nombreCancha = !empty($canchas) ? $canchas[0]->getNombre() : 'Cancha #' . $reserva->getCanchaId();
            
            // Formatear fecha con Carbon
            $fechaCarbon = Carbon::parse($reserva->getFecha());
            $fechaFormateada = $fechaCarbon->format('d/m/Y');
            
            // Calcular hora de fin (duración de 1.5 horas)
            $horaInicio = Carbon::parse($reserva->getFecha() . ' ' . $reserva->getHora());
            $horaFin = $horaInicio->copy()->addMinutes(90);
            
            $reservasFormateadas[] = [
                'id' => $reserva->getId(),
                'fecha' => $fechaFormateada,
                'fecha_raw' => $reserva->getFecha(),
                'horario' => $horaInicio->format('H:i') . ' - ' . $horaFin->format('H:i'),
                'cancha' => $nombreCancha,
                'cancha_id' => $reserva->getCanchaId(),
                'puede_cancelar' => self::puedeCancelar($reserva->getFecha(), $reserva->getHora())
            ];
        }
        
        // Ordenar por fecha (más recientes primero)
        usort($reservasFormateadas, function($a, $b) {
            return strcmp($b['fecha_raw'], $a['fecha_raw']);
        });
        
        $respuesta = [
            'exito' => true,
            'mensaje' => 'Se encontraron ' . count($reservasFormateadas) . ' reserva(s)',
            'reservas' => $reservasFormateadas
        ];
        return $respuesta;
    }
    
    /**
     * Verifica si una reserva puede ser cancelada
     * (debe tener al menos 24 horas de anticipación)
     * @param string $fecha Fecha de la reserva (Y-m-d)
     * @param string $hora Hora de la reserva (H:i)
     * @return bool true si puede cancelar
     */
    private static function puedeCancelar(string $fecha, string $hora): bool
    {
        try {
            $fechaReserva = Carbon::parse($fecha . ' ' . $hora, 'America/Argentina/Buenos_Aires');
            $ahora = Carbon::now('America/Argentina/Buenos_Aires');
            
            // Debe haber al menos 24 horas de anticipación
            $horasRestantes = $ahora->diffInHours($fechaReserva, false);
            
            return $horasRestantes >= 24;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Cancela una reserva
     * @param int $reservaId ID de la reserva a cancelar
     * @return array Resultado de la operación
     */
    public static function cancelarReserva(int $reservaId): array
    {
        // Buscar la reserva
        $reservas = Reserva::listar("id=" . $reservaId);
        
        if (empty($reservas)) {
            return [
                'exito' => false,
                'mensaje' => 'Reserva no encontrada'
            ];
        }
        
        $reserva = $reservas[0];
        
        // Verificar que esté confirmada
        if ($reserva->getEstado() !== 'confirmada') {
            return [
                'exito' => false,
                'mensaje' => 'Esta reserva ya fue cancelada'
            ];
        }
        
        // Verificar que se pueda cancelar (24 horas de anticipación)
        if (!self::puedeCancelar($reserva->getFecha(), $reserva->getHora())) {
            return [
                'exito' => false,
                'mensaje' => 'No se puede cancelar. Debe hacerlo con al menos 24 horas de anticipación'
            ];
        }
        
        // Realizar la cancelación
        $modelo = new Reserva(
            $reserva->getCanchaId(),
            $reserva->getFecha(),
            $reserva->getHora(),
            $reserva->getClienteNombre(),
            $reserva->getClienteEmail(),
            $reserva->getClienteTelefono(),
            'cancelada', // Cambiar estado
            $reserva->getFechaReserva()
        );
        $modelo->setId($reserva->getId());
        
        if ($modelo->modificar($reserva->getId())) {
            // Obtener el nombre de la cancha
            $canchas = Cancha::listar("id=" . $reserva->getCanchaId());
            $nombreCancha = !empty($canchas) ? $canchas[0]->getNombre() : 'Cancha ' . $reserva->getCanchaId();
            
            // Enviar email de cancelación (responsabilidad del Controller)
            $datosEmail = [
                'nombre' => $reserva->getClienteNombre(),
                'fecha' => $reserva->getFecha(),
                'hora' => $reserva->getHora(),
                'cancha' => $nombreCancha
            ];
            EmailService::enviarCancelacion($reserva->getClienteEmail(), $datosEmail);
            // Nota: si falla el email, no afecta el resultado de la cancelación
            
            return [
                'exito' => true,
                'mensaje' => 'Reserva cancelada exitosamente',
                'reserva' => [
                    'fecha' => $reserva->getFecha(),
                    'hora' => $reserva->getHora(),
                    'email' => $reserva->getClienteEmail(),
                    'nombre' => $reserva->getClienteNombre(),
                    'cancha' => $nombreCancha
                ]
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'Error al cancelar la reserva. Intente nuevamente'
            ];
        }
    }
}
