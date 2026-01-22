# 🚀 Guia de Integração - Módulo de Agendamentos

## ⚡ Início Rápido (5 minutos)

### Passo 1: Executar Migração do Banco de Dados
```bash
# Opção 1: Via linha de comando
mysql -u root < schema.sql

# Opção 2: Via navegador
http://seu-site.com/public_html/migrate.php
```

### Passo 2: Testar Instalação
```
http://seu-site.com/public_html/test-schedules.php
```

### Passo 3: Acessar Módulo
```
http://seu-site.com/index.php?action=schedules
```

## 📋 Checklist de Instalação

- [ ] Executar `schema.sql`
- [ ] Verificar que tabelas foram criadas
- [ ] Executar script de teste
- [ ] Cadastrar pelo menos 1 cliente
- [ ] Criar primeiro agendamento
- [ ] (Opcional) Configurar WhatsApp

## 🔧 Configuração de Horário Comercial

### Via SQL
```sql
UPDATE schedule_settings 
SET business_hours_start = '08:00:00',
    business_hours_end = '18:00:00',
    business_days = '1,2,3,4,5'
WHERE id = 1;
```

### Valores Padrão
- **Início**: 07:00:00 (7h da manhã)
- **Fim**: 17:00:00 (5h da tarde)
- **Dias**: 1,2,3,4,5,6 (segunda a sábado)

### Formato de Dias
```
1 = Segunda-feira
2 = Terça-feira
3 = Quarta-feira
4 = Quinta-feira
5 = Sexta-feira
6 = Sábado
7 = Domingo
```

## 📱 Configuração WhatsApp

### Opção 1: Modo Teste (Padrão)
Sem configuração, as mensagens são registradas em `/public_html/logs/whatsapp.log`

### Opção 2: Twilio (Recomendado)

#### a) Criar conta Twilio
1. Acesse [twilio.com](https://www.twilio.com)
2. Crie uma conta
3. Configure seu número WhatsApp

#### b) Instalar SDK
```bash
composer require twilio/sdk
```

#### c) Configurar credenciais
Crie arquivo `public_html/config.whatsapp.php`:

```php
<?php
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'twilio',
    'account_sid' => 'AC...', // Seu Account SID
    'auth_token' => '...', // Seu Auth Token
    'phone_number' => '+5511999999999', // Seu número Twilio
]);
```

### Opção 3: Zenvia

```php
<?php
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'zenvia',
    'zenvia_api_key' => 'sua_api_key',
    'phone_number' => 'seu_numero',
]);
```

### Opção 4: Meta WhatsApp Cloud API

```php
<?php
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'whatsapp_cloud',
    'wa_phone_id' => 'seu_phone_id',
    'wa_access_token' => 'seu_access_token',
]);
```

## 🎯 Fluxo de Uso

### Criar Agendamento
```
1. Login no sistema
2. Clique em "Agendamentos" no menu
3. Clique em "Novo Agendamento"
4. Preencha o formulário:
   - Selecione cliente
   - Descreva o serviço
   - Escolha data (sistema filtra apenas datas válidas)
   - Escolha hora (carregada dinamicamente)
5. Adicione observações (opcional)
6. Clique em "Agendar"
7. Cliente recebe notificação WhatsApp 📱
```

### Atualizar Status
```
1. Acesse listagem de agendamentos
2. No dropdown de status, selecione novo status
3. Cliente recebe notificação automática via WhatsApp
```

### Editar Agendamento
```
1. Clique em "Editar" na listagem
2. Altere os dados desejados
3. Clique em "Atualizar"
4. Cliente é notificado se houver mudança de status
```

### Cancelar Agendamento
```
1. Clique em "Cancelar" na listagem
2. Confirme a ação
3. Agendamento é deletado
4. (Você pode querer alterar para status "Cancelado" antes)
```

## 📊 Dashboard

O painel de agendamentos exibe:
- Total de agendamentos
- Agendamentos por status (com cores)
- Busca por cliente ou serviço
- Links rápidos para ações

### Cores de Status
- 🔵 **Azul** = Agendado
- 🟢 **Verde** = Confirmado
- 🟢 **Esmeralda** = Concluído
- 🔴 **Vermelho** = Cancelado

## 🛠️ Troubleshooting

### Problema: "Nenhum horário disponível"
**Solução:**
- Verifique a data (deve ser no futuro)
- Verifique se é dia útil (seg-sab por padrão)
- Verifique se há horários disponíveis após as 7h
- Verifique `schedule_settings` no banco

### Problema: WhatsApp não envia mensagens
**Solução:**
1. Verifique se o telefone do cliente está correto
2. Verifique se WhatsApp está configurado
3. Verifique arquivo de log: `logs/whatsapp.log`
4. Teste com `test-schedules.php`

### Problema: Erro ao criar agendamento
**Solução:**
1. Verifique se cliente existe
2. Verifique se preencheu todos os campos
3. Verifique se data/hora estão no formato correto
4. Verifique permissões do banco de dados

### Problema: Tabelas não foram criadas
**Solução:**
1. Verifique se executou `schema.sql`
2. Tente acessar `migrate.php` no navegador
3. Verifique permissões do MySQL
4. Verifique se banco de dados `crm_orcamentos` foi criado

## 📝 Arquivo de Log

Os logs de WhatsApp são salvos em:
```
/public_html/logs/whatsapp.log
```

Formato:
```
[2024-01-20 10:30:45] Para: 5511999999999 | Mensagem: Seu texto aqui
[2024-01-20 10:35:20] Para: 5511988888888 | Mensagem: Outro texto
```

## 🔐 Segurança

### Práticas Recomendadas
1. **Senhas WhatsApp**: Nunca commite credenciais no Git
2. **HTTPS**: Use sempre HTTPS em produção
3. **Backup**: Faça backup regular do banco de dados
4. **Permissões**: Restrinja acesso ao `public_html/migrate.php`
5. **Logs**: Revise logs regularmente

### Remover Scripts de Debug
Em produção, delete:
- `public_html/test-schedules.php`
- `public_html/migrate.php`
- `public_html/config.whatsapp.example.php`

## 📚 Estrutura de Dados

### Tabela: schedules
```sql
id INT - ID do agendamento
client_id INT - ID do cliente
service_description TEXT - Descrição do serviço
scheduled_date DATE - Data (YYYY-MM-DD)
scheduled_time TIME - Hora (HH:MM:SS)
status VARCHAR - Agendado|Confirmado|Concluido|Cancelado
notes TEXT - Observações
created_at TIMESTAMP - Criado em
updated_at TIMESTAMP - Atualizado em
```

### Tabela: schedule_settings
```sql
id INT - ID (sempre 1)
business_hours_start TIME - Início (07:00:00)
business_hours_end TIME - Fim (17:00:00)
business_days VARCHAR - Dias (1,2,3,4,5,6)
```

## 🚀 Próximas Melhorias

- [ ] Sincronização com Google Calendar
- [ ] Notificações por email
- [ ] Agendamento automático de lembretes (24h antes)
- [ ] Relatórios e estatísticas
- [ ] Importação/exportação de agendamentos
- [ ] Múltiplos calendários por departamento
- [ ] Integração com videoconferência (Zoom, Teams)
- [ ] Formulário de agendamento no site público

## 📞 Suporte

Para dúvidas ou problemas:
1. Consulte `SCHEDULES_README.md`
2. Revise logs em `/logs/whatsapp.log`
3. Execute `test-schedules.php` para validar
4. Verifique permissões de banco e arquivos

---

**Versão**: 1.0  
**Data**: Janeiro 2024  
**Status**: Pronto para Produção ✅
