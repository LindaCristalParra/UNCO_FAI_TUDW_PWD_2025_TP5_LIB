<?php
include_once(__DIR__ . '/../Estructura/header.php');
// Recibe por GET o POST: anio, mes, dia, hora, fin
$anio = $_GET['anio'] ?? $_POST['anio'] ?? '';
$mes = $_GET['mes'] ?? $_POST['mes'] ?? '';
$dia = $_GET['dia'] ?? $_POST['dia'] ?? '';
$hora = $_GET['hora'] ?? $_POST['hora'] ?? '';
$fin = $_GET['fin'] ?? $_POST['fin'] ?? '';
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">Confirmar Reserva</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="../Controlador/accionReserva.php">
                        <input type="hidden" name="anio" value="<?= htmlspecialchars($anio) ?>">
                        <input type="hidden" name="mes" value="<?= htmlspecialchars($mes) ?>">
                        <input type="hidden" name="dia" value="<?= htmlspecialchars($dia) ?>">
                        <input type="hidden" name="hora" value="<?= htmlspecialchars($hora) ?>">
                        <input type="hidden" name="fin" value="<?= htmlspecialchars($fin) ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <div class="alert alert-info mb-0">
                                <strong>Reserva:</strong> <?= htmlspecialchars($dia) ?>/<?= htmlspecialchars($mes) ?>/<?= htmlspecialchars($anio) ?> de <?= htmlspecialchars($hora) ?> a <?= htmlspecialchars($fin) ?>
                            </div>
                        </div>
                        <button type="submit" class="calendar-dia-btn w-100 mb-2">Confirmar Reserva</button>
                        <a href="calendario.php" class="btn btn-outline-secondary w-100">Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>
