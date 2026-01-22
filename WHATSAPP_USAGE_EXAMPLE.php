<?php
/**
 * Exemplo de Uso do WhatsApp Service
 * 
 * Este arquivo demonstra como usar o serviço WhatsApp
 * para enviar notificações de agendamentos
 */

// 1. Incluir o serviço
require_once __DIR__ . '/services/WhatsAppService.php';

// 2. (Opcional) Configurar com suas credenciais
// Descomente uma das opções abaixo:

// ===== OPÇÃO 1: TWILIO =====
/*
WhatsAppService::configure([
    'provider' => 'twilio',
    'account_sid' => 'seu_account_sid',
    'auth_token' => 'seu_auth_token',
    'phone_number' => '+5511999999999',
]);
*/

// ===== OPÇÃO 2: ZENVIA =====
/*
WhatsAppService::configure([
    'provider' => 'zenvia',
    'zenvia_api_key' => 'sua_api_key',
    'phone_number' => 'seu_numero',
]);
*/

// ===== OPÇÃO 3: META WHATSAPP CLOUD =====
/*
WhatsAppService::configure([
    'provider' => 'whatsapp_cloud',
    'wa_phone_id' => 'seu_phone_id',
    'wa_access_token' => 'seu_access_token',
]);
*/

// 3. Exemplos de uso

// Exemplo 1: Enviar notificação de novo agendamento
/*
$client = [
    'name' => 'João Silva',
    'phone' => '(11) 98765-4321',
    'email' => 'joao@example.com',
];

$schedule = [
    'id' => 1,
    'client_id' => 1,
    'service_description' => 'Limpeza de escritório',
    'scheduled_date' => '2024-01-22',
    'scheduled_time' => '10:00:00',
    'status' => 'Agendado',
];

WhatsAppService::notifyNewSchedule($client, $schedule);
*/

// Exemplo 2: Enviar notificação de confirmação
/*
WhatsAppService::notifyScheduleConfirmed($client, $schedule);
*/

// Exemplo 3: Enviar notificação de cancelamento
/*
WhatsAppService::notifyScheduleCancelled($client, $schedule);
*/

// Exemplo 4: Enviar lembrete 24h antes
/*
WhatsAppService::sendReminder($client, $schedule);
*/

// Exemplo 5: Enviar mensagem customizada
/*
$phone = '11987654321';
$message = 'Olá! Este é um teste de WhatsApp. 👋';
WhatsAppService::send($phone, $message);
*/

// ============================================
// FLUXO COMPLETO DE EXEMPLO
// ============================================

// Simulando dados do banco de dados
$example_client = [
    'id' => 1,
    'name' => 'Maria Silva',
    'email' => 'maria@example.com',
    'phone' => '+55 (11) 98765-4321',
    'company' => 'Silva Consultoria',
];

$example_schedule = [
    'id' => 123,
    'client_id' => 1,
    'service_description' => 'Consultoria empresarial',
    'scheduled_date' => '2024-01-22',
    'scheduled_time' => '14:30:00',
    'status' => 'Agendado',
    'notes' => 'Importante para o projeto X',
];

// Simulando o fluxo de criação de agendamento
echo "<!-- EXEMPLO DE USO DO WHATSAPP SERVICE -->\n";
echo "<!-- Por padrão, as mensagens serão salvas em: logs/whatsapp.log -->\n\n";

// Quando um agendamento é criado
// WhatsAppService::notifyNewSchedule($example_client, $example_schedule);
// echo "✓ Notificação de novo agendamento enviada para " . $example_client['phone'] . "\n";

// Quando o status é alterado para "Confirmado"
// WhatsAppService::notifyScheduleConfirmed($example_client, $example_schedule);
// echo "✓ Notificação de confirmação enviada para " . $example_client['phone'] . "\n";

// Quando 24 horas antes do agendamento (pode ser agendado via cron)
// WhatsAppService::sendReminder($example_client, $example_schedule);
// echo "✓ Lembrete enviado para " . $example_client['phone'] . "\n";

// Quando o status é alterado para "Cancelado"
// WhatsAppService::notifyScheduleCancelled($example_client, $example_schedule);
// echo "✓ Notificação de cancelamento enviada para " . $example_client['phone'] . "\n";

?>

<!-- DOCUMENTAÇÃO -->

ESTRUTURA DO CLIENTE:
- id: ID do cliente
- name: Nome completo
- email: Email
- phone: Telefone (pode estar em qualquer formato)
- company: Empresa

ESTRUTURA DO AGENDAMENTO:
- id: ID do agendamento
- client_id: ID do cliente
- service_description: Descrição do serviço
- scheduled_date: Data (YYYY-MM-DD)
- scheduled_time: Hora (HH:MM:SS)
- status: Estado atual (Agendado, Confirmado, Concluído, Cancelado)
- notes: Observações

MÉTODOS DISPONÍVEIS:

1. WhatsAppService::configure(array $config)
   - Configura as credenciais
   - Deve ser chamado ANTES de enviar mensagens

2. WhatsAppService::send(string $phone, string $message)
   - Envia mensagem customizada
   - Retorna bool (sucesso ou falha)

3. WhatsAppService::notifyNewSchedule(array $client, array $schedule)
   - Notifica sobre novo agendamento

4. WhatsAppService::notifyScheduleUpdate(array $client, array $schedule)
   - Notifica sobre atualização de agendamento

5. WhatsAppService::notifyScheduleConfirmed(array $client, array $schedule)
   - Notifica confirmação de agendamento

6. WhatsAppService::notifyScheduleCancelled(array $client, array $schedule)
   - Notifica cancelamento de agendamento

7. WhatsAppService::sendReminder(array $client, array $schedule)
   - Envia lembrete do agendamento

TELEFONES SUPORTADOS:
- (11) 98765-4321      ✓
- 11987654321          ✓
- +55 11 98765-4321    ✓
- 987654321            ✓ (será adicionado DDD 55)
- 98765-4321           ✗ (muito curto, será rejeitado)

MODO DE TESTE:
- Sem configuração, as mensagens são salvas em logs/whatsapp.log
- Útil para desenvolvimento e testes
- Use para validar a integração antes de produção
