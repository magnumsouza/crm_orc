<?php
// Configuração de OAuth Google
// IMPORTANTE: Configure suas credenciais do Google no Google Cloud Console
// https://console.cloud.google.com/

return [
    'google' => [
        // Seu Client ID do Google Cloud Console
        'client_id' => 'SEU_CLIENT_ID_AQUI.apps.googleusercontent.com',
        
        // Seu Client Secret do Google Cloud Console
        'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
        
        // URL de callback (deve estar configurada no Google Cloud Console também)
        'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
        
        // Scopes que você quer solicitar ao usuário
        'scopes' => [
            'openid',
            'email',
            'profile',
        ],
    ],
];
?>
