# 🎉 Pós-Instalação - Módulo de Agendamentos

## ✅ Instalação Concluída!

Parabéns! O módulo de agendamentos foi instalado com sucesso.

## 📋 Próximas Etapas

### 1. Validar Instalação ✓
```
http://seu-site.com/public_html/test-schedules.php
```
Verifique se todos os testes passaram em verde.

### 2. Acessar o Módulo ✓
```
http://seu-site.com/index.php?action=schedules
```
Você deve ver o painel de agendamentos vazio.

### 3. Cadastrar Clientes ✓
Antes de criar agendamentos, você precisa ter clientes:
- Vá para "Clientes" no menu
- Cadastre pelo menos um cliente
- **Importante**: O cliente deve ter um telefone válido (com WhatsApp)

### 4. Criar Primeiro Agendamento ✓
- Clique em "Novo Agendamento"
- Selecione um cliente
- Descreva o serviço
- Escolha data e hora (válidos para horário comercial)
- Clique em "Agendar"
- O cliente receberá uma notificação (verifique `logs/whatsapp.log`)

### 5. Configurar WhatsApp (Opcional) ✓
Se quiser enviar mensagens de verdade:

```php
// Criar arquivo: public_html/config.whatsapp.php

<?php
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'twilio', // ou 'zenvia', 'whatsapp_cloud'
    'account_sid' => 'seu_account_sid',
    'auth_token' => 'seu_auth_token',
    'phone_number' => '+5511999999999',
]);
```

## 🎯 Verificação de Funcionalidades

### Dashboard
- [ ] Contadores aparecem corretamente
- [ ] Cards mostram números atualizados
- [ ] Cores correspondem aos status

### Listagem
- [ ] Agendamentos aparecem em ordem (mais recentes primeiro)
- [ ] Busca funciona por cliente
- [ ] Dropdown de status funciona
- [ ] Botões de ação aparecem

### Criar Agendamento
- [ ] Formulário carrega todos os clientes
- [ ] Data picker funciona
- [ ] Horários carregam dinamicamente
- [ ] Validação bloqueia datas passadas
- [ ] Validação bloqueia fora do horário comercial
- [ ] Mensagem de sucesso aparece

### Status
- [ ] Alterar status via dropdown funciona
- [ ] Cliente é notificado via WhatsApp
- [ ] Página atualiza sem reload

### Visualizar
- [ ] Detalhes aparecem formatados
- [ ] Link do WhatsApp funciona
- [ ] Datas aparecem em PT-BR

## 🔧 Configurações Recomendadas

### Horário Comercial
Se quiser ajustar os horários padrão (7h-17h, seg-sab):

```sql
-- Conectar ao MySQL
mysql -u root crm_orcamentos

-- Alterar horários (exemplo: 8h-18h, seg-sex)
UPDATE schedule_settings 
SET business_hours_start = '08:00:00',
    business_hours_end = '18:00:00',
    business_days = '1,2,3,4,5'
WHERE id = 1;
```

### Período de Agendamento
Para permitir agendamentos com antecedência:
- Edite `models/Schedule.php`
- Função `schedule_is_valid_datetime()`
- Adicione validação de dias mínimos de antecedência

### Intervalo de Horários
O padrão é 30 minutos. Para alterar:
- Edite `models/Schedule.php`
- Função `schedule_get_available_slots()`
- Mude `new DateInterval('PT30M')` para `PT15M` ou `PT60M`

## 📱 WhatsApp - Próximas Etapas

### Se usar Twilio:
1. Acesse [twilio.com](https://www.twilio.com)
2. Crie conta
3. Compre número WhatsApp
4. Obtenha `Account SID` e `Auth Token`
5. Configure em `config.whatsapp.php`
6. Instale: `composer require twilio/sdk`

### Se usar Zenvia:
1. Acesse [zenvia.com](https://www.zenvia.com)
2. Crie conta
3. Obtenha API Key
4. Configure em `config.whatsapp.php`

### Se usar Meta WhatsApp Cloud:
1. Configure Business Account no Facebook
2. Crie aplicação e obtenha credenciais
3. Configure em `config.whatsapp.php`

## 🧹 Limpeza Antes de Produção

### Remova os arquivos de teste:
```bash
rm public_html/test-schedules.php
rm public_html/migrate.php
rm public_html/config.whatsapp.example.php
rm WHATSAPP_USAGE_EXAMPLE.php
```

### Crie arquivo seguro de configuração:
```bash
cp public_html/config.whatsapp.example.php public_html/config.whatsapp.php
# Edite com suas credenciais reais
# NÃO faça commit no Git
```

### Atualize .gitignore:
```
public_html/config.whatsapp.php
public_html/logs/
```

## 📊 Monitoramento

### Verificar Logs
```bash
tail -f public_html/logs/whatsapp.log
```

### Backup do Banco
```bash
mysqldump -u root crm_orcamentos > backup_$(date +%Y%m%d).sql
```

### Agendamentos Hoje
```sql
SELECT * FROM schedules 
WHERE DATE(scheduled_date) = CURDATE()
ORDER BY scheduled_time;
```

## 🆘 Problemas Comuns

### "Nenhum cliente aparece no dropdown"
- Certifique-se de ter cadastrado clientes em "Clientes"
- Clientes devem ter telefone válido

### "Mensagem de 'data/hora inválida'"
- A data deve ser no futuro
- Deve ser um dia útil (seg-sab por padrão)
- Deve estar entre 7h-17h por padrão
- Formato deve ser YYYY-MM-DD HH:MM:SS

### "WhatsApp não envia mensagens"
- Verifique se configurou `config.whatsapp.php`
- Verifique se provider está correto
- Verifique telefone do cliente (deve ser válido)
- Consulte `logs/whatsapp.log`
- Verifique credenciais no painel do provider

### "Erro ao criar agendamento"
- Preencha todos os campos obrigatórios
- Cliente selecionado deve existir
- Data/hora devem ser válidos
- Verifique permissões do banco de dados

### "Permissão negada em /logs"
```bash
chmod 755 public_html/logs
chmod 666 public_html/logs/whatsapp.log
```

## 📚 Documentação

Consulte os arquivos para mais detalhes:
- `SCHEDULES_README.md` - Documentação completa
- `INTEGRATION_GUIDE.md` - Guia de integração
- `SCHEDULES_SETUP_SUMMARY.md` - Resumo de features

## 🚀 Recursos Futuros

Para melhorias futuras:
- [ ] Sincronizar com Google Calendar
- [ ] Alertas por email
- [ ] Lembretes automáticos (24h antes)
- [ ] Relatórios de agendamentos
- [ ] Portal do cliente para autoagendamento
- [ ] Integração com Zoom/Teams
- [ ] SMS como alternativa ao WhatsApp
- [ ] Múltiplos calendários

## ✨ Dicas de Uso

### Agendar Para Amanhã
- Use a data de amanhã
- Escolha hora dentro do comercial
- Cliente receberá notificação imediatamente

### Atualizar Horário
- Use "Editar"
- Mude a data/hora
- Salve - cliente será notificado

### Cancelar Agendamento
- Clique em "Cancelar"
- OU mude status para "Cancelado"
- Cliente receberá notificação

### Buscar Agendamentos
- Use o campo de busca
- Busca por nome do cliente OU descrição do serviço
- Digite parte do nome/serviço

## 📞 Suporte

Se tiver dúvidas:
1. Consulte a documentação incluída
2. Verifique o arquivo de log
3. Execute `test-schedules.php`
4. Verifique banco de dados

## 🎉 Parabéns!

Você está pronto para gerenciar agendamentos de serviços com:
- ✅ Horário comercial controlado
- ✅ Validações automáticas
- ✅ Notificações WhatsApp
- ✅ Dashboard intuitivo
- ✅ Sistema confiável

---

**Versão**: 1.0  
**Data**: Janeiro 2024  
**Status**: Pronto para Uso 🎊
