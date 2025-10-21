<?php

// Punto de entrada para manejar acciones provenientes de formularios.

require_once __DIR__ . '/../../../Control/metodoEncapsulado.php';

$ve = new ValorEncapsulado();
$accion = $ve->obtenerValor('accion', null);

switch ($accion) {
  case 'crearReserva':
    require_once __DIR__ . '/accionReserva.php';
    break;
  case 'cancelarReserva':
    require_once __DIR__ . '/accionCancelar.php';
    break;
  case 'listarCanchas':
    require_once __DIR__ . '/../../../Control/CanchaController.php';
    CanchaController::listarActivas();
    break;
  case 'listarReservas':
    require_once __DIR__ . '/../../../Control/ReservaController.php';
    ReservaController::listar();
    break;
  default:
    http_response_code(400);
    echo 'Acción no reconocida o no provista.';
}
