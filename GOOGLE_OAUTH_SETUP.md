# Configurar Autenticação Google OAuth 2.0

## Passos para habilitar login com Google:

### 1. Criar Projeto no Google Cloud Console

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione um existente
3. No menu esquerdo, vá em **APIs & Services** → **Credentials**
4. Clique em **Create Credentials** → **OAuth client ID**
5. Se solicitado, configure a tela de consentimento OAuth primeiro

### 2. Configurar Tela de Consentimento OAuth

1. Em **APIs & Services**, clique em **OAuth consent screen**
2. Selecione **External** como tipo de usuário
3. Preencha as informações obrigatórias:
   - **App name**: CRM Orçamentos
   - **User support email**: seu@email.com
   - **Developer contact**: seu@email.com
4. Clique em **Save and Continue**
5. Salte escopos opcionais clicando em **Save and Continue**
6. Salte usuários de teste clicando em **Save and Continue**
7. Clique em **Back to Dashboard**

### 3. Criar OAuth 2.0 Client ID

1. Em **APIs & Services** → **Credentials**
2. Clique em **Create Credentials** → **OAuth client ID**
3. Selecione **Web Application**
4. Preencha:
   - **Name**: CRM Orçamentos (ou seu nome)
5. Em **Authorized JavaScript origins**, adicione:
   - `http://localhost:80`
   - `http://localhost`
   - Se usar HTTPS, adicione também com `https://`

6. Em **Authorized redirect URIs**, adicione:
   - `http://localhost/crm-orcamentos/public_html/index.php?action=google_callback`
   - Se usar HTTPS, substitua `http` por `https`

7. Clique em **Create**

### 4. Copiar Credenciais

1. Na tela de credenciais, você verá um popup com:
   - **Client ID** (algo como `xxx.apps.googleusercontent.com`)
   - **Client Secret** (uma string aleatória)

2. Copie essas credenciais

### 5. Atualizar Configuração

Abra o arquivo `public_html/config.oauth.php` e substitua:

```php
'client_id' => 'SEU_CLIENT_ID_AQUI.apps.googleusercontent.com',
'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
```

Por suas credenciais reais. Exemplo:

```php
'client_id' => '123456789-abcdefg.apps.googleusercontent.com',
'client_secret' => 'GOCSPX-abcdefghijklmnop',
'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
```

### 6. Testar Login

1. Abra http://localhost/crm-orcamentos/public_html/index.php?action=login
2. Clique no botão "Google"
3. Faça login com sua conta Google
4. Você será redirecionado ao dashboard

## Notas Importantes:

- ✅ A primeira vez que você logar com Google, uma nova conta será criada no sistema
- ✅ Próximos logins com o mesmo email usarão a conta existente
- ✅ O sistema usa o nome e email do seu perfil Google
- ⚠️ Em produção, use HTTPS e configure URLs corretas (com domínio real)
- ⚠️ Nunca compartilhe seu `client_secret` em repositórios públicos

## Solução de Problemas:

**"Error: redirect_uri_mismatch"**
- Verifique se a URL de callback configurada no Google Cloud Console exatamente igual à do arquivo `config.oauth.php`
- Inclua `http://` ou `https://` completo
- Verifique maiúsculas/minúsculas

**"Erro ao obter token do Google"**
- Verifique credenciais (Client ID e Client Secret)
- Confirme que a função CURL está habilitada (geralmente está por padrão)

**Usuário recém-criado não tem permissões**
- O sistema cria usuários novos com a coluna `email` preenchida
- Se necessário permissões especiais, atualize o usuário no banco de dados

## Banco de Dados

O sistema usa a coluna `email` já existente na tabela `users`. Se a tabela não tiver essa coluna, execute:

```sql
ALTER TABLE users ADD COLUMN email VARCHAR(255) UNIQUE AFTER name;
```
