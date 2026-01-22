<?php
// Configuracao de OAuth Google
// IMPORTANTE: Configure suas credenciais do Google no Google Cloud Console
// https://console.cloud.google.com/

return [
    'google' => [
        // Seu Client ID do Google Cloud Console
        'client_id' => 'SEU_CLIENT_ID_AQUI',
        
        // Seu Client Secret do Google Cloud Console
        'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
        
        // URL de callback (deve estar configurada no Google Cloud Console tambem)
        'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
        
        // Scopes que voce quer solicitar ao usuario
        'scopes' => [
            'openid',
            'email',
            'profile',
        ],
    ],
];
?>
