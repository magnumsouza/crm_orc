#!/bin/bash

# Script de Instalação do Módulo de Agendamentos
# Execute este script para instalar o módulo no seu banco de dados

echo "═══════════════════════════════════════════════════════════════"
echo "Instalação do Módulo de Agendamentos"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Verificar se o arquivo de schema existe
if [ ! -f "schema.sql" ]; then
    echo "❌ Erro: Arquivo schema.sql não encontrado!"
    echo "   Certifique-se de executar este script na raiz do projeto"
    exit 1
fi

echo "📦 Criando tabelas do módulo de agendamentos..."
echo ""

# Cores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Tentar criar as tabelas
if mysql -u root -e "USE crm_orcamentos; SHOW TABLES;" 2>/dev/null | grep -q "schedules"; then
    echo -e "${GREEN}✓${NC} Tabelas de agendamentos já existem"
else
    mysql -u root < schema.sql
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} Tabelas criadas com sucesso"
    else
        echo -e "${RED}✗${NC} Erro ao criar tabelas"
        echo "   Execute manualmente: mysql -u root < schema.sql"
        exit 1
    fi
fi

echo ""
echo "📁 Criando diretórios necessários..."

if [ ! -d "public_html/logs" ]; then
    mkdir -p "public_html/logs"
    echo -e "${GREEN}✓${NC} Diretório de logs criado"
else
    echo -e "${GREEN}✓${NC} Diretório de logs já existe"
fi

echo ""
echo "📄 Arquivo de configuração WhatsApp..."
if [ ! -f "public_html/config.whatsapp.php" ]; then
    echo -e "${YELLOW}!${NC} Copie 'config.whatsapp.example.php' para 'config.whatsapp.php'"
    echo "   e configure com suas credenciais de WhatsApp"
else
    echo -e "${GREEN}✓${NC} Arquivo de configuração WhatsApp já existe"
fi

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo -e "${GREEN}✓ Instalação Concluída!${NC}"
echo "═══════════════════════════════════════════════════════════════"
echo ""
echo "Próximos passos:"
echo "1. Configure seu WhatsApp em: public_html/config.whatsapp.php"
echo "2. Acesse: http://seu-site.com/index.php?action=schedules"
echo "3. Comece a criar agendamentos!"
echo ""
echo "Para documentação completa, veja: SCHEDULES_README.md"
