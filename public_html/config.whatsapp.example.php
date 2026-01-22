<?php
/**
 * Arquivo de Configuração de Integração WhatsApp
 * 
 * Descomente a opção desejada e configure com suas credenciais
 * Inclua este arquivo em seu index.php ANTES de usar o SchedulesController
 */

// Opção 1: Twilio (Recomendado)
// Instale via composer: composer require twilio/sdk
/*
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'twilio',
    'account_sid' => 'seu_account_sid_aqui',
    'auth_token' => 'seu_auth_token_aqui',
    'phone_number' => '+5511999999999', // Seu número Twilio
]);
*/

// Opção 2: Zenvia
/*
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'zenvia',
    'zenvia_api_key' => 'sua_api_key_aqui',
    'phone_number' => 'seu_numero_aqui',
]);
*/

// Opção 3: Meta WhatsApp Cloud API
/*
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'whatsapp_cloud',
    'wa_phone_id' => 'seu_phone_id_aqui',
    'wa_access_token' => 'seu_access_token_aqui',
]);
*/

// Modo de Teste (registra mensagens em log)
// Por padrão, sem configuração, as mensagens são registradas em logs/whatsapp.log
require_once __DIR__ . '/services/WhatsAppService.php';
WhatsAppService::configure([
    'provider' => '', // Deixe vazio para usar modo de teste (log)
]);
