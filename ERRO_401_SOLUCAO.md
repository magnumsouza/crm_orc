# ⚡ ERRO 401: invalid_client - SOLUÇÃO RÁPIDA

## O Problema
O erro **401: invalid_client** significa que você precisa configurar suas credenciais do Google.

## A Solução (3 minutos)

### 1️⃣ Obter Credenciais do Google
- Acesse: https://console.cloud.google.com/
- Clique em "Select a Project" → "NEW PROJECT" → "CRM Orcamentos" → "CREATE"
- Em "APIs & Services" → "OAuth consent screen":
  - Escolha **External**
  - Preencha: App name = "CRM Orcamentos"
  - Seu email em "User support email"
  - Clique "SAVE AND CONTINUE" até terminar
- Em "APIs & Services" → "Credentials":
  - Clique "+ CREATE CREDENTIALS" → "OAuth client ID"
  - Tipo: **Web application**
  - Nome: "CRM Orcamentos"
  - **Authorized JavaScript origins** (ADD URI):
    - `http://localhost`
    - `http://127.0.0.1`
  - **Authorized redirect URIs** (ADD URI):
    - `http://localhost/crm-orcamentos/public_html/index.php?action=google_callback`
  - Clique "CREATE"
  - Copie o **Client ID** e **Client Secret**

### 2️⃣ Editar Arquivo de Configuração
1. Abra: `public_html/config.oauth.php`
2. Procure:
   ```php
   'client_id' => 'SEU_CLIENT_ID_AQUI.apps.googleusercontent.com',
   'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
   ```
3. Substitua pelos seus valores copiados do Google Cloud Console
4. Salve o arquivo

### 3️⃣ Testar
1. Vá para: http://localhost/crm-orcamentos/public_html/index.php?action=login
2. Clique no botão **Google**
3. Pronto! 🎉

## 📝 Arquivo de Exemplo
Consulte `config.oauth.example.php` para ver como fica o arquivo preenchido.

## 📖 Guia Completo
Leia `OBTER_CREDENCIAIS_GOOGLE.md` para instruções detalhadas com imagens.

## ⚠️ Lembre-se
- **Client Secret** é confidencial - nunca compartilhe!
- Sempre verifique maiúsculas/minúsculas
- Copie sem espaços extras

---

**Pronto para configurar?** Abra o Google Cloud Console acima e siga os 3 passos!
