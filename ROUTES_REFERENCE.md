# 🔗 Rotas e URLs - Módulo de Agendamentos

## 📍 URLs Base do Sistema

```
http://seu-site.com/index.php?action=AÇÃO
ou
http://seu-site.com/public_html/index.php?action=AÇÃO
```

---

## 📋 Rotas do Módulo de Agendamentos

### Listagem
```
GET | POST: http://seu-site.com/index.php?action=schedules
Função: schedules_index()
Descrição: Mostra painel com todos os agendamentos
Exibe: Dashboard com estatísticas + tabela de agendamentos
```

### Novo Agendamento (Formulário)
```
GET: http://seu-site.com/index.php?action=schedules_create
Função: schedules_create()
Descrição: Mostra formulário para criar novo agendamento
Exige: Estar logado
```

### Novo Agendamento (Salvar)
```
POST: http://seu-site.com/index.php?action=schedules_store
Função: schedules_store()
Descrição: Salva novo agendamento no banco
Parâmetros POST:
  - client_id: ID do cliente
  - service_description: Descrição do serviço
  - scheduled_date: Data (YYYY-MM-DD)
  - scheduled_time: Hora (HH:MM:SS)
  - notes: Observações (opcional)
Validações: Automáticas
Notificação: WhatsApp enviado
Retorno: Redireciona para listagem
```

### Visualizar Agendamento
```
GET: http://seu-site.com/index.php?action=schedules_view&id=123
Função: schedules_view()
Descrição: Mostra detalhes completos do agendamento
Parâmetros GET:
  - id: ID do agendamento (obrigatório)
Exibe: Informações completo + dados do cliente + histórico
```

### Editar Agendamento (Formulário)
```
GET: http://seu-site.com/index.php?action=schedules_edit&id=123
Função: schedules_edit()
Descrição: Mostra formulário para editar agendamento
Parâmetros GET:
  - id: ID do agendamento (obrigatório)
Pré-carrega: Dados atuais do agendamento
```

### Editar Agendamento (Salvar)
```
POST: http://seu-site.com/index.php?action=schedules_update
Função: schedules_update()
Descrição: Salva alterações do agendamento
Parâmetros POST:
  - id: ID do agendamento
  - client_id: ID do cliente
  - service_description: Descrição do serviço
  - scheduled_date: Data (YYYY-MM-DD)
  - scheduled_time: Hora (HH:MM:SS)
  - status: Agendado|Confirmado|Concluido|Cancelado
  - notes: Observações (opcional)
Validações: Automáticas
Notificação: WhatsApp se status mudou
Retorno: Redireciona para listagem
```

### Deletar Agendamento
```
GET: http://seu-site.com/index.php?action=schedules_delete&id=123
Função: schedules_delete()
Descrição: Deleta o agendamento
Parâmetros GET:
  - id: ID do agendamento (obrigatório)
Confirmação: JavaScript confirm() antes de deletar
Retorno: Redireciona para listagem
Aviso: Ação irreversível
```

### Atualizar Status (AJAX)
```
POST: http://seu-site.com/index.php?action=schedules_update_status
Função: schedules_update_status()
Descrição: Atualiza status via AJAX (sem reload)
Content-Type: application/x-www-form-urlencoded
Parâmetros POST:
  - id: ID do agendamento
  - status: Novo status
Retorno: JSON { success: true/false, message: "..." }
Notificação: WhatsApp enviado automaticamente
Uso: Dropdown na tabela de listagem
```

### API de Horários (JSON)
```
GET: http://seu-site.com/index.php?action=schedules_api_slots&date=2024-01-22
Função: schedules_api_slots()
Descrição: Retorna horários disponíveis para uma data
Content-Type: application/json
Parâmetros GET:
  - date: Data desejada (YYYY-MM-DD)
Resposta Success:
{
  "success": true,
  "slots": ["07:00", "07:30", "08:00", ..., "16:30"]
}
Resposta Error:
{
  "success": false,
  "message": "Data não fornecida"
}
Uso: Carregamento dinâmico no formulário
Sem reload: Não redireciona, apenas retorna JSON
```

---

## 📊 Fluxo de Rotas

### Criar Agendamento
```
1. schedules_create
   ↓ (usuário preenche formulário)
2. schedules_store
   ↓ (valida dados)
3. schedules
   (redireciona com mensagem de sucesso)
```

### Editar Agendamento
```
1. schedules_edit?id=123
   ↓ (usuário altera dados)
2. schedules_update
   ↓ (valida e salva)
3. schedules
   (redireciona com mensagem de sucesso)
```

### Alterar Status
```
1. (dropdown na tabela)
   ↓ (AJAX POST)
2. schedules_update_status
   ↓ (valida e salva)
3. (JSON response)
   (atualiza tabela sem reload)
```

### Obter Horários
```
1. (selecionando data no formulário)
   ↓ (AJAX GET)
2. schedules_api_slots
   ↓ (busca horários disponíveis)
3. (JSON response)
   (preenche dropdown de horários)
```

---

## 🔐 Autenticação

Todas as rotas exigem autenticação:
```php
require_login(); // Função verificada em index.php
```

Se não autenticado → Redireciona para login

---

## ✅ Validações por Rota

### schedules_store
- Client ID existe
- Service description não vazio
- Date/time preenchidos
- Date/time válido (futuro + comercial)

### schedules_update
- ID do agendamento existe
- Todos os campos obrigatórios preenchidos
- Date/time válido (futuro + comercial)

### schedules_delete
- ID do agendamento existe
- Confirmação JavaScript

### schedules_update_status
- ID do agendamento existe
- Status válido (enum)

### schedules_api_slots
- Data fornecida
- Data formatada corretamente

---

## 📱 Respostas de Status HTTP

### Sucesso (200)
- Operação realizada
- Redireciona com mensagem

### Sucesso JSON (200)
```json
{ "success": true, "message": "Status atualizado e cliente notificado" }
```

### Erro JSON (200)
```json
{ "success": false, "message": "Agendamento não encontrado" }
```

### Erro Validação (302 Redirect)
- Redireciona com mensagem de erro
- Usa sistema de flash messages

---

## 🧪 Testes de Rotas

### Listar
```bash
curl "http://seu-site.com/index.php?action=schedules"
```

### API Horários
```bash
curl "http://seu-site.com/index.php?action=schedules_api_slots&date=2024-01-22"
```

### Atualizar Status
```bash
curl -X POST "http://seu-site.com/index.php" \
  -d "action=schedules_update_status&id=1&status=Confirmado"
```

---

## 🔄 Relacionamentos

```
clients (existe)
    ↓
schedules (referencia client_id)
    ↓
schedule_settings (configurações globais)
```

---

## 📝 Parâmetros Validados

### client_id
- Type: Integer
- Obrigatório: Sim
- Validação: Deve existir em clients

### service_description
- Type: String (Text)
- Obrigatório: Sim
- Comprimento: Mínimo 1 caractere

### scheduled_date
- Type: Date (YYYY-MM-DD)
- Obrigatório: Sim
- Validação: Deve ser futuro, dia útil, comercial

### scheduled_time
- Type: Time (HH:MM:SS)
- Obrigatório: Sim
- Validação: Dentro de 7h-17h (configurável)

### status
- Type: Enum
- Valores: Agendado | Confirmado | Concluido | Cancelado
- Padrão: Agendado

### notes
- Type: String (Text)
- Obrigatório: Não
- Comprimento: Ilimitado

### business_days
- Type: String (Comma separated)
- Exemplo: "1,2,3,4,5,6"
- Significado: 1=seg, 2=ter, ..., 7=dom

---

## 🚀 Resumo Rápido

| Ação | Método | Rota | Retorno |
|------|--------|------|---------|
| Listar | GET | schedules | HTML |
| Criar | GET | schedules_create | HTML |
| Salvar | POST | schedules_store | Redirect |
| Ver | GET | schedules_view | HTML |
| Editar | GET | schedules_edit | HTML |
| Atualizar | POST | schedules_update | Redirect |
| Deletar | GET | schedules_delete | Redirect |
| Status | POST | schedules_update_status | JSON |
| Horários | GET | schedules_api_slots | JSON |

---

**Data**: Janeiro 2024  
**Versão**: 1.0  
**Última Atualização**: [data de hoje]
