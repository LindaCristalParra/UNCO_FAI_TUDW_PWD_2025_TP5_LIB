<?php
// Vista/Estructura/Accion/accionCancelar.php
// Procesa la cancelación de reserva desde cancelar.php

require_once __DIR__ . '/../../../Control/CancelarController.php';
require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';

$ve = new ValorEncapsulado();

// Obtener ID de la reserva a cancelar
$cancelar_id = $ve->obtenerValor('cancelar_id', null);

if (!$cancelar_id) {
    header('Location: ../../Reserva/cancelar.php?error=id_no_valido');
    exit;
}

// Llamar al controlador para cancelar
$resultado = CancelarController::cancelarReserva(intval($cancelar_id));

if ($resultado['exito']) {
    // TODO: Enviar email de cancelación usando Symfony Mailer
    // EmailService::enviarCancelacion($resultado['reserva']['email'], $resultado['reserva']);
    
    // Redirigir con mensaje de éxito
    header('Location: ../../Reserva/cancelar.php?exito=cancelada');
    exit;
} else {
    // Error al cancelar - pasar el mensaje específico
    $mensajeError = urlencode($resultado['mensaje']);
    header('Location: ../../Reserva/cancelar.php?error=' . $mensajeError);
    exit;
}
