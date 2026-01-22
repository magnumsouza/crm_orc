# 🚀 Como Testar o Módulo de Agendamentos

## ✅ Verificação Rápida

### 1. **Debug Dashboard**
Acesse: http://localhost/crm-orcamentos/public_html/debug-agendamentos.php

Isso mostra:
- ✓ Conexão com banco de dados
- ✓ Todas as tabelas
- ✓ Clientes disponíveis
- ✓ Configurações de agendamento
- ✓ Agendamentos atuais
- ✓ Estatísticas
- ✓ Botão para criar agendamento de teste
- ✓ Formulário manual de criação

### 2. **Ver Log de Erros**
Acesse: http://localhost/crm-orcamentos/public_html/view-log.php

Mostra tudo que aconteceu em cada tentativa de criar agendamento.

### 3. **Dashboard Principal**
Acesse: http://localhost/crm-orcamentos/public_html/index.php?action=schedules

Veja todos os agendamentos criados.

### 4. **Novo Agendamento**
Acesse: http://localhost/crm-orcamentos/public_html/index.php?action=schedules_create

Crie um novo agendamento manualmente.

## 🧪 Testes Recomendados

### Teste 1: Criar Automático
1. Abra http://localhost/crm-orcamentos/public_html/debug-agendamentos.php
2. Role até a seção "7️⃣ Teste de Criação Automática"
3. Clique em "Criar Agendamento de Teste"
4. Verifique se apareceu na lista e se criou um registro no banco

### Teste 2: Criar via Formulário Manual
1. Abra http://localhost/crm-orcamentos/public_html/debug-agendamentos.php
2. Role até a seção "8️⃣ Formulário Manual"
3. Preencha os campos:
   - Cliente: Selecione um cliente
   - Descrição: Digite uma descrição
   - Data: Escolha uma data futura
   - Hora: Escolha uma hora entre 7h-17h
4. Clique "Criar via Formulário"
5. Verifique o resultado

### Teste 3: Criar via Formulário Principal
1. Abra http://localhost/crm-orcamentos/public_html/index.php?action=schedules_create
2. Preencha o formulário completo
3. Clique "Agendar"
4. Se tiver erro, verifique http://localhost/crm-orcamentos/public_html/view-log.php

### Teste 4: Listar e Visualizar
1. Abra http://localhost/crm-orcamentos/public_html/index.php?action=schedules
2. Veja o dashboard com todos os agendamentos
3. Clique em "Visualizar" para ver detalhes

### Teste 5: Editar
1. No dashboard, clique "Editar" em um agendamento
2. Modifique os dados
3. Clique "Atualizar"
4. Volte ao dashboard e confirme as mudanças

### Teste 6: Cancelar
1. No dashboard, clique "Cancelar"
2. Confirme a ação
3. Veja se o status mudou para "Cancelado"

## 🔍 Se Não Funcionar

### Passo 1: Verifique o Log
```
http://localhost/crm-orcamentos/public_html/view-log.php
```
Procure por "ERRO" para entender o problema.

### Passo 2: Verifique o Banco de Dados
```bash
C:\xampp\mysql\bin\mysql -u root crm_orcamentos
SELECT COUNT(*) as total FROM schedules;
```

### Passo 3: Verifique a Conexão
Abra http://localhost/crm-orcamentos/public_html/debug-agendamentos.php
Procure por "Conexão com Banco de Dados" - deve estar verde (✓)

### Passo 4: Verifique os Clientes
No debug, procure por "Clientes Disponíveis"
Se disser "Nenhum cliente encontrado!", crie um cliente primeiro em:
http://localhost/crm-orcamentos/public_html/index.php?action=clients_create

## 📊 Status Esperado

Se tudo estiver funcionando:
- ✅ Você consegue criar agendamentos
- ✅ Aparecem no dashboard
- ✅ Aparecem no banco de dados
- ✅ Podem ser editados
- ✅ Podem ser cancelados
- ✅ Log está vazio (sem erros)

## 🆘 Problemas Comuns

### "Nenhum cliente encontrado"
- Crie um cliente em: http://localhost/crm-orcamentos/public_html/index.php?action=clients

### "Data/hora inválida"
- A data deve ser a partir de hoje
- A hora deve estar entre 7h e 17h
- O dia deve ser seg-sab (não dom)

### "Cliente não encontrado"
- O cliente foi deletado
- Use um cliente que exista no banco

### "Erro ao salvar"
- Verifique o log em: http://localhost/crm-orcamentos/public_html/view-log.php
- Procure por "ERRO" para detalhes

## 📝 Comandos SQL Úteis

```sql
-- Ver todos os agendamentos
SELECT * FROM schedules ORDER BY id DESC;

-- Contar agendamentos
SELECT COUNT(*) FROM schedules;

-- Ver agendamentos por status
SELECT status, COUNT(*) FROM schedules GROUP BY status;

-- Ver agendamentos de um cliente
SELECT * FROM schedules WHERE client_id = 1;

-- Deletar todos os agendamentos (cuidado!)
TRUNCATE TABLE schedules;

-- Ver configurações
SELECT * FROM schedule_settings;
```

---

**Última atualização:** 21/01/2026
