<?php
include_once(__DIR__ . '/../Estructura/header.php');

// Verificar si viene de una reserva exitosa
$exito = $_GET['exito'] ?? null;
$fecha = $_GET['fecha'] ?? '';
$hora = $_GET['hora'] ?? '';
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <?php if ($exito): ?>
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">✓ ¡Reserva Confirmada!</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="mb-3">Tu reserva fue procesada exitosamente.</p>
                        <?php if ($fecha && $hora): ?>
                            <div class="alert alert-info">
                                <strong>Fecha:</strong> <?= htmlspecialchars($fecha) ?><br>
                                <strong>Horario:</strong> <?= htmlspecialchars($hora) ?>
                            </div>
                        <?php endif; ?>
                        <p class="text-muted">Recibirás un email de confirmación en breve.</p>
                        <a href="calendario.php" class="calendar-dia-btn d-inline-block px-4">Volver al Calendario</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <p class="mb-0">No hay información de confirmación disponible.</p>
                    <a href="calendario.php" class="btn btn-link">Ir al calendario</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>
