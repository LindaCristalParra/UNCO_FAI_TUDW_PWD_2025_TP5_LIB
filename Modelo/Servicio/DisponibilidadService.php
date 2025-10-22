<?php

require_once __DIR__ . '/../Cancha.php';
require_once __DIR__ . '/../Reserva.php';

class Disponibilidad
{
    // Retorna true si TODAS las canchas están ocupadas para esa fecha y hora
    public static function horarioTotalmenteOcupado(string $fecha, string $hora): bool
    {
        $c = new Cancha('', '', 0.0, true);
        $canchas = $c->obtenerCanchasActivas();
        $totalCanchas = count($canchas);

        $reservas = Reserva::listar("fecha='" . $fecha . "' AND hora='" . $hora . "' AND estado='confirmada'");
        $totalReservas = count($reservas);

        return $totalReservas >= $totalCanchas;
    }
}
