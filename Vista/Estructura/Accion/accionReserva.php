<?php
// Vista/Estructura/Accion/accionReserva.php
// Procesa el formulario de reserva desde Reservar.php

require_once __DIR__ . '/../../../Control/ReservaController.php';
require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';

$ve = new ValorEncapsulado();

// Obtener datos del formulario
$anio = $ve->obtenerValor('anio', null);
$mes = $ve->obtenerValor('mes', null);
$dia = $ve->obtenerValor('dia', null);
$hora = $ve->obtenerValor('hora', null);
$fin = $ve->obtenerValor('fin', null);
$nombre = $ve->obtenerValor('nombre', null);
$email = $ve->obtenerValor('email', null);
$telefono = $ve->obtenerValor('telefono', null);

// Validar datos básicos
if (!$anio || !$mes || !$dia || !$hora || !$nombre || !$email) {
    header('Location: ../../Reserva/Reservar.php?error=datos_incompletos');
    exit;
}

// Formatear fecha
$fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);

// Crear la reserva (asumimos idCancha = 1 por defecto, ajustar según necesidad)
$idCancha = 1;
$fechaReservaNow = date('Y-m-d H:i:s');

$reserva = new Reserva($idCancha, $fecha, $hora, $nombre, $email, $telefono, 'confirmada', $fechaReservaNow);

if ($reserva->insertar()) {
    // Enviar email de confirmación (opcional, implementar después)
    // Redirigir a página de éxito
    header('Location: ../../Reserva/confirmacion.php?exito=1&fecha=' . urlencode($fecha) . '&hora=' . urlencode($hora));
    exit;
} else {
    // Error al crear reserva
    header('Location: ../../Reserva/Reservar.php?error=no_se_pudo_crear');
    exit;
}
