<?php
// Vista/Estructura/Accion/accionCancelar.php
// Procesa la cancelación de reserva desde cancelar.php

require_once __DIR__ . '/../../../Control/CancelarController.php';
require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';
require_once __DIR__ . '/../../../Modelo/EmailService.php';

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
    // Enviar email de cancelación
    $datosEmail = [
        'nombre' => $resultado['reserva']['nombre'],
        'fecha' => $resultado['reserva']['fecha'],
        'hora' => $resultado['reserva']['hora'],
        'cancha' => $resultado['reserva']['cancha']
    ];
    
    $resultadoEmail = EmailService::enviarCancelacion($resultado['reserva']['email'], $datosEmail);
    // Nota: aunque falle el email, la cancelación ya está registrada, así que redirigimos igual
    
    // Redirigir con mensaje de éxito
    header('Location: ../../Reserva/cancelar.php?exito=cancelada');
    exit;
} else {
    // Error al cancelar - pasar el mensaje específico
    $mensajeError = urlencode($resultado['mensaje']);
    header('Location: ../../Reserva/cancelar.php?error=' . $mensajeError);
    exit;
}

