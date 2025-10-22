<?php
// Vista para cancelar reserva
include_once(__DIR__ . '/../Estructura/header.php');

// Controlador: buscar reservas si se envió el formulario
require_once(__DIR__ . '/../../Control/CancelarController.php');

$resultadoBusqueda = null;
$emailBuscado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])) {
    $emailBuscado = trim($_POST['email']);
    $resultadoBusqueda = CancelarController::buscarReservasPorEmail($emailBuscado);

}

// Mensajes de éxito/error de la URL
$mensajeExito = isset($_GET['exito']) ? $_GET['exito'] : null;
$mensajeError = isset($_GET['error']) ? $_GET['error'] : null;
?>
<div class="container mt-4">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="calendario.php">Reservar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="cancelar.php">Cancelar reserva</a>
        </li>
    </ul>
    <h2>Cancelar Reserva</h2>
    
    <?php if ($mensajeExito === 'cancelada'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Reserva cancelada!</strong> Tu reserva ha sido cancelada exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($mensajeError): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> 
            <?php 
            switch($mensajeError) {
                case 'id_no_valido':
                    echo 'ID de reserva no válido';
                    break;
                case 'no_se_pudo_cancelar':
                    echo 'No se pudo cancelar la reserva. Intente nuevamente';
                    break;
                default:
                    echo 'Ocurrió un error';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <form method="post" class="mb-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($emailBuscado) ?>" required>
            <div class="form-text">Ingresá el email que usaste para hacer la reserva</div>
        </div>
        <button type="submit" class="calendar-dia-btn">Ver mis reservas</button>
    </form>
    
    <?php if ($resultadoBusqueda !== null): ?>
        <?php if (!$resultadoBusqueda['exito']): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($resultadoBusqueda['mensaje']) ?>
            </div>
        <?php elseif (empty($resultadoBusqueda['reservas'])): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($resultadoBusqueda['mensaje']) ?>
            </div>
        <?php else: ?>
        <div class="card mt-4">
            <div class="card-header bg-success text-white text-center">
                <strong>Mis Reservas</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Horario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($resultadoBusqueda['reservas'] as $res): ?>
                            <tr>
                                <td><?= htmlspecialchars($res['fecha']) ?></td>
                                <td><?= htmlspecialchars($res['horario']) ?></td>
                                <td>
                                    <?php if ($res['puede_cancelar']): ?>
                                        <form method="post" action="../Estructura/Accion/accionCancelar.php" class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                                            <input type="hidden" name="cancelar_id" value="<?= $res['id'] ?>">
                                            <button type="submit" class="calendar-dia-btn">Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.9em;">
                                            No se puede cancelar<br>(menos de 24hs)
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>