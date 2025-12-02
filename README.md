# 🌿 RM Jardim - Paisagismo e Jardinagem

Sistema completo de gestão para empresa de paisagismo com integração WhatsApp.

## 🚀 Stack Tecnológico

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Vite + Tailwind CSS + Alpine.js
- **Database:** SQL Server (Azure SQL Edge)
- **WhatsApp API:** WppConnect
- **Containerização:** Docker + Docker Compose

## 📋 Pré-requisitos

- Docker Desktop (com suporte ARM64 para Mac M1/M2)
- Git
- 4GB RAM mínimo

## 🔧 Instalação

### 1. Clone o repositório
```bash
git clone <seu-repositorio>
cd rmjardim
```

### 2. Configure as variáveis de ambiente
```bash
# Copie o exemplo
cp .env.example .env

# Edite com suas credenciais
nano .env
```

### 3. Configure o Laravel
```bash
# Copie o .env do Laravel
cp site/.env.example site/.env

# Edite as credenciais do banco
nano site/.env
```

**Configuração do SQL Server no `site/.env`:**
```env
DB_CONNECTION=sqlsrv
DB_HOST=rmjardim-sqlserver
DB_PORT=1433
DB_DATABASE=rmjardim
DB_USERNAME=sa
DB_PASSWORD=RmJardim@2024
DB_TRUST_SERVER_CERTIFICATE=true
```

### 4. Inicie os containers
```bash
# Build e start
docker-compose up --build -d

# Acompanhe os logs
docker-compose logs -f laravel
```

### 5. Acesse a aplicação

- **Site:** http://localhost:8000
- **Vite HMR:** http://localhost:5173
- **WhatsApp API:** http://localhost:3000
- **SQL Server:** localhost:1433

## 📱 WhatsApp Setup

1. Acesse http://localhost:3000
2. Escaneie o QR Code com seu WhatsApp
3. Aguarde a confirmação de conexão

## 🗃️ Database

### Migrations

As migrations são executadas automaticamente na inicialização do container.

Para executar manualmente:
```bash
docker-compose exec laravel php artisan migrate
```

### Seeders
```bash
docker-compose exec laravel php artisan db:seed
```

## 🛠️ Comandos Úteis

### Docker
```bash
# Parar containers
docker-compose down

# Rebuild completo
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d

# Ver logs
docker-compose logs -f [service]

# Entrar no container
docker-compose exec laravel bash
```

### Laravel
```bash
# Artisan commands
docker-compose exec laravel php artisan [command]

# Cache clear
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan config:clear
docker-compose exec laravel php artisan view:clear

# Generate key
docker-compose exec laravel php artisan key:generate
```

### NPM (Vite)
```bash
# Install packages
docker-compose exec laravel npm install

# Dev server (já roda automaticamente)
docker-compose exec laravel npm run dev

# Build production
docker-compose exec laravel npm run build
```

## 🏗️ Estrutura do Projeto
```
rmjardim/
├── site/                      # Laravel application
│   ├── app/
│   ├── resources/
│   │   ├── css/
│   │   ├── js/
│   │   └── views/
│   ├── public/
│   └── ...
├── wppapi/                    # WhatsApp API
│   ├── src/
│   ├── tokens/                # Session storage
│   └── package.json
├── docker-compose.yml         # Docker orchestration
├── Dockerfile.laravel         # Laravel container
├── Dockerfile.wppapi          # WppAPI container
├── docker-entrypoint-laravel.sh
├── docker-entrypoint-wppapi.sh
└── .env                       # Environment variables
```

## 🔐 Segurança

⚠️ **NUNCA** commite os seguintes arquivos:

- `.env`
- `site/.env`
- `wppapi/.env`
- `wppapi/tokens/`
- Credenciais de banco de dados

## 🐛 Troubleshooting

### Container reiniciando infinitamente
```bash
docker-compose logs laravel
```

### Database connection failed

Verifique se o SQL Server está healthy:
```bash
docker-compose ps
```

### Vite not loading

Verifique se a porta 5173 está disponível e limpe o cache:
```bash
docker-compose exec laravel npm run dev
```

## 📄 Licença

Propriedade de RM Jardim - Todos os direitos reservados.

## 👨‍💻 Suporte

Para suporte, entre em contato através do WhatsApp integrado.