<?php
include_once(__DIR__ . '/../Estructura/header.php');
// Vista de calendario de reservas
// Calendario mensual con Bootstrap
$mesActual = date('n');
$anioActual = date('Y');
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
    <?php
    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : $mesActual;
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : $anioActual;
    $anioMin = $anioActual;
    $anioMax = $anioActual+2;
    $mesesNombres = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
    ?>
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
                            $primerDia = mktime(0,0,0,$mes,1,$anio);
                            $diasMes = date('t', $primerDia);
                            $diaSemana = (date('N', $primerDia) % 7);
                            $fila = [];
                            for($i=1; $i<$diaSemana; $i++) {
                                $fila[] = '';
                            }
                            for($dia=1; $dia<=$diasMes; $dia++) {
                                $selected = (isset($_GET['dia']) && intval($_GET['dia']) == $dia);
                                $fila[] = '<form method="get"><input type="hidden" name="anio" value="'.$anio.'"><input type="hidden" name="mes" value="'.$mes.'"><input type="hidden" name="dia" value="'.$dia.'"><button type="submit" class="calendar-dia-btn w-100 h-100'.($selected ? ' calendar-dia-btn-activo' : '').'">'.$dia.'</button></form>';
                                if(count($fila) == 7) {
                                    echo '<tr>';
                                    foreach($fila as $celda) {
                                        echo '<td>'.($celda ?: '&nbsp;').'</td>';
                                    }
                                    echo '</tr>';
                                    $fila = [];
                                }
                            }
                            if(count($fila)) {
                                while(count($fila)<7) $fila[] = '';
                                echo '<tr>';
                                foreach($fila as $celda) {
                                    echo '<td>'.($celda ?: '&nbsp;').'</td>';
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
            // Si se seleccionó un día, mostrar horarios al costado derecho
            if(isset($_GET['dia'])) {
                $diaSeleccionado = intval($_GET['dia']);
                echo "<h4 class='mb-3 calendar-nav'>Horarios para el $diaSeleccionado/$mes/$anio</h4>";
                $horaInicio = 16;
                $horaFin = 23;
                $duracion = 1.5; // horas
                $horarios = [];
                for($h=$horaInicio; $h<$horaFin; $h+=$duracion) {
                    $horaStr = sprintf('%02d:%02d', floor($h), ($h-floor($h))*60);
                    $finStr = sprintf('%02d:%02d', floor($h+$duracion), ($h+$duracion-floor($h+$duracion))*60);
                    $horarios[] = ["inicio"=>$horaStr, "fin"=>$finStr];
                }
                echo '<div class="row">';
                foreach($horarios as $horario) {
                    $reservado = false; // Cambiar por consulta a BD
                    echo '<div class="col-12 mb-2">';
                    if($reservado) {
                        echo '<button type="button" class="btn btn-secondary w-100" disabled>'.$horario["inicio"].' - '.$horario["fin"].'</button>';
                    } else {
                        $link = "Reservar.php?anio=$anio&mes=$mes&dia=$diaSeleccionado&hora=".urlencode($horario["inicio"])."&fin=".urlencode($horario["fin"]);
                        echo '<a href="'.$link.'" class="calendar-reserva-btn w-100 d-block text-center" style="text-decoration:none;">'.$horario["inicio"].' - '.$horario["fin"].'</a>';
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