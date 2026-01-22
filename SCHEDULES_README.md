# Módulo de Agendamentos - Documentação

## Visão Geral

O módulo de agendamentos permite gerenciar os agendamentos de serviços dos clientes com:

- ✅ Horário comercial configurável (padrão: 7h-17h)
- ✅ Dias úteis configuráveis (padrão: segunda a sábado)
- ✅ Validação automática de conflitos de horários
- ✅ Integração com WhatsApp para notificações
- ✅ Diferentes status de agendamentos
- ✅ Histórico e rastreamento completo

## Instalação

### 1. Banco de Dados

Execute o arquivo `schema.sql` para criar as tabelas necessárias:

```bash
mysql -u root < schema.sql
```

Tabelas criadas:
- `schedules` - Armazena os agendamentos
- `schedule_settings` - Configurações de horário comercial

### 2. Estrutura de Arquivos

```
public_html/
├── controllers/
│   └── SchedulesController.php      # Controlador de agendamentos
├── models/
│   └── Schedule.php                 # Modelo de dados
├── services/
│   └── WhatsAppService.php          # Serviço de integração WhatsApp
└── views/
    └── schedules/
        ├── index.php               # Listagem de agendamentos
        ├── form.php                # Criar/editar agendamento
        └── view.php                # Visualizar agendamento
```

## Uso

### Acessar o Módulo

A partir do dashboard, clique em "Agendamentos" no menu lateral ou acesse:

```
http://seu-site.com/index.php?action=schedules
```

### Criar Novo Agendamento

1. Clique em "Novo Agendamento"
2. Selecione o cliente (deve estar cadastrado em Clientes)
3. Descreva o serviço
4. Selecione a data (o sistema carrega apenas datas válidas)
5. Selecione o horário (carregado dinamicamente baseado na data selecionada)
6. Adicione observações se necessário
7. Clique em "Agendar"

O cliente receberá uma notificação no WhatsApp automaticamente.

### Atualizar Status

No painel de agendamentos, você pode alterar o status diretamente do dropdown:

- **Agendado** - Estado inicial
- **Confirmado** - Cliente confirmou o agendamento
- **Concluído** - Serviço realizado
- **Cancelado** - Agendamento cancelado

Ao atualizar o status, uma notificação é enviada ao cliente via WhatsApp.

## Integração com WhatsApp

### Configuração Básica

O serviço WhatsApp está preparado para múltiplos provedores. Por enquanto, as mensagens são registradas em log.

#### Opção 1: Twilio

1. Crie uma conta em [twilio.com](https://www.twilio.com)
2. Obtenha suas credenciais
3. Instale o SDK: `composer require twilio/sdk`
4. Configure no seu arquivo `config.php` ou crie um novo arquivo de configuração:

```php
<?php
require_once __DIR__ . '/services/WhatsAppService.php';

WhatsAppService::configure([
    'provider' => 'twilio',
    'account_sid' => 'seu_account_sid',
    'auth_token' => 'seu_auth_token',
    'phone_number' => '+5511999999999', // Seu número Twilio
]);
```

#### Opção 2: Zenvia

1. Crie uma conta em [zenvia.com](https://www.zenvia.com)
2. Configure no seu código:

```php
WhatsAppService::configure([
    'provider' => 'zenvia',
    'zenvia_api_key' => 'sua_api_key',
    'phone_number' => 'seu_numero',
]);
```

#### Opção 3: Meta WhatsApp Cloud API

1. Crie uma conta Facebook Business
2. Configure no seu código:

```php
WhatsAppService::configure([
    'provider' => 'whatsapp_cloud',
    'wa_phone_id' => 'seu_phone_id',
    'wa_access_token' => 'seu_access_token',
]);
```

### Métodos Disponíveis

```php
// Notificar novo agendamento
WhatsAppService::notifyNewSchedule($client, $schedule);

// Notificar atualização
WhatsAppService::notifyScheduleUpdate($client, $schedule);

// Notificar confirmação
WhatsAppService::notifyScheduleConfirmed($client, $schedule);

// Notificar cancelamento
WhatsAppService::notifyScheduleCancelled($client, $schedule);

// Enviar lembrete
WhatsAppService::sendReminder($client, $schedule);

// Enviar mensagem customizada
WhatsAppService::send($phone, $message);
```

## Configurar Horário Comercial

Acesse o banco de dados e edite a tabela `schedule_settings`:

```sql
UPDATE schedule_settings 
SET business_hours_start = '08:00:00',
    business_hours_end = '18:00:00',
    business_days = '1,2,3,4,5,6,7'
WHERE id = 1;
```

### Dias da Semana (business_days)

- `1` = Segunda-feira
- `2` = Terça-feira
- `3` = Quarta-feira
- `4` = Quinta-feira
- `5` = Sexta-feira
- `6` = Sábado
- `7` = Domingo

Exemplo para segunda a sexta:
```sql
business_days = '1,2,3,4,5'
```

## Validações

O sistema valida automaticamente:

- ✅ Cliente selecionado existe
- ✅ Data/hora é no futuro
- ✅ Data/hora está dentro do horário comercial
- ✅ Descrição do serviço não está vazia
- ✅ Não há conflitos de horários

## API de Horários Disponíveis

Para obter os horários disponíveis de um dia:

```javascript
fetch('index.php?action=schedules_api_slots&date=2024-01-20')
    .then(response => response.json())
    .then(data => {
        console.log(data.slots); // Array de horários disponíveis
    });
```

## Rotas Disponíveis

| Action | Descrição |
|--------|-----------|
| `schedules` | Listar agendamentos |
| `schedules_create` | Formulário novo agendamento |
| `schedules_store` | Salvar novo agendamento |
| `schedules_view` | Visualizar detalhes |
| `schedules_edit` | Formulário editar |
| `schedules_update` | Salvar alterações |
| `schedules_delete` | Deletar agendamento |
| `schedules_update_status` | Atualizar status (AJAX) |
| `schedules_api_slots` | API de horários (JSON) |

## Recursos Futuros

- [ ] Sincronização com Google Calendar
- [ ] Notificações por SMS
- [ ] Agendamento de lembretes automáticos
- [ ] Relatórios de agendamentos
- [ ] Integração com formulários de agendamento no site
- [ ] Agendamento em tempo real para clientes
- [ ] Múltiplos calendários por departamento

## Resolução de Problemas

### Mensagens não estão sendo enviadas

1. Verifique se o telefone do cliente está correto
2. Verifique se o WhatsApp está configurado em `config.php`
3. Verifique o arquivo de log em `logs/whatsapp.log`

### Horários não aparecem no formulário

1. Verifique se a data selecionada é válida (futuro, dia útil)
2. Verifique as configurações de `schedule_settings` no banco
3. Verifique se o horário não conflita com agendamentos existentes

### Erro de validação de data/hora

- Certifique-se de que a data está em formato YYYY-MM-DD
- A hora deve estar em formato HH:MM:SS
- Ambos devem estar dentro do horário comercial configurado

## Suporte

Para dúvidas ou sugestões, contacte o administrador do sistema.
