<?php
// Controlador para la vista del calendario de reservas
// Prepara todos los datos necesarios sin que la vista acceda directamente a modelos

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Modelo/Servicio/DisponibilidadService.php';

use Carbon\Carbon;

class CalendarioController
{
    /**
     * Obtiene todos los datos necesarios para renderizar el calendario
     * @param array $params Parámetros GET (mes, anio, dia)
     * @return array Datos estructurados para la vista
     */
    public static function obtenerDatosCalendario(array $params): array
    {
        // Usar Carbon para obtener fecha actual
        $hoy = Carbon::now('America/Argentina/Buenos_Aires');
        $mesActual = $hoy->month;
        $anioActual = $hoy->year;
        
        // Parámetros del calendario
        $mes = isset($params['mes']) ? intval($params['mes']) : $mesActual;
        $anio = isset($params['anio']) ? intval($params['anio']) : $anioActual;
        $diaSeleccionado = isset($params['dia']) ? intval($params['dia']) : null;
        
        // Límites de navegación
        $anioMin = $anioActual;
        $anioMax = $anioActual + 2;
        
        // Usar Carbon para obtener nombres de meses localizados
        Carbon::setLocale('es');
        $mesesNombres = [];
        for ($m = 1; $m <= 12; $m++) {
            $mesesNombres[$m] = Carbon::create($anio, $m, 1)->translatedFormat('F');
            // Capitalizar primera letra
            $mesesNombres[$m] = ucfirst($mesesNombres[$m]);
        }
        
        // Construir estructura del calendario usando Carbon
        $primerDiaDelMes = Carbon::create($anio, $mes, 1);
        $diasMes = $primerDiaDelMes->daysInMonth;
        $diaSemana = $primerDiaDelMes->dayOfWeek; // 0=Dom, 1=Lun, etc.
        
        $semanas = self::construirSemanas($mes, $anio, $diasMes, $diaSemana, $diaSeleccionado, $hoy);
        
        // Si hay día seleccionado, preparar horarios con disponibilidad
        $horariosDisponibilidad = null;
        if ($diaSeleccionado !== null) {
            $horariosDisponibilidad = self::obtenerHorariosConDisponibilidad($anio, $mes, $diaSeleccionado);
        }
        
        return [
            'mes' => $mes,
            'anio' => $anio,
            'mesActual' => $mesActual,
            'anioActual' => $anioActual,
            'diaSeleccionado' => $diaSeleccionado,
            'anioMin' => $anioMin,
            'anioMax' => $anioMax,
            'mesesNombres' => $mesesNombres,
            'semanas' => $semanas,
            'horariosDisponibilidad' => $horariosDisponibilidad
        ];
    }
    
    /**
     * Construye la estructura de semanas del calendario
     * @return array Array de semanas, cada semana es array de días (int o null)
     */
    private static function construirSemanas(int $mes, int $anio, int $diasMes, int $diaSemana, ?int $diaSeleccionado, Carbon $hoy): array
    {
        $semanas = [];
        $semanaActual = [];
        
        // Obtener fecha de hoy sin hora (solo día, mes, año) para comparación
        $fechaHoy = $hoy->copy()->startOfDay();
        
        // Días vacíos al inicio
        for ($i = 1; $i < $diaSemana; $i++) {
            $semanaActual[] = null;
        }
        
        // Días del mes
        for ($dia = 1; $dia <= $diasMes; $dia++) {
            // Crear fecha del día actual del bucle
            $fechaDia = Carbon::create($anio, $mes, $dia, 0, 0, 0, 'America/Argentina/Buenos_Aires');
            
            // Verificar si es anterior a hoy
            $esPasado = $fechaDia->lt($fechaHoy);
            
            $semanaActual[] = [
                'dia' => $dia,
                'seleccionado' => ($diaSeleccionado === $dia),
                'deshabilitado' => $esPasado
            ];
            
            if (count($semanaActual) == 7) {
                $semanas[] = $semanaActual;
                $semanaActual = [];
            }
        }
        
        // Completar última semana
        if (count($semanaActual) > 0) {
            while (count($semanaActual) < 7) {
                $semanaActual[] = null;
            }
            $semanas[] = $semanaActual;
        }
        
        return $semanas;
    }
    
    /**
     * Obtiene los horarios del día con su disponibilidad
     * @return array Array de horarios con estructura [inicio, fin, disponible, link]
     */
    private static function obtenerHorariosConDisponibilidad(int $anio, int $mes, int $dia): array
    {
        $horaInicio = 16;
        $horaFin = 23;
        $duracion = 1.5; // horas
        
        $horarios = [];
        
        // Usar Carbon para crear la fecha y validar
        $fecha = Carbon::create($anio, $mes, $dia, 0, 0, 0, 'America/Argentina/Buenos_Aires');
        $fechaConsulta = $fecha->format('Y-m-d');
        
        // Generar franjas horarias usando Carbon
        $horaActual = Carbon::create($anio, $mes, $dia, $horaInicio, 0, 0, 'America/Argentina/Buenos_Aires');
        $horaLimite = Carbon::create($anio, $mes, $dia, $horaFin, 0, 0, 'America/Argentina/Buenos_Aires');
        
        while ($horaActual->lt($horaLimite)) {
            $horaInicio = $horaActual->copy();
            $horaFin = $horaActual->copy()->addMinutes($duracion * 60);
            
            $horaStr = $horaInicio->format('H:i');
            $finStr = $horaFin->format('H:i');
            
            // Verificar disponibilidad usando el modelo de dominio
            $reservado = Disponibilidad::horarioTotalmenteOcupado($fechaConsulta, $horaStr);
            
            $horarios[] = [
                'inicio' => $horaStr,
                'fin' => $finStr,
                'disponible' => !$reservado,
                'link' => "Reservar.php?anio=$anio&mes=$mes&dia=$dia&hora=" . urlencode($horaStr) . "&fin=" . urlencode($finStr)
            ];
            
            // Avanzar a la siguiente franja
            $horaActual->addMinutes($duracion * 60);
        }
        
        return $horarios;
    }
}
