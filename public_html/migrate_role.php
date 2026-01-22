<?php
/**
 * Migração simples para adicionar coluna role em users.
 * Remova este arquivo em produção após executar.
 */

define('APP_PATH', __DIR__);
require_once APP_PATH . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Migração - Role de Usuários</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 640px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            h1 { color: #222; }
            .warning { background: #fff3cd; padding: 15px; border-radius: 4px; border-left: 4px solid #ffc107; margin: 20px 0; }
            button { background: #4d7dff; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
            button:hover { background: #2750d6; }
            .info { color: #555; font-size: 14px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🗄️ Migração da coluna role</h1>
            <p>Este script adiciona a coluna <strong>role</strong> na tabela <strong>users</strong>.</p>
            <div class="warning">
                <strong>⚠️ Aviso:</strong> faça backup antes de continuar.
            </div>
            <form method="POST">
                <label>
                    <input type="checkbox" name="confirm" required>
                    Confirmo que tenho backup do banco
                </label>
                <p class="info">
                    <button type="submit">Executar Migração</button>
                </p>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Migração</title><style>body{font-family:Arial;margin:20px;background:#f5f5f5;}pre{background:#eee;padding:15px;border-radius:4px;overflow-x:auto;}.success{color:green;}.error{color:red;}</style></head><body>";
echo "<h1>🗄️ Executando Migração...</h1>";

try {
    $pdo = db();

    // Adicionar coluna role se ainda não existir
    $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('admin','viewer') NOT NULL DEFAULT 'viewer'");
    echo "<p class='success'>✓ Coluna role adicionada</p>";

    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE username = 'admin'");
    $stmt->execute();
    echo "<p class='success'>✓ Usuário admin definido como administrador</p>";

    echo "<h2>✓ Migração Concluída</h2>";
    echo "<p><a href='index.php?action=login'>Ir para login →</a></p>";
} catch (Exception $e) {
    $message = $e->getMessage();
    if (stripos($message, 'Duplicate column') !== false) {
        echo "<p class='success'>✓ Coluna role já existia</p>";
        echo "<p class='success'>✓ Nenhuma ação adicional necessária</p>";
        echo "<p><a href='index.php?action=login'>Ir para login →</a></p>";
    } else {
        echo "<h2 class='error'>✗ Erro na Migração</h2>";
        echo "<p class='error'>" . htmlspecialchars($message) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}

echo "</body></html>";
