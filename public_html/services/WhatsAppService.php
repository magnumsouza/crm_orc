<?php

class WhatsAppService
{
    private static $config = [];

    /**
     * Configurar as credenciais da API WhatsApp
     * 
     * Exemplo de uso:
     * WhatsAppService::configure([
     *     'provider' => 'twilio', // ou 'zenvia', 'whatsapp_cloud'
     *     'account_sid' => 'seu_account_sid',
     *     'auth_token' => 'seu_auth_token',
     *     'phone_number' => '+5511999999999',
     * ]);
     */
    public static function configure(array $config): void
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Enviar mensagem via WhatsApp
     */
    public static function send(string $phone, string $message): bool
    {
        // Limpar número do telefone
        $phone = self::sanitizePhone($phone);
        
        if (empty($phone)) {
            return false;
        }

        $provider = self::$config['provider'] ?? '';

        switch ($provider) {
            case 'twilio':
                return self::sendViaTwilio($phone, $message);
            case 'zenvia':
                return self::sendViaZenvia($phone, $message);
            case 'whatsapp_cloud':
                return self::sendViaWhatsAppCloud($phone, $message);
            default:
                return self::logMessage($phone, $message);
        }
    }

    /**
     * Enviar via Twilio
     * Requer: composer require twilio/sdk
     */
    private static function sendViaTwilio(string $phone, string $message): bool
    {
        try {
            if (!isset(self::$config['account_sid']) || !isset(self::$config['auth_token']) || !isset(self::$config['phone_number'])) {
                return self::logMessage($phone, $message);
            }

            // Implementação Twilio
            // $twilio = new Client(self::$config['account_sid'], self::$config['auth_token']);
            // $twilio->messages->create(
            //     $phone,
            //     new PhoneNumber(self::$config['phone_number']),
            //     $message
            // );
            
            return true;
        } catch (Exception $e) {
            error_log("Twilio Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar via Zenvia
     * Requer: API key da Zenvia
     */
    private static function sendViaZenvia(string $phone, string $message): bool
    {
        try {
            if (!isset(self::$config['zenvia_api_key'])) {
                return self::logMessage($phone, $message);
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.zenvia.com/v1/channels/whatsapp/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'X-API-TOKEN: ' . self::$config['zenvia_api_key'],
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'from' => self::$config['phone_number'] ?? 'business',
                    'to' => $phone,
                    'contents' => [
                        [
                            'type' => 'text',
                            'text' => $message,
                        ]
                    ]
                ]),
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            return !empty($response);
        } catch (Exception $e) {
            error_log("Zenvia Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar via WhatsApp Cloud API (Meta)
     * Requer: WhatsApp Business Account
     */
    private static function sendViaWhatsAppCloud(string $phone, string $message): bool
    {
        try {
            if (!isset(self::$config['wa_phone_id']) || !isset(self::$config['wa_access_token'])) {
                return self::logMessage($phone, $message);
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://graph.instagram.com/v18.0/' . self::$config['wa_phone_id'] . '/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . self::$config['wa_access_token'],
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ]
                ]),
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            return !empty($response);
        } catch (Exception $e) {
            error_log("WhatsApp Cloud Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Registrar mensagem em log (quando provider não está configurado)
     */
    private static function logMessage(string $phone, string $message): bool
    {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/whatsapp.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] Para: $phone | Mensagem: $message\n";

        return file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX) !== false;
    }

    /**
     * Sanitizar número de telefone
     */
    private static function sanitizePhone(string $phone): string
    {
        // Remover caracteres especiais
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Adicionar código do país se não tiver
        if (strlen($phone) === 11) { // Formato brasileiro com DDD (85987654321)
            $phone = '55' . $phone;
        } elseif (strlen($phone) === 10) { // Formato brasileiro sem DDD (987654321)
            return '';
        }

        return $phone;
    }

    /**
     * Enviar notificação de novo agendamento
     */
    public static function notifyNewSchedule(array $client, array $schedule): bool
    {
        $message = sprintf(
            "🗓️ *Novo Agendamento*\n\nOlá %s,\n\nSeu agendamento foi confirmado:\n\n" .
            "📅 Data: %s\n" .
            "🕐 Hora: %s\n" .
            "📝 Serviço: %s\n\n" .
            "Obrigado!",
            $client['name'],
            date('d/m/Y', strtotime($schedule['scheduled_date'])),
            substr($schedule['scheduled_time'], 0, 5),
            substr($schedule['service_description'], 0, 50)
        );

        return self::send($client['phone'] ?? '', $message);
    }

    /**
     * Enviar notificação de atualização de agendamento
     */
    public static function notifyScheduleUpdate(array $client, array $schedule): bool
    {
        $message = sprintf(
            "📝 *Agendamento Atualizado*\n\nOlá %s,\n\nSeu agendamento foi atualizado:\n\n" .
            "📅 Data: %s\n" .
            "🕐 Hora: %s\n\n" .
            "Obrigado!",
            $client['name'],
            date('d/m/Y', strtotime($schedule['scheduled_date'])),
            substr($schedule['scheduled_time'], 0, 5)
        );

        return self::send($client['phone'] ?? '', $message);
    }

    /**
     * Enviar notificação de confirmação de agendamento
     */
    public static function notifyScheduleConfirmed(array $client, array $schedule): bool
    {
        $message = sprintf(
            "✅ *Agendamento Confirmado*\n\nOlá %s,\n\nSeu agendamento foi confirmado:\n\n" .
            "📅 Data: %s\n" .
            "🕐 Hora: %s\n\n" .
            "Nos vemos lá!",
            $client['name'],
            date('d/m/Y', strtotime($schedule['scheduled_date'])),
            substr($schedule['scheduled_time'], 0, 5)
        );

        return self::send($client['phone'] ?? '', $message);
    }

    /**
     * Enviar notificação de cancelamento de agendamento
     */
    public static function notifyScheduleCancelled(array $client, array $schedule): bool
    {
        $message = sprintf(
            "❌ *Agendamento Cancelado*\n\nOlá %s,\n\nInformamos que seu agendamento foi cancelado:\n\n" .
            "📅 Data: %s\n" .
            "🕐 Hora: %s\n\n" .
            "Entre em contato conosco se tiver dúvidas.",
            $client['name'],
            date('d/m/Y', strtotime($schedule['scheduled_date'])),
            substr($schedule['scheduled_time'], 0, 5)
        );

        return self::send($client['phone'] ?? '', $message);
    }

    /**
     * Enviar lembrete 24 horas antes do agendamento
     */
    public static function sendReminder(array $client, array $schedule): bool
    {
        $message = sprintf(
            "⏰ *Lembrete de Agendamento*\n\nOlá %s,\n\nLembrete: seu agendamento é amanhã:\n\n" .
            "📅 Data: %s\n" .
            "🕐 Hora: %s\n" .
            "📝 Serviço: %s\n\n" .
            "Não se esqueça!",
            $client['name'],
            date('d/m/Y', strtotime($schedule['scheduled_date'])),
            substr($schedule['scheduled_time'], 0, 5),
            substr($schedule['service_description'], 0, 50)
        );

        return self::send($client['phone'] ?? '', $message);
    }
}
