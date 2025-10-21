<?php
// Vista/Estructura/Accion/accionReserva.php
// Procesa el formulario de reserva desde Reservar.php

require_once __DIR__ . '/../../../Control/ReservaController.php';
require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';
require_once __DIR__ . '/../../../Modelo/EmailService.php';


$ve = new ValorEncapsulado();
$anio = $ve->obtenerValor('anio', null);
$mes = $ve->obtenerValor('mes', null);
$dia = $ve->obtenerValor('dia', null);
$hora = $ve->obtenerValor('hora', null);
$fin = $ve->obtenerValor('fin', null);
$nombre = $ve->obtenerValor('nombre', null);
$email = $ve->obtenerValor('email', null);
$telefono = $ve->obtenerValor('telefono', null);
$idCancha = $ve->obtenerValor('idCancha', 1); // Mejor si viene del formulario

if (!$anio || !$mes || !$dia || !$hora || !$nombre || !$email) {
    header('Location: ../../Reserva/Reservar.php?error=datos_incompletos');
    exit;
}

// Llama al controller, que internamente usa Carbon para validar y crear la reserva
$resultado = ReservaController::crearReserva($idCancha, $anio, $mes, $dia, $hora, $fin, $nombre, $email, $telefono);

if ($resultado['success']) {
    // Enviar email de confirmación
    $datosEmail = [
        'nombre' => $nombre,
        'fecha' => $resultado['fecha'],
        'hora' => $resultado['hora'],
        'cancha' => $resultado['cancha']
    ];
    
    $resultadoEmail = EmailService::enviarConfirmacion($email, $datosEmail);
    // Nota: aunque falle el email, la reserva ya está creada, así que redirigimos igual
    
    header('Location: ../../Reserva/confirmacion.php?exito=1&fecha=' . urlencode($resultado['fecha']) . '&hora=' . urlencode($resultado['hora']));
    exit;
} else {
    header('Location: ../../Reserva/Reservar.php?error=' . urlencode($resultado['error']));
    exit;
}

