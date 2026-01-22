# 🔑 Obter Credenciais do Google Cloud Console - Guia Passo a Passo

## ❌ Erro Encontrado
Você recebeu: **Erro 401: invalid_client**

Isso significa que as credenciais no arquivo `config.oauth.php` ainda são os placeholders padrão e precisam ser substituídas pelas suas credenciais reais do Google.

## ✅ Passo a Passo Para Obter as Credenciais

### PASSO 1: Acessar Google Cloud Console
1. Abra: https://console.cloud.google.com/
2. Faça login com sua conta Google pessoal ou corporativa
3. Se solicitado, concorde com os Termos de Serviço

### PASSO 2: Criar um Novo Projeto (se necessário)

**Se você JÁ TEM um projeto**, pule para PASSO 3.

Se não tem:
1. No canto superior esquerdo, clique em **"Select a Project"**
2. Clique em **"NEW PROJECT"** (botão azul no canto superior direito)
3. Preencha:
   - **Project name**: `CRM Orcamentos` (ou o nome que desejar)
   - **Folder**: Deixe em branco
4. Clique **CREATE**
5. Aguarde alguns segundos enquanto o projeto é criado
6. Selecione o projeto na lista

### PASSO 3: Ativar Google+ API

1. No menu esquerdo, vá em **APIs & Services** → **Library**
2. Na barra de busca, digite: **Google+ API**
3. Clique em **Google+ API** (ou **People API**)
4. Clique no botão azul **ENABLE**
5. Aguarde a ativação

### PASSO 4: Configurar Tela de Consentimento OAuth (IMPORTANTE!)

1. No menu esquerdo, clique em **APIs & Services** → **OAuth consent screen**
2. Se aparecer um aviso, escolha:
   - **User Type**: Selecione **External**
   - Clique **CREATE**
3. Preencha os dados do aplicativo:
   - **App name**: `CRM Orcamentos`
   - **User support email**: Seu email (ex: seu@email.com)
   - Em **Developer contact information**, adicione seu email
4. Clique **SAVE AND CONTINUE**
5. **Scopes**: Clique **SAVE AND CONTINUE** (sem modificar)
6. **Test users**: Clique **SAVE AND CONTINUE** (sem modificar)
7. Revise os dados e clique **BACK TO DASHBOARD**

### PASSO 5: Criar as Credenciais OAuth 2.0

1. No menu esquerdo, clique em **APIs & Services** → **Credentials**
2. Clique no botão **+ CREATE CREDENTIALS** (canto superior)
3. Selecione **OAuth client ID**
4. Se aparacecer um aviso "You will need to create an OAuth 2.0 consent screen", volte ao PASSO 4
5. Escolha o tipo de aplicação:
   - **Application type**: Selecione **Web application**
   - **Name**: `CRM Orcamentos` (ou qualquer nome)
6. Em **Authorized JavaScript origins**, clique **+ ADD URI** e adicione:
   ```
   http://localhost
   http://localhost:80
   http://127.0.0.1
   http://127.0.0.1:80
   ```
7. Em **Authorized redirect URIs**, clique **+ ADD URI** e adicione:
   ```
   http://localhost/crm-orcamentos/public_html/index.php?action=google_callback
   http://127.0.0.1/crm-orcamentos/public_html/index.php?action=google_callback
   ```
8. Clique **CREATE**

### PASSO 6: Copiar suas Credenciais

Após clicar CREATE, uma janela popup aparecerá com:
- **Your Client ID** (algo como: `123456789-abcdefghijklmnopqrst.apps.googleusercontent.com`)
- **Your Client Secret** (algo como: `GOCSPX-AbCdEfGhIjKlMnOpQrStUv`)

**COPIE AMBAS AS INFORMAÇÕES**

Se a janela fechar, você ainda pode ver as credenciais:
1. Em **APIs & Services** → **Credentials**
2. Na seção **OAuth 2.0 Client IDs**, clique no seu projeto
3. Copie o **Client ID** e **Client Secret**

## 🔧 Atualizar o Arquivo de Configuração

Agora que você tem suas credenciais:

1. Abra o arquivo: `public_html/config.oauth.php`
2. Encontre estas linhas:
   ```php
   'client_id' => 'SEU_CLIENT_ID_AQUI.apps.googleusercontent.com',
   'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
   ```
3. Substitua pelos seus valores. **EXEMPLO**:
   ```php
   'client_id' => '123456789-abcdefghijklmnopqrst.apps.googleusercontent.com',
   'client_secret' => 'GOCSPX-AbCdEfGhIjKlMnOpQrStUv',
   ```

## ⚠️ CUIDADO!

- **NUNCA compartilhe o `client_secret`** em repositórios públicos ou com outras pessoas
- Se você acidentalmente compartilhou, vá em Credentials e delete essa credencial
- Crie uma nova credencial (repita PASSO 5)

## 🧪 Testar Após Configurar

1. Acesse: http://localhost/crm-orcamentos/public_html/index.php?action=login
2. Clique no botão **Google**
3. Se aparecer a tela de login do Google, você configurou corretamente!
4. Faça login com sua conta Google
5. Você deve ser redirecionado ao dashboard

## 🐛 Se Ainda Receber Erro 401

**Possíveis causas:**

1. ❌ Caracteres extras (espaços no início/fim)
   - Solução: Copie novamente as credenciais com cuidado

2. ❌ Copiei errado o Client ID ou Secret
   - Solução: Delete a credencial e crie uma nova (PASSO 5)

3. ❌ Esqueci de clicar ENABLE na API
   - Solução: Volte ao PASSO 3 e ative a Google+ API

4. ❌ Esqueci de configurar a tela de consentimento
   - Solução: Volte ao PASSO 4 e configure

5. ❌ A URL de redirect não está exatamente igual
   - Solução: Verifique maiúsculas/minúsculas e caracteres especiais

## 📍 Localizações Importantes

- **Config File**: `public_html/config.oauth.php`
- **Google Cloud Console**: https://console.cloud.google.com/
- **APIs & Services**: https://console.cloud.google.com/apis/dashboard
- **Credentials**: https://console.cloud.google.com/apis/credentials
- **OAuth Consent**: https://console.cloud.google.com/apis/credentials/consent

## 💡 Dica

Se você estiver usando um **domínio real** (não localhost), substitua:
- `localhost` por seu domínio (ex: `crm.seusite.com.br`)
- `http://` por `https://` (recomendado)

Exemplo para produção:
```php
'redirect_uri' => 'https://crm.seusite.com.br/public_html/index.php?action=google_callback',
```

---

**Após seguir todos os passos, o login com Google funcionará perfeitamente!**
Se precisar de ajuda com o Google Cloud Console, assista um tutorial rápido: https://www.google.com/search?q=google+cloud+console+oauth+2.0+credentials
