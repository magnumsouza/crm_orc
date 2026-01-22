<?php
/**
 * Script para Executar o Schema SQL
 * 
 * Este script cria/atualiza as tabelas do módulo de agendamentos
 * Execute via: http://seu-site.com/public_html/migrate.php
 * 
 * ⚠️ REMOVA ESTE ARQUIVO EM PRODUÇÃO!
 */

define('APP_PATH', __DIR__);
require_once APP_PATH . '/db.php';

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Exibir formulário
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Migração - Módulo de Agendamentos</title>
        <style>
            body { font-family: Arial; margin: 20px; background: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            h1 { color: #333; }
            .warning { background: #fff3cd; padding: 15px; border-radius: 4px; border-left: 4px solid #ffc107; margin: 20px 0; }
            button { background: #4d7dff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
            button:hover { background: #2750d6; }
            .info { color: #666; font-size: 14px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🗄️ Migração do Banco de Dados</h1>
            <p>Este script irá criar/atualizar as tabelas necessárias para o módulo de agendamentos.</p>
            
            <div class="warning">
                <strong>⚠️ Aviso:</strong> Este procedimento irá criar novas tabelas e não afetará dados existentes.
            </div>

            <form method="POST">
                <label>
                    <input type="checkbox" name="confirm" required>
                    Confirmo que tenho um backup do banco de dados
                </label>
                <p class="info">
                    <button type="submit">Executar Migração</button>
                </p>
            </form>

            <p class="info">
                <strong>Tabelas que serão criadas:</strong><br>
                - schedules<br>
                - schedule_settings
            </p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Processar migração
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Migração</title><style>body{font-family:Arial;margin:20px;background:#f5f5f5;}pre{background:#eee;padding:15px;border-radius:4px;overflow-x:auto;}.success{color:green;}.error{color:red;}</style></head><body>";
echo "<h1>🗄️ Executando Migração...</h1>";

try {
    $pdo = db();

    // SQL para criar tabelas
    $sql = file_get_contents(__DIR__ . '/../schema.sql');
    
    // Executar cada comando
    $commands = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($cmd) => !empty($cmd) && strpos($cmd, 'INSERT') === false
    );

    foreach ($commands as $command) {
        if (strpos($command, 'CREATE TABLE') !== false) {
            $pdo->exec($command);
            preg_match('/CREATE TABLE.*?(\w+)\s*\(/', $command, $matches);
            $tableName = $matches[1] ?? 'unknown';
            echo "<p class='success'>✓ Tabela '$tableName' criada/atualizada</p>";
        }
    }

    // Inserir dados de configuração padrão
    $checkSettings = $pdo->query("SELECT COUNT(*) FROM schedule_settings");
    if ($checkSettings->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO schedule_settings (business_hours_start, business_hours_end, business_days) 
            VALUES ('07:00:00', '17:00:00', '1,2,3,4,5,6')
        ");
        echo "<p class='success'>✓ Configurações padrão inseridas</p>";
    }

    echo "<h2>✓ Migração Concluída com Sucesso!</h2>";
    echo "<p>As tabelas do módulo de agendamentos foram criadas/atualizadas.</p>";
    echo "<p><a href='index.php?action=schedules'>Ir para Agendamentos →</a></p>";

} catch (Exception $e) {
    echo "<h2 class='error'>✗ Erro na Migração</h2>";
    echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
