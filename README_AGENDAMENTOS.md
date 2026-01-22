# 🎉 CRM Orçamentos - Com Módulo de Agendamentos

Bem-vindo ao CRM Orçamentos com o novo módulo de agendamentos de serviços!

## 🚀 O que há de novo?

### ✨ Novo Módulo de Agendamentos
Um módulo completo para gerenciar agendamentos de serviços com:

- 📅 **Horário Comercial Inteligente**: 7h-17h, segunda a sábado (configurável)
- 📱 **Notificações WhatsApp**: Alertas automáticos para o cliente
- 🔔 **Status Dinâmicos**: Agendado → Confirmado → Concluído
- 📊 **Dashboard**: Visualize estatísticas dos agendamentos
- 🔍 **Busca Avançada**: Encontre agendamentos por cliente ou serviço
- ⏰ **Horários Inteligentes**: Sistema evita conflitos automaticamente
- 🌐 **Responsivo**: Funciona perfeitamente em mobile

## 📋 Iniciando

### 1️⃣ Instalação Rápida (5 minutos)

```bash
# 1. Criar banco de dados
mysql -u root < schema.sql

# 2. Validar instalação (abrir no navegador)
http://seu-site.com/public_html/test-schedules.php

# 3. Acessar o módulo
http://seu-site.com/index.php?action=schedules
```

### 2️⃣ Configuração WhatsApp (Opcional)

```bash
# Copiar arquivo de exemplo
cp public_html/config.whatsapp.example.php public_html/config.whatsapp.php

# Editar com suas credenciais Twilio/Zenvia/Meta
nano public_html/config.whatsapp.php
```

## 📚 Documentação

| Arquivo | Descrição |
|---------|-----------|
| `INTEGRATION_GUIDE.md` | 📖 Guia completo de integração |
| `SCHEDULES_README.md` | 📖 Documentação do módulo |
| `SCHEDULES_SETUP_SUMMARY.md` | 📖 Resumo de features |
| `POST_INSTALLATION.md` | 📖 Próximas etapas |
| `MANIFESTO.md` | 📖 Lista de todos os arquivos |
| `WHATSAPP_USAGE_EXAMPLE.php` | 📖 Exemplos de código |

## 🎯 Recursos Principais

### 📊 Dashboard
```
Agendamentos
├── Total: 15
├── Agendados: 5
├── Confirmados: 7
├── Concluídos: 3
└── Cancelados: 0
```

### 📅 Criar Agendamento
1. Selecionar cliente
2. Descrever serviço
3. Escolher data (filtrada automaticamente)
4. Escolher hora (carregada via AJAX)
5. Cliente recebe WhatsApp 📱

### 📱 Notificações WhatsApp
- ✅ Novo agendamento
- ✅ Atualização de horário
- ✅ Confirmação
- ✅ Cancelamento
- ✅ Lembretes (futura)

## 🔧 Tecnologia

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: Tailwind CSS
- **Integração**: WhatsApp (Twilio, Zenvia, Meta Cloud)

## 🧪 Testes

Executar testes de validação:
```bash
http://seu-site.com/public_html/test-schedules.php
```

Deve mostrar:
- ✓ 10+ testes passando
- ✓ Banco de dados conectado
- ✓ Tabelas criadas
- ✓ Permissões OK

## 🔐 Segurança

✅ Validação completa de entrada  
✅ Prepared statements (sem SQL injection)  
✅ Escaping de saída (sem XSS)  
✅ Autenticação obrigatória  
✅ Sanitização de telefone  

## ⚙️ Horário Comercial

Padrão:
```
Início: 07:00
Fim: 17:00
Dias: Segunda a Sábado
```

Para alterar:
```sql
UPDATE schedule_settings 
SET business_hours_start = '08:00:00',
    business_hours_end = '18:00:00',
    business_days = '1,2,3,4,5'
WHERE id = 1;
```

## 📋 Checklist de Uso

- [ ] Banco de dados criado
- [ ] Teste passando em verde
- [ ] Pelo menos 1 cliente cadastrado
- [ ] Primeiro agendamento criado
- [ ] WhatsApp configurado (opcional)
- [ ] Menu funcionando
- [ ] Status sendo atualizado

## 🚀 Próximos Passos

1. **Validar**: Execute `test-schedules.php`
2. **Configurar**: Edite `config.whatsapp.php`
3. **Usar**: Acesse `index.php?action=schedules`
4. **Monitorar**: Consulte `logs/whatsapp.log`

## 🛠️ Troubleshooting

### Problema: Nenhum agendamento aparece
**Solução**: Você precisa cadastrar clientes primeiro!

### Problema: WhatsApp não funciona
**Solução**: Configure `config.whatsapp.php` com suas credenciais

### Problema: Erro na data/hora
**Solução**: Verifique se está entre 7h-17h e seg-sab

## 📞 Suporte

Consulte os arquivos de documentação:
1. `INTEGRATION_GUIDE.md` - Guia completo
2. `POST_INSTALLATION.md` - Problemas comuns
3. `MANIFESTO.md` - Lista de arquivos

## 🎉 Pronto para Usar!

Seu CRM agora tem um módulo completo de agendamentos de serviços com notificações WhatsApp integradas!

### Acessar Agora
```
http://seu-site.com/index.php?action=schedules
```

---

**Versão**: 1.0  
**Data de Instalação**: Janeiro 2024  
**Status**: ✅ Pronto para Produção

Enjoy! 🚀
