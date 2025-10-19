<?php
// Vista para cancelar reserva
include_once(__DIR__ . '/../Estructura/header.php');
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
    <form method="post" class="mb-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    <button type="submit" class="calendar-dia-btn">Ver mis reservas</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email'])): ?>
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
                                <th>Cancha</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        // EJEMPLO PARA HACER EL ESTILO BORRAR DESPUES
                        // Ejemplo de reservas, reemplazar por consulta a BD
                        $reservas = [
                            ["id"=>1, "fecha"=>"20/10/2025", "horario"=>"18:00 - 19:30", "cancha"=>"1"],
                            ["id"=>2, "fecha"=>"22/10/2025", "horario"=>"20:00 - 21:30", "cancha"=>"2"],
                        ];
                        foreach($reservas as $res): ?>
                            <tr>
                                <td><?= htmlspecialchars($res['fecha']) ?></td>
                                <td><?= htmlspecialchars($res['horario']) ?></td>
                                <td><?= htmlspecialchars($res['cancha']) ?></td>
                                <td>
                                    <form method="post" action="../Controlador/accionReservas.php" class="d-inline">
                                        <input type="hidden" name="cancelar_id" value="<?= $res['id'] ?>">
                                        <button type="submit" class="calendar-dia-btn">Cancelar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>