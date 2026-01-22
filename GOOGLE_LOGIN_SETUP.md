# 🔐 Autenticação Google OAuth 2.0 - Guia de Setup

## ✅ O que foi implementado:

1. **Sistema OAuth 2.0 nativo** sem dependências externas
2. **Botão de login com Google** na página de login
3. **Criação automática de usuários** ao fazer login pela primeira vez com Google
4. **Sincronização de dados** (nome e email)
5. **Coluna de email** adicionada à tabela `users`

## 🔧 Próximos Passos - Configurar suas Credenciais Google:

### Passo 1: Acessar Google Cloud Console
👉 https://console.cloud.google.com/

### Passo 2: Criar Projeto (se não tiver)
- Clique em "Select a Project" (canto superior esquerdo)
- Clique em "NEW PROJECT"
- Nome: "CRM Orcamentos" (ou qualquer nome)
- Clique em "CREATE"

### Passo 3: Configurar Tela de Consentimento OAuth
1. No menu esquerdo: **APIs & Services** → **OAuth consent screen**
2. Selecione **External** e clique **CREATE**
3. Preencha:
   - **App name**: CRM Orcamentos
   - **User support email**: seu@email.com
   - **Developer contact**: seu@email.com
4. Clique **SAVE AND CONTINUE** (pule as outras seções)

### Passo 4: Criar Credenciais OAuth
1. Em **APIs & Services** → **Credentials**
2. Clique **+ CREATE CREDENTIALS** → **OAuth client ID**
3. Selecione **Web application**
4. Preencha **Name**: CRM Orcamentos
5. Em **Authorized JavaScript origins**, adicione:
   ```
   http://localhost:80
   http://localhost
   http://127.0.0.1:80
   http://127.0.0.1
   ```
6. Em **Authorized redirect URIs**, adicione:
   ```
   http://localhost/crm-orcamentos/public_html/index.php?action=google_callback
   http://127.0.0.1/crm-orcamentos/public_html/index.php?action=google_callback
   ```
7. Clique **CREATE**

### Passo 5: Copiar suas Credenciais
Na tela que aparecer, você verá:
- **Client ID** (algo como `123456789-xxxxx.apps.googleusercontent.com`)
- **Client Secret** (uma string aleatória)

### Passo 6: Atualizar o Arquivo de Configuração

Abra: `public_html/config.oauth.php`

Encontre estas linhas:
```php
'client_id' => 'SEU_CLIENT_ID_AQUI.apps.googleusercontent.com',
'client_secret' => 'SEU_CLIENT_SECRET_AQUI',
'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
```

Substitua pelos seus valores. Exemplo:
```php
'client_id' => '123456789-abcdefghijklmnop.apps.googleusercontent.com',
'client_secret' => 'GOCSPX-1234567890abcdefghijklmno',
'redirect_uri' => 'http://localhost/crm-orcamentos/public_html/index.php?action=google_callback',
```

**⚠️ Importante**: Nunca compartilhe seu `client_secret` em repositórios públicos!

## 🚀 Testar o Login

1. Vá para: http://localhost/crm-orcamentos/public_html/index.php?action=login
2. Clique no botão **Google**
3. Faça login com sua conta Google
4. Você será redirecionado ao dashboard

## 📝 O que acontece no primeiro login:

- Uma nova conta é **criada automaticamente** no sistema
- Nome e email são **sincronizados** do seu perfil Google
- Você é **logado automaticamente**
- Próximos logins com o mesmo email usam a conta existente

## 🐛 Solucionar Problemas

### Erro: "Erro na autenticação com Google"
- Verifique se você copiou corretamente o **Client ID**
- Confirme que clicou em "Aceitar" na tela de consentimento do Google

### Erro: "redirect_uri_mismatch"
- A URL de callback não corresponde às configurações do Google
- Verifique se é exatamente: `http://localhost/crm-orcamentos/public_html/index.php?action=google_callback`
- Maiúsculas/minúsculas importam!

### Erro: "Erro ao obter token"
- Verifique o **Client Secret** - ele pode estar incorreto
- Confirme que CURL está habilitado no PHP (deveria estar por padrão)

### Nenhuma resposta após clicar em Google
- Verifique se adicionou `http://localhost` em **Authorized JavaScript origins**
- Tente usar `http://127.0.0.1` em vez de `localhost`

## 📦 Arquivos Afetados

- ✅ `public_html/controllers/AuthController.php` - Novas funções Google OAuth
- ✅ `public_html/config.oauth.php` - Configurações (crie este arquivo)
- ✅ `public_html/views/login.php` - Adicionado botão Google
- ✅ `public_html/index.php` - Rota para callback Google
- ✅ `schema.sql` - Coluna `email` adicionada à tabela `users`
- ✅ Banco de dados - Coluna `email` criada na tabela `users`

## 🔄 Para Produção (HTTPS):

Se você deseja colocar em produção com HTTPS:

1. Substitua `http://` por `https://` em:
   - `config.oauth.php` na chave `redirect_uri`
   - Adicione também em **Authorized JavaScript origins** e **Authorized redirect URIs** no Google Cloud Console

2. Use um domínio real em vez de `localhost`

3. Exemplo para domínio `crm.empresa.com.br`:
   ```php
   'redirect_uri' => 'https://crm.empresa.com.br/public_html/index.php?action=google_callback',
   ```

## ✨ Recursos Inclusos

- ✅ OAuth 2.0 nativo (sem dependências externas)
- ✅ Criação automática de usuários via Google
- ✅ Sincronização de nome e email
- ✅ Interface responsiva com Tailwind CSS
- ✅ Tratamento de erros
- ✅ Redirect automático após login

---

**Precisa de ajuda?** Verifique [GOOGLE_OAUTH_SETUP.md](./GOOGLE_OAUTH_SETUP.md) para mais detalhes técnicos.
