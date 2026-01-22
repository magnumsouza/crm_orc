# ⚡ QUICK START - Módulo de Agendamentos

## 3 PASSOS = Sistema Pronto ✅

### PASSO 1️⃣: Instalar (2 min)
```bash
mysql -u root < schema.sql
```

### PASSO 2️⃣: Validar (1 min)
```
http://seu-site.com/public_html/test-schedules.php
```

### PASSO 3️⃣: Usar (0 min)
```
http://seu-site.com/index.php?action=schedules
```

---

## 🎯 Primeiro Agendamento

1. **Cadastre um cliente** (se não tiver)
   - Clique em "Clientes"
   - Cadastre com telefone/WhatsApp

2. **Crie agendamento**
   - Clique em "Agendamentos"
   - Clique em "Novo Agendamento"
   - Selecione cliente
   - Descreva o serviço
   - Escolha data/hora (7h-17h, seg-sab)
   - Clique "Agendar"

3. **Cliente recebe WhatsApp** 📱
   - Mensagem automática enviada
   - (em modo teste, veja `/logs/whatsapp.log`)

---

## 🔧 Configurar WhatsApp

### Modo Teste (Padrão)
Mensagens são salvas em `/logs/whatsapp.log`
Perfeito para desenvolvimento!

### Modo Real (Twilio)
```bash
# 1. Copiar arquivo
cp public_html/config.whatsapp.example.php public_html/config.whatsapp.php

# 2. Editar com sua Account SID e Auth Token
nano public_html/config.whatsapp.php

# 3. Pronto! Mensagens reais serão enviadas
```

---

## 📊 O que você consegue fazer

### ✅ Criar Agendamento
- Selecionar cliente
- Descrever serviço
- Escolher data/hora
- Cliente recebe WhatsApp

### ✅ Atualizar Agendamento
- Editar dados
- Mudar status
- Cliente recebe notificação

### ✅ Gerenciar Status
- Agendado → Confirmado → Concluído
- Cada mudança notifica cliente
- Dashboard mostra estatísticas

### ✅ Buscar Agendamentos
- Por cliente
- Por serviço
- Ver detalhes

---

## 🆘 Problemas?

### "Nenhum cliente aparece"
→ Cadastre um em "Clientes" primeiro

### "Nenhum horário disponível"
→ Data deve ser seg-sab, 7h-17h, no futuro

### "WhatsApp não envia"
→ Verifique `config.whatsapp.php`
→ Em modo teste, veja `/logs/whatsapp.log`

### "Teste falha"
→ Certifique-se que executou `schema.sql`
→ Verifique permissões do banco

---

## 📚 Documentação

| Documento | Para quem? |
|-----------|-----------|
| `README_AGENDAMENTOS.md` | Overview geral |
| `INTEGRATION_GUIDE.md` | Setup completo |
| `SCHEDULES_README.md` | Detalhes técnicos |
| `POST_INSTALLATION.md` | Troubleshooting |

---

## 🚀 Pronto!

Seu CRM agora tem um sistema profissional de agendamentos com WhatsApp integrado!

**Acesse agora**: http://seu-site.com/index.php?action=schedules

Enjoy! 🎉
