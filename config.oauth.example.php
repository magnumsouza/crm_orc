<?php
/**
 * EXEMPLO DE COMO PREENCHER config.oauth.php
 * 
 * Siga estes passos:
 * 1. Abra config.oauth.php
 * 2. Substitua 'SEU_CLIENT_ID_AQUI' pelo seu Client ID do Google
 * 3. Substitua 'SEU_CLIENT_SECRET_AQUI' pelo seu Client Secret
 * 4. Salve o arquivo
 * 
 * EXEMPLO PREENCHIDO:
 */

return [
    'google' => [
        'client_id' => '123456789-abcdefghijklmnopqrstuvwxyz.apps.googleusercontent.com',
        'client_secret' => 'GOCSPX-1234567890ABCDEFGHIJKLmnopqrst',
        'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
        'scopes' => [
            'openid',
            'email',
            'profile',
        ],
    ],
];

/**
 * PARA PRODUÇÃO COM HTTPS E DOMÍNIO REAL:
 * 
 * return [
 *     'google' => [
 *         'client_id' => 'SEU_CLIENT_ID.apps.googleusercontent.com',
 *         'client_secret' => 'SEU_CLIENT_SECRET',
 *         'redirect_uri' => 'https://seu-dominio.com.br/crm-orcamentos/public_html/index.php?action=google_callback',
 *         'scopes' => ['openid', 'email', 'profile'],
 *     ],
 * ];
 */
?>
