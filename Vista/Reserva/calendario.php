<?php
include_once(__DIR__ . '/../Estructura/header.php');

// CONTROLADOR: Preparar todos los datos necesarios
require_once(__DIR__ . '/../../Control/CalendarioController.php');
$datosCalendario = CalendarioController::obtenerDatosCalendario($_GET);

// Extraer datos para la vista
extract($datosCalendario);
?>
<div class="container mt-4">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="calendario.php">Reservar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cancelar.php">Cancelar reserva</a>
        </li>
    </ul>
    <h2>Calendario de Reservas</h2>
    <!-- Calendario mensual en tabla Bootstrap -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header calendar-header-blue text-center p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="get" class="d-inline">
                            <input type="hidden" name="anio" value="<?= max($anioMin, $anio-1) ?>">
                            <input type="hidden" name="mes" value="<?= $mes ?>">
                            <button type="submit" class="border-0 p-0" style="background: none; color: #343a40;">
                                <span class="carousel-control-prev-icon calendar-arrow" aria-hidden="true"<?= $anio <= $anioMin ? ' style="opacity:0.5;pointer-events:none;"' : '' ?>></span>
                            </button>
                        </form>
                        <span class="calendar-year"><?= $anio ?></span>
                        <form method="get" class="d-inline">
                            <input type="hidden" name="anio" value="<?= min($anioMax, $anio+1) ?>">
                            <input type="hidden" name="mes" value="<?= $mes ?>">
                            <button type="submit" class="border-0 p-0" style="background: none; color: #343a40;">
                                <span class="carousel-control-next-icon calendar-arrow" aria-hidden="true"<?= $anio >= $anioMax ? ' style="opacity:0.5;pointer-events:none;"' : '' ?>></span>
                            </button>
                        </form>
                    </div>
                </div>
                <div style="border-bottom:1px solid #dee2e6;"></div>
                <div class="bg-white text-center p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="get" class="d-inline">
                            <input type="hidden" name="anio" value="<?= $anio ?>">
                            <input type="hidden" name="mes" value="<?= max($mesActual, $mes-1) ?>">
                            <button type="submit" class="border-0 p-0" style="background: none; color: #343a40;">
                                <span class="carousel-control-prev-icon calendar-arrow" aria-hidden="true"<?= $mes <= $mesActual ? ' style="opacity:0.5;pointer-events:none;"' : '' ?>></span>
                            </button>
                        </form>
                        <span class="calendar-nav"><?= $mesesNombres[$mes] ?></span>
                        <form method="get" class="d-inline">
                            <input type="hidden" name="anio" value="<?= $anio ?>">
                            <input type="hidden" name="mes" value="<?= min(12, $mes+1) ?>">
                            <button type="submit" class="border-0 p-0" style="background: none; color: #343a40;">
                                <span class="carousel-control-next-icon calendar-arrow" aria-hidden="true"<?= $mes >= 12 ? ' style="opacity:50% ;pointer-events:none;"' : '' ?>></span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-2">
                    <table class="table table-bordered text-center align-middle mb-0" style="width:100%;max-width:350px;margin:auto;">
                        <thead>
                            <tr>
                                <th class="calendar-nav">Lun</th>
                                <th class="calendar-nav">Mar</th>
                                <th class="calendar-nav">Mié</th>
                                <th class="calendar-nav">Jue</th>
                                <th class="calendar-nav">Vie</th>
                                <th class="calendar-nav">Sáb</th>
                                <th class="calendar-nav">Dom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Renderizar semanas preparadas por el controlador
                            foreach ($semanas as $semana) {
                                echo '<tr>';
                                foreach ($semana as $dia) {
                                    if ($dia === null) {
                                        echo '<td>&nbsp;</td>';
                                    } else {
                                        $claseActivo = $dia['seleccionado'] ? ' calendar-dia-btn-activo' : '';
                                        $deshabilitado = $dia['deshabilitado'] ?? false;
                                        
                                        echo '<td>';
                                        
                                        if ($deshabilitado) {
                                            // Día pasado: mostrar como texto sin interacción
                                            echo '<span class="calendar-dia-deshabilitado" style="display:block;padding:8px;color:#999;cursor:not-allowed;">' . $dia['dia'] . '</span>';
                                        } else {
                                            // Día disponible: mostrar botón clickeable
                                            echo '<form method="get">';
                                            echo '<input type="hidden" name="anio" value="' . $anio . '">';
                                            echo '<input type="hidden" name="mes" value="' . $mes . '">';
                                            echo '<input type="hidden" name="dia" value="' . $dia['dia'] . '">';
                                            echo '<button type="submit" class="calendar-dia-btn w-100 h-100' . $claseActivo . '">' . $dia['dia'] . '</button>';
                                            echo '</form>';
                                        }
                                        
                                        echo '</td>';
                                    }
                                }
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php
            // Si se seleccionó un día, mostrar horarios preparados por el controlador
            if ($horariosDisponibilidad !== null) {
                echo "<h4 class='mb-3 calendar-nav'>Horarios para el $diaSeleccionado/$mes/$anio</h4>";
                echo '<div class="row">';
                
                foreach ($horariosDisponibilidad as $horario) {
                    echo '<div class="col-12 mb-2">';
                    
                    if (!$horario['disponible']) {
                        // Horario completo (todas las canchas ocupadas)
                        echo '<button type="button" class="btn btn-secondary w-100" disabled>';
                        echo $horario['inicio'] . ' - ' . $horario['fin'];
                        echo '</button>';
                    } else {
                        // Horario disponible
                        echo '<a href="' . $horario['link'] . '" class="calendar-reserva-btn w-100 d-block text-center" style="text-decoration:none;">';
                        echo $horario['inicio'] . ' - ' . $horario['fin'];
                        echo '</a>';
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>