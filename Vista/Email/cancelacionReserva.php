<?php
// Plantilla de email de cancelación de reserva
// Variables esperadas: $nombre, $fecha, $hora, $cancha
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cancelación de Reserva</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #222; }
        .mail-container { background: #fff; border-radius: 8px; padding: 24px; max-width: 480px; margin: 24px auto; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .mail-title { color: #dc3545; font-size: 1.5rem; margin-bottom: 12px; }
        .mail-detail { margin-bottom: 8px; }
        .mail-footer { color: #888; font-size: 0.95rem; margin-top: 18px; }
    </style>
</head>
<body>
    <div class="mail-container">
        <div class="mail-title">Reserva cancelada</div>
        <div class="mail-detail">Hola <strong><?= htmlspecialchars($nombre) ?></strong>,</div>
        <div class="mail-detail">Tu reserva fue cancelada correctamente.</div>
    <div class="mail-detail"><strong>Fecha:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($fecha))) ?></div>
        <div class="mail-detail"><strong>Horario:</strong> <?= htmlspecialchars($hora) ?></div>
        <div class="mail-footer">Lamentamos que no puedas asistir.<br>Puro Fútbol</div>
    </div>
</body>
</html>