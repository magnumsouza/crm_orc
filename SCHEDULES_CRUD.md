# Módulo de Agendamentos - CRUD Completo

## ✅ Status: Funcionando

O módulo de agendamentos contém um CRUD completo com as seguintes funcionalidades:

## 📋 Funcionalidades

### 1. **CREATE** - Criar Agendamento
- **Rota:** `index.php?action=schedules_create` (GET) / `schedules_store` (POST)
- **Arquivo:** `controllers/SchedulesController.php`
- **Validações:**
  - Cliente obrigatório
  - Descrição do serviço obrigatória
  - Data obrigatória (não pode ser retroativa)
  - Hora obrigatória (7h-17h)
  - Horário comercial (seg-sab)
  - Cliente deve existir no banco
- **Redirecionamento:** Dashboard de agendamentos (`schedules`)
- **WhatsApp:** Notifica cliente quando agendamento é criado

### 2. **READ** - Listar Agendamentos
- **Rota:** `index.php?action=schedules` 
- **Arquivo:** `controllers/SchedulesController.php` → `views/schedules/index.php`
- **Funcionalidades:**
  - Dashboard com 4 cards de estatísticas (Total, Agendados, Confirmados, Concluídos)
  - Tabela responsiva com todos os agendamentos
  - Busca por cliente ou descrição do serviço
  - Botões de ação (Visualizar, Editar, Cancelar)
  - Dropdown para alterar status em tempo real (AJAX)

### 3. **UPDATE** - Editar Agendamento
- **Rota:** `index.php?action=schedules_edit&id=X` (GET) / `schedules_update` (POST)
- **Arquivo:** `controllers/SchedulesController.php`
- **Edições Permitidas:**
  - Cliente
  - Descrição do serviço
  - Data e hora
  - Status (Agendado, Confirmado, Concluído, Cancelado)
  - Observações
- **Validações:** Mesmas do CREATE
- **WhatsApp:** Notifica cliente em caso de mudança de status

### 4. **DELETE** - Cancelar Agendamento
- **Rota:** `schedules_delete&id=X`
- **Arquivo:** `controllers/SchedulesController.php` → `schedule_delete()` no model
- **Funcionamento:** Soft delete (marca como cancelado) ou hard delete (remove do banco)
- **WhatsApp:** Notifica cliente quando agendamento é cancelado

### 5. **VIEW** - Visualizar Detalhes
- **Rota:** `index.php?action=schedules_view&id=X`
- **Arquivo:** `views/schedules/view.php`
- **Informações Exibidas:**
  - Dados do cliente com link WhatsApp direto
  - Detalhes do serviço
  - Data e hora formatadas
  - Status com cores
  - Observações
  - Datas de criação e atualização
  - Botões para editar e retornar

### 6. **API** - Horários Disponíveis
- **Rota:** `index.php?action=schedules_api_slots&date=YYYY-MM-DD`
- **Resposta:** JSON com horários disponíveis para a data
- **Filtragem:**
  - Remove horários já agendados
  - Valida apenas horário comercial
  - Retorna slots de 30 em 30 minutos

## 🗄️ Banco de Dados

### Tabelas Principais

#### `schedules`
```sql
- id: INT PRIMARY KEY
- client_id: INT (FK → clients)
- service_description: TEXT
- scheduled_date: DATE
- scheduled_time: TIME
- status: VARCHAR(20) [Agendado, Confirmado, Concluido, Cancelado]
- notes: TEXT
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
- Índices: client_id, scheduled_date
```

#### `schedule_settings`
```sql
- id: INT PRIMARY KEY
- business_hours_start: TIME (padrão: 07:00:00)
- business_hours_end: TIME (padrão: 17:00:00)
- business_days: VARCHAR(255) (padrão: 1,2,3,4,5,6 = seg-sab)
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

## 📁 Estrutura de Arquivos

```
public_html/
├── controllers/
│   └── SchedulesController.php       # Lógica CRUD
├── models/
│   └── Schedule.php                  # Funções de banco
├── services/
│   └── WhatsAppService.php          # Integração WhatsApp
├── views/schedules/
│   ├── index.php                     # Dashboard/Lista
│   ├── form.php                      # Criar/Editar
│   └── view.php                      # Detalhes
└── schema.sql                         # Tabelas do banco
```

## 🔧 Principais Funções

### Controllers
- `schedules_index()` - Lista todos
- `schedules_create()` - Formulário de criação
- `schedules_store()` - Salva novo agendamento
- `schedules_view()` - Exibe detalhes
- `schedules_edit()` - Formulário de edição
- `schedules_update()` - Atualiza agendamento
- `schedules_delete()` - Remove/cancela agendamento
- `schedules_update_status()` - AJAX para alterar status
- `schedules_api_slots()` - Retorna horários disponíveis

### Models (Schedule.php)
- `schedule_all()` - Retorna todos com busca
- `schedule_find()` - Retorna um por ID
- `schedule_by_date()` - Retorna por data
- `schedule_create()` - Insere novo
- `schedule_update()` - Atualiza
- `schedule_delete()` - Remove
- `schedule_update_status()` - Altera status
- `schedule_counts()` - Estatísticas
- `schedule_is_valid_datetime()` - Valida data/hora
- `schedule_get_available_slots()` - Retorna horários livres
- `schedule_get_settings()` - Configurações

## ⚙️ Validações

### Data
- ✅ Não pode ser anterior a hoje
- ✅ Deve ser segunda a sábado
- ✅ Apenas futuro

### Hora
- ✅ Entre 7h e 17h
- ✅ Não pode ter conflito com outro agendamento
- ✅ Formato HH:MM:SS

### Cliente
- ✅ Obrigatório
- ✅ Deve existir no banco
- ✅ Deve ter telefone para WhatsApp

## 📱 Integração WhatsApp

Notificações automáticas em:
- ✅ Novo agendamento criado
- ✅ Agendamento confirmado
- ✅ Agendamento cancelado
- ✅ Lembrete (configurável)

**Provedores suportados:**
- Twilio
- Zenvia
- Meta Cloud API

**Status:** Pronto para configuração (veja `services/WhatsAppService.php`)

## 🚀 Como Usar

### 1. Criar Agendamento
1. Clique em "Novo Agendamento" no menu
2. Preencha:
   - Cliente (select)
   - Descrição do serviço (textarea)
   - Data (date picker)
   - Hora (time input)
   - Observações (opcional)
3. Clique "Agendar"
4. Será redirecionado para o dashboard

### 2. Listar Agendamentos
- Acesse `index.php?action=schedules`
- Veja dashboard com estatísticas
- Use busca para filtrar
- Clique em "Visualizar" para detalhes

### 3. Editar Agendamento
- No dashboard, clique "Editar"
- Modifique os campos necessários
- Clique "Atualizar"
- Cliente será notificado se status mudar

### 4. Cancelar Agendamento
- No dashboard, clique "Cancelar"
- Confirme a ação
- Cliente será notificado

## ✨ Funcionalidades Extras

- **Responsivo:** Funciona em mobile e desktop
- **Cores por Status:** Visual indicador de status
- **AJAX:** Dropdown de status em tempo real
- **Timestamps:** Criado em / Atualizado em automático
- **Soft Delete:** Mantém histórico
- **Search:** Busca por cliente ou descrição
- **Paginação:** Pronta para implementação

## 🔒 Segurança

- ✅ Prepared Statements (SQL Injection)
- ✅ Input Sanitization (XSS)
- ✅ CSRF Token (se implementado)
- ✅ Autenticação obrigatória
- ✅ Validação no servidor

## 📊 Dashboard Estatísticas

Exibe:
- **Total de Agendamentos**
- **Agendados** (status = "Agendado")
- **Confirmados** (status = "Confirmado")
- **Concluídos** (status = "Concluido")

## 🎯 Próximas Melhorias (Opcional)

- [ ] Paginação com limit/offset
- [ ] Filtro por data range
- [ ] Exportar para PDF/CSV
- [ ] Calendário visual
- [ ] Sincronização com Google Calendar
- [ ] Lembretes automáticos 24h antes
- [ ] Relatórios de agendamentos
- [ ] Multi-idioma

---

**Última atualização:** 21/01/2026
**Status:** ✅ Produção
