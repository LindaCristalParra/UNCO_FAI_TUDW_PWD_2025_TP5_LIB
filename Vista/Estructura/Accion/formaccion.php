<?php

// Punto de entrada para manejar acciones provenientes de formularios.

require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';

$ve = new ValorEncapsulado();
$accion = $ve->obtenerValor('accion', null);

switch ($accion) {
  case 'crearReserva':
    // Invocar al controlador correspondiente.
    echo 'Acción crearReserva pendiente de implementación.';
    break;
  case 'cancelarReserva':
    echo 'Acción cancelarReserva pendiente de implementación.';
    break;
  default:
    http_response_code(400);
    echo 'Acción no reconocida o no provista.';
}
