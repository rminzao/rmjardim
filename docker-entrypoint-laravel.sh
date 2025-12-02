#!/bin/bash
set -e

echo "🚀 Iniciando Laravel container..."

# Instalar dependências como root
echo "📦 Instalando dependências..."
composer update --no-interaction
npm install

# Corrigir permissões
echo "🔧 Ajustando permissões..."
chown -R www-data:www-data /var/www

# Aguardar SQL Server estar pronto
echo "⏳ Aguardando SQL Server..."
sleep 5

# Criar database se não existir
echo "🗄️  Criando database..."
/opt/mssql-tools18/bin/sqlcmd -S rmjardim-sqlserver -U sa -P RmJardim@2024 -C -Q "IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'rmjardim') CREATE DATABASE rmjardim"

# Executar comandos Laravel como www-data
echo "🔑 Gerando APP_KEY..."
su www-data -s /bin/sh -c 'php artisan key:generate --force'

echo "🗄️  Rodando migrations..."
su www-data -s /bin/sh -c 'php artisan migrate --force'

echo "🌱 Rodando seeders..."
su www-data -s /bin/sh -c 'php artisan db:seed --class=SiteDataSeeder --force'

echo "⚡ Iniciando Vite (background)..."
su www-data -s /bin/sh -c 'npm run dev' &

echo "🌐 Iniciando Laravel server..."
exec su www-data -s /bin/sh -c 'php artisan serve --host=0.0.0.0 --port=8000'