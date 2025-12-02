#!/bin/bash
set -e

echo "🚀 Iniciando WppAPI container..."

# Limpar lock do Chromium (caso exista de execuções anteriores)
rm -f /app/tokens/rmjardim-session/SingletonLock

# Instalar dependências
npm install

# Iniciar API
exec npm start