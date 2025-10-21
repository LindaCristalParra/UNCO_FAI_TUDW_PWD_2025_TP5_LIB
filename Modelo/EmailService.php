<?php
// Modelo/EmailService.php
// Lógica de negocio para envío de emails usando Symfony Mailer
// Utiliza las plantillas HTML en Vista/Email/

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class EmailService
{
    private static $config = null;
    
    /**
     * Carga la configuración de email
     */
    private static function cargarConfig(): array
    {
        if (self::$config === null) {
            $configFile = __DIR__ . '/../config/email.php';
            if (file_exists($configFile)) {
                self::$config = require $configFile;
            } else {
                // Configuración por defecto
                self::$config = [
                    'smtp_host' => 'smtp.gmail.com',
                    'smtp_port' => 587,
                    'smtp_encryption' => 'tls',
                    'smtp_username' => 'tu-email@gmail.com',
                    'smtp_password' => 'tu-contraseña-de-aplicacion',
                    'from_email' => 'tu-email@gmail.com',
                    'from_name' => 'Puro Fútbol - Reservas'
                ];
            }
        }
        return self::$config;
    }
    
    /**
     * Crea y configura el mailer
     */
    private static function crearMailer(): Mailer
    {
        $config = self::cargarConfig();
        
        // Crear DSN (Data Source Name) para el transporte SMTP
        // Regla:
        //  - TLS (587):  smtp://user:pass@host:587?encryption=tls&auth_mode=login
        //  - SSL (465): smtps://user:pass@host:465
        //  - Sin cifrado: smtp://user:pass@host:25
        $encryption = $config['smtp_encryption'] ?? null;
        $scheme = ($encryption === 'ssl') ? 'smtps' : 'smtp';
        $dsn = sprintf(
            '%s://%s:%s@%s:%d',
            $scheme,
            urlencode($config['smtp_username'] ?? ''),
            urlencode($config['smtp_password'] ?? ''),
            $config['smtp_host'] ?? 'localhost',
            (int)($config['smtp_port'] ?? 25)
        );
        // Agregar query params para TLS u otras opciones
        $query = [];
        if ($encryption === 'tls') {
            $query[] = 'encryption=tls';
        }
        // Forzar modo de auth 'login' para máxima compatibilidad con Gmail/Outlook
        $query[] = 'auth_mode=login';
        if (!empty($query)) {
            $dsn .= '?' . implode('&', $query);
        }
        
        // Log de diagnóstico (sin contraseña)
        $dsnSafe = preg_replace('/:(.*?)@/', ':****@', $dsn);
        error_log('[EmailService] Creando transporte con DSN: ' . $dsnSafe);

        $transport = Transport::fromDsn($dsn);
        return new Mailer($transport);
    }
    
    /**
     * Renderiza una plantilla HTML con variables
     */
    private static function renderizarPlantilla(string $plantilla, array $variables): string
    {
        extract($variables);
        ob_start();
        include __DIR__ . '/../Vista/Email/' . $plantilla;
        return ob_get_clean();
    }
    
    /**
     * Envía email de confirmación de reserva
     * 
     * @param string $destinatario Email del cliente
     * @param array $datos Datos de la reserva: ['nombre', 'fecha', 'hora', 'cancha']
     * @return array Resultado ['exito' => bool, 'mensaje' => string]
     */
    public static function enviarConfirmacion(string $destinatario, array $datos): array
    {
        try {
            $config = self::cargarConfig();
            $mailer = self::crearMailer();
            
            // Renderizar plantilla HTML
            $htmlBody = self::renderizarPlantilla('confirmacionReserva.php', $datos);
            
            // Crear el email
            $email = (new Email())
                ->from(sprintf('%s <%s>', $config['from_name'] ?? 'Puro Fútbol - Reservas', $config['from_email']))
                ->to($destinatario)
                ->subject('Confirmación de Reserva - Puro Fútbol')
                ->html($htmlBody);
            
            // Enviar
            error_log('[EmailService] Enviando confirmación a: ' . $destinatario . ' desde: ' . $config['from_email']);
            $mailer->send($email);
            error_log('[EmailService] Confirmación enviada OK');
            
            return [
                'exito' => true,
                'mensaje' => 'Email de confirmación enviado correctamente'
            ];
            
        } catch (\Exception $e) {
            error_log('[EmailService] Error enviarConfirmacion: ' . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al enviar email: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Envía email de cancelación de reserva
     * 
     * @param string $destinatario Email del cliente
     * @param array $datos Datos de la reserva: ['nombre', 'fecha', 'hora', 'cancha']
     * @return array Resultado ['exito' => bool, 'mensaje' => string]
     */
    public static function enviarCancelacion(string $destinatario, array $datos): array
    {
        try {
            $config = self::cargarConfig();
            $mailer = self::crearMailer();
            
            // Renderizar plantilla HTML
            $htmlBody = self::renderizarPlantilla('cancelacionReserva.php', $datos);
            
            // Crear el email
            $email = (new Email())
                ->from(sprintf('%s <%s>', $config['from_name'] ?? 'Puro Fútbol - Reservas', $config['from_email']))
                ->to($destinatario)
                ->subject('Cancelación de Reserva - Puro Fútbol')
                ->html($htmlBody);
            
            // Enviar
            error_log('[EmailService] Enviando cancelación a: ' . $destinatario . ' desde: ' . $config['from_email']);
            $mailer->send($email);
            error_log('[EmailService] Cancelación enviada OK');
            
            return [
                'exito' => true,
                'mensaje' => 'Email de cancelación enviado correctamente'
            ];
            
        } catch (\Exception $e) {
            error_log('[EmailService] Error enviarCancelacion: ' . $e->getMessage());
            return [
                'exito' => false,
                'mensaje' => 'Error al enviar email: ' . $e->getMessage()
            ];
        }
    }
}
