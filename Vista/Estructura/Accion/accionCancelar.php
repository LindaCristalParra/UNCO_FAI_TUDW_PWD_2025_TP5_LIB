<?php
// Vista/Estructura/Accion/accionCancelar.php
// Procesa la cancelación de reserva desde cancelar.php

require_once __DIR__ . '/../../../Control/ReservaController.php';
require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';

$ve = new ValorEncapsulado();

// Obtener ID de la reserva a cancelar
$cancelar_id = $ve->obtenerValor('cancelar_id', null);

if (!$cancelar_id) {
    header('Location: ../../Reserva/cancelar.php?error=id_no_valido');
    exit;
}

// Crear instancia del modelo y cancelar
$modelo = new Reserva(0, '', '', '', '', '', '', '');
if ($modelo->cancelarReserva($cancelar_id)) {
    // Enviar email de cancelación (opcional, implementar después)
    // Redirigir con mensaje de éxito
    header('Location: ../../Reserva/cancelar.php?exito=cancelada');
    exit;
} else {
    // Error al cancelar
    header('Location: ../../Reserva/cancelar.php?error=no_se_pudo_cancelar');
    exit;
}
