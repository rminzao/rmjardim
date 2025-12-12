# 🌿 RM Jardim - Sistema Completo de Paisagismo e Jardinagem

Sistema profissional de gestão para empresa de paisagismo com integração WhatsApp, geração de propostas em PDF e agendamento online.

---

## 🚀 Stack Tecnológico

### **Backend**
- **Framework:** Laravel 12 (PHP 8.3+)
- **Database:** SQL Server 2019+ (Azure SQL Edge para desenvolvimento)
- **PDF Generation:** DomPDF 3.x
- **External APIs:** BrasilAPI (validação CNPJ/CPF)

### **Frontend**
- **Build Tool:** Vite 5.x
- **Styling:** TailwindCSS 3.x (custom HSL colors)
- **JavaScript:** Alpine.js 3.x (componentes reativos)
- **Icons:** Lucide Icons

### **WhatsApp Integration**
- **API:** WppConnect (Node.js 18+)
- **Server:** Express.js
- **Process Manager:** PM2 (produção) / NSSM (Windows Service)
- **Port:** 3002

### **Infraestrutura**
- **Web Server:** IIS (Windows Server 2019+)
- **SSL:** Let's Encrypt via Win-ACME
- **Containerização:** Docker + Docker Compose (desenvolvimento)

---

## 📋 Pré-requisitos

### **Ambiente de Desenvolvimento (Docker)**
```bash
# Obrigatório
- Docker Desktop 4.x+ (com suporte ARM64 para Mac M1/M2)
- Git 2.x+
- 4GB RAM mínimo
- 10GB espaço em disco

# Opcional (desenvolvimento local)
- PHP 8.3+ com extensões: openssl, pdo_sqlsrv, mbstring, xml, curl
- Node.js 18+ com npm
- Composer 2.x+
```

### **Ambiente de Produção (Windows Server)**
```bash
# Obrigatório
- Windows Server 2019+ ou Windows 10/11 Pro
- IIS 10+ com URL Rewrite Module
- PHP 8.3+ (Thread Safe) com extensões:
  - openssl, pdo_sqlsrv, sqlsrv, mbstring, xml, curl, gd, fileinfo
- Node.js 18+ LTS
- SQL Server 2019+ (Express/Standard/Enterprise)
- Composer 2.x+

# Recomendado
- PM2 (gerenciador de processos Node.js)
- NSSM (Windows Service wrapper)
- Win-ACME (gerenciador SSL Let's Encrypt)
```

---

## 🔧 Instalação - Desenvolvimento (Docker)

### **1. Clone o repositório**
```bash
git clone <seu-repositorio>
cd rmjardim
```

### **2. Configure as variáveis de ambiente raiz**
```bash
# Copie o exemplo
cp .env.example .env

# Edite com suas credenciais
nano .env
```

**Exemplo `.env` raiz:**
```env
# SQL Server
MSSQL_SA_PASSWORD=RmJardim@2024
MSSQL_DATABASE=rmjardim

# Ports
LARAVEL_PORT=8000
VITE_PORT=5173
WPPAPI_PORT=3002
SQLSERVER_PORT=1433
```

### **3. Configure o Laravel**
```bash
# Copie o .env do Laravel
cp site/.env.example site/.env

# Edite as credenciais
nano site/.env
```

**Configuração essencial `site/.env`:**
```env
APP_NAME="RM Jardim"
APP_ENV=local
APP_KEY=base64:GERAR_COM_php_artisan_key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (Docker)
DB_CONNECTION=sqlsrv
DB_HOST=rmjardim-sqlserver
DB_PORT=1433
DB_DATABASE=rmjardim
DB_USERNAME=sa
DB_PASSWORD=RmJardim@2024
DB_TRUST_SERVER_CERTIFICATE=true

# Vite
VITE_DEV_SERVER_HOST=0.0.0.0
VITE_DEV_SERVER_PORT=5173

# WhatsApp API
WPPAPI_URL=http://localhost:3002
```

### **4. Instale dependências PHP (antes do build)**
```bash
cd site
composer install --ignore-platform-reqs
cd ..
```

### **5. Inicie os containers**
```bash
# Build e start
docker-compose up --build -d

# Acompanhe os logs
docker-compose logs -f laravel

# Verifique status
docker-compose ps
```

### **6. Execute migrations**
```bash
# Aguarde SQL Server estar pronto (~30s)
docker-compose exec laravel php artisan migrate

# (Opcional) Execute seeders
docker-compose exec laravel php artisan db:seed
```

### **7. Gere a chave da aplicação**
```bash
docker-compose exec laravel php artisan key:generate
```

### **8. Crie link simbólico do storage**
```bash
docker-compose exec laravel php artisan storage:link
```

### **9. Acesse a aplicação**
- **Site:** http://localhost:8000
- **Admin:** http://localhost:8000/admin
- **Vite HMR:** http://localhost:5173
- **WhatsApp API:** http://localhost:3002/status
- **SQL Server:** localhost:1433

---

## 🏭 Instalação - Produção (Windows Server)

### **1. Instale dependências do sistema**

#### **PHP 8.3 Thread Safe**
```powershell
# Download: https://windows.php.net/download/
# Instale em: C:\PHP

# Extensões necessárias (edite php.ini):
extension=openssl
extension=pdo_sqlsrv
extension=sqlsrv
extension=mbstring
extension=curl
extension=fileinfo
extension=gd
extension=xml

# Adicione ao PATH
$env:Path += ";C:\PHP"
```

#### **SQL Server Drivers para PHP**
```powershell
# Download: https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
# Copie .dll para: C:\PHP\ext\
```

#### **Composer**
```powershell
# Download: https://getcomposer.org/Composer-Setup.exe
# Instale globalmente
```

#### **Node.js 18 LTS**
```powershell
# Download: https://nodejs.org/
# Instale com npm incluído
```

#### **PM2 (gerenciador de processos)**
```powershell
npm install -g pm2
npm install -g pm2-windows-startup

# Configure startup
pm2-startup install
```

#### **NSSM (Windows Service wrapper)**
```powershell
# Download: https://nssm.cc/download
# Extraia para: C:\nssm
$env:Path += ";C:\nssm\win64"
```

### **2. Clone e configure o projeto**
```powershell
cd C:\
git clone <seu-repositorio> rmjardim
cd rmjardim\site
```

### **3. Instale dependências do Laravel**
```powershell
# PHP dependencies
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Node.js dependencies
npm install
npm run build
```

### **4. Configure `.env` de produção**
```powershell
cp .env.example .env
notepad .env
```

**Configuração essencial `site/.env` (produção):**
```env
APP_NAME="RM Jardim"
APP_ENV=production
APP_KEY=base64:GERAR_COM_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://rmjardim.com.br

# Database (Produção)
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=rmjardim
DB_USERNAME=sa
DB_PASSWORD=SuaSenhaSegura@2024
DB_TRUST_SERVER_CERTIFICATE=true

# WhatsApp API
WPPAPI_URL=http://localhost:3002
```

### **5. Gere chave e execute migrations**
```powershell
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **6. Configure permissões de pastas**
```powershell
# Storage e Bootstrap cache precisam de permissão de escrita
icacls "C:\rmjardim\site\storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "C:\rmjardim\site\bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T

# Crie pasta para PDFs
mkdir C:\rmjardim\site\storage\app\public\proposals
icacls "C:\rmjardim\site\storage\app\public" /grant "IIS_IUSRS:(OI)(CI)F" /T
```

### **7. Configure IIS**

#### **Crie Application Pool**
```powershell
# Abra IIS Manager
# Application Pools > Add Application Pool
# Nome: RMJardim
# .NET CLR Version: No Managed Code
# Pipeline: Integrated
```

#### **Crie Site**
```powershell
# Sites > Add Website
# Nome: RMJardim
# Application Pool: RMJardim
# Physical Path: C:\rmjardim\site\public
# Binding: https://rmjardim.com.br (porta 443)
```

#### **Instale URL Rewrite Module**
```powershell
# Download: https://www.iis.net/downloads/microsoft/url-rewrite
# Instale e reinicie IIS
```

#### **Configure web.config**
Arquivo já existe em `site/public/web.config` (Laravel padrão)

### **8. Configure SSL (Let's Encrypt)**
```powershell
# Download Win-ACME: https://www.win-acme.com/
cd C:\win-acme

# Execute e siga o wizard
wacs.exe

# Escolha:
# - Create certificate with advanced options
# - Site: RMJardim
# - Validation: HTTP validation
# - Store: Default (Windows Certificate Store)
# - Binding: Create or update https binding

# Renovação automática configurada automaticamente (Task Scheduler)
```

### **9. Configure WhatsApp API como serviço**

#### **Instale dependências**
```powershell
cd C:\rmjardim\site\wppapi
npm install
```

#### **Configure PM2**
```powershell
# Crie ecosystem.config.js
notepad ecosystem.config.js
```

**Conteúdo `ecosystem.config.js`:**
```javascript
module.exports = {
  apps: [{
    name: 'rmjardim-whatsapp',
    script: 'src/server.js',
    instances: 1,
    exec_mode: 'fork',
    autorestart: true,
    watch: false,
    max_memory_restart: '500M',
    env: {
      NODE_ENV: 'production',
      PORT: 3002
    },
    error_file: 'logs/err.log',
    out_file: 'logs/out.log',
    log_date_format: 'YYYY-MM-DD HH:mm:ss Z'
  }]
};
```

#### **Inicie com PM2**
```powershell
pm2 start ecosystem.config.js
pm2 save
pm2 startup
```

#### **OU configure como Windows Service (NSSM)**
```powershell
# Instale serviço
nssm install RMJardim-WhatsApp "C:\Program Files\nodejs\node.exe" "C:\rmjardim\site\wppapi\src\server.js"

# Configure
nssm set RMJardim-WhatsApp AppDirectory "C:\rmjardim\site\wppapi"
nssm set RMJardim-WhatsApp AppEnvironmentExtra PORT=3002
nssm set RMJardim-WhatsApp AppStdout "C:\rmjardim\site\wppapi\logs\service.log"
nssm set RMJardim-WhatsApp AppStderr "C:\rmjardim\site\wppapi\logs\service-error.log"

# Inicie serviço
nssm start RMJardim-WhatsApp

# Verifique status
nssm status RMJardim-WhatsApp
```

### **10. Configure Firewall**
```powershell
# Libere porta 3002 (WhatsApp API) apenas para localhost
New-NetFirewallRule -DisplayName "RMJardim WhatsApp API" -Direction Inbound -LocalPort 3002 -Protocol TCP -Action Allow -RemoteAddress LocalSubnet

# Libere portas 80/443 (se necessário)
New-NetFirewallRule -DisplayName "RMJardim HTTP" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow
New-NetFirewallRule -DisplayName "RMJardim HTTPS" -Direction Inbound -LocalPort 443 -Protocol TCP -Action Allow
```

---

## 📱 WhatsApp Setup

### **Desenvolvimento (Docker)**
1. Acesse http://localhost:3002
2. Endpoint `/start` gerará QR Code
3. Escaneie com WhatsApp (Dispositivos Conectados)
4. Aguarde confirmação de conexão

### **Produção**
1. Acesse painel admin: https://rmjardim.com.br/admin/whatsapp
2. Clique em "Conectar"
3. Escaneie QR Code exibido
4. Status mudará para "Conectado" (verde)

**Endpoints disponíveis:**
```bash
GET  /status          # Status da conexão
POST /start           # Inicia conexão (gera QR Code)
POST /logout          # Desconecta WhatsApp
GET  /qrcode          # Obtém QR Code base64
POST /send-text       # Envia mensagem
GET  /logs            # Logs recentes (últimas 50 linhas)
```

---

## 🗃️ Database

### **Migrations**

#### **Desenvolvimento (Docker)**
```bash
docker-compose exec laravel php artisan migrate
```

#### **Produção**
```powershell
cd C:\rmjardim\site
php artisan migrate --force
```

### **Rollback**
```bash
php artisan migrate:rollback
php artisan migrate:rollback --step=1  # Apenas último batch
```

### **Seeders**
```bash
php artisan db:seed
php artisan db:seed --class=UsersTableSeeder
```

### **Tabelas Principais**
```sql
-- Propostas de serviço
proposal_services (id, client_name, client_document, date, total, pdf_path, ...)
proposal_service_items (id, proposal_service_id, description, quantity, unit_price)

-- Agendamentos (próxima feature)
schedules (id, client_name, phone, date, time, status, ...)
schedule_settings (id, service_duration, work_hours, ...)
blocked_dates (id, date, reason, ...)
```

---

## 🛠️ Comandos Úteis

### **Docker (Desenvolvimento)**

#### **Gerenciamento de Containers**
```bash
# Iniciar
docker-compose up -d

# Parar
docker-compose down

# Parar e remover volumes (CUIDADO: apaga banco)
docker-compose down -v

# Rebuild completo
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Ver logs
docker-compose logs -f [service]
docker-compose logs -f laravel
docker-compose logs -f wppapi

# Status dos containers
docker-compose ps

# Entrar no container
docker-compose exec laravel bash
docker-compose exec sqlserver bash
```

#### **Laravel no Docker**
```bash
# Artisan commands
docker-compose exec laravel php artisan [command]

# Cache management
docker-compose exec laravel php artisan cache:clear
docker-compose exec laravel php artisan config:clear
docker-compose exec laravel php artisan route:clear
docker-compose exec laravel php artisan view:clear

# Generate key
docker-compose exec laravel php artisan key:generate

# Migrations
docker-compose exec laravel php artisan migrate
docker-compose exec laravel php artisan migrate:fresh --seed

# Composer
docker-compose exec laravel composer install
docker-compose exec laravel composer update

# NPM
docker-compose exec laravel npm install
docker-compose exec laravel npm run dev
docker-compose exec laravel npm run build
```

### **Produção (Windows Server)**

#### **Laravel**
```powershell
cd C:\rmjardim\site

# Cache management
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrations
php artisan migrate --force

# Composer
composer install --no-dev --optimize-autoloader
composer dump-autoload --optimize

# Build frontend
npm run build
```

#### **PM2 (WhatsApp API)**
```powershell
# Status
pm2 status
pm2 show rmjardim-whatsapp

# Logs
pm2 logs rmjardim-whatsapp
pm2 logs --lines 100

# Restart
pm2 restart rmjardim-whatsapp
pm2 reload rmjardim-whatsapp  # Zero downtime

# Stop
pm2 stop rmjardim-whatsapp

# Delete
pm2 delete rmjardim-whatsapp

# Monitor
pm2 monit
```

#### **NSSM (alternativa ao PM2)**
```powershell
# Status
nssm status RMJardim-WhatsApp

# Start/Stop
nssm start RMJardim-WhatsApp
nssm stop RMJardim-WhatsApp
nssm restart RMJardim-WhatsApp

# Remover
nssm remove RMJardim-WhatsApp confirm
```

#### **IIS**
```powershell
# Restart
iisreset

# Restart Application Pool
Restart-WebAppPool -Name "RMJardim"

# Status
Get-WebAppPoolState -Name "RMJardim"
```

---

## 🏗️ Estrutura do Projeto

```
rmjardim/
├── site/                           # Laravel Application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Admin/
│   │   │   │   │   ├── ProposalServiceController.php
│   │   │   │   │   ├── WhatsAppManagerController.php
│   │   │   │   │   └── DashboardController.php
│   │   │   │   └── HomeController.php
│   │   │   └── Middleware/
│   │   ├── Models/
│   │   │   ├── ProposalService.php
│   │   │   ├── ProposalServiceItem.php
│   │   │   └── User.php
│   │   └── Services/
│   │       └── ProposalPDFGenerator.php
│   ├── database/
│   │   ├── migrations/
│   │   │   ├── YYYY_MM_DD_create_proposal_services_table.php
│   │   │   └── YYYY_MM_DD_create_proposal_service_items_table.php
│   │   └── seeders/
│   ├── resources/
│   │   ├── css/
│   │   │   └── app.css               # Tailwind + Custom HSL colors
│   │   ├── js/
│   │   │   ├── app.js                # Alpine.js setup
│   │   │   └── components/           # Alpine components
│   │   └── views/
│   │       ├── layouts/
│   │       │   ├── app.blade.php
│   │       │   └── admin.blade.php
│   │       ├── admin/
│   │       │   ├── dashboard.blade.php
│   │       │   ├── proposals/
│   │       │   │   ├── index.blade.php
│   │       │   │   └── create.blade.php
│   │       │   └── whatsapp/
│   │       │       └── index.blade.php
│   │       ├── pdf/
│   │       │   └── proposal.blade.php
│   │       └── welcome.blade.php
│   ├── routes/
│   │   ├── web.php                   # Rotas principais
│   │   └── api.php
│   ├── storage/
│   │   ├── app/
│   │   │   └── public/
│   │   │       └── proposals/        # PDFs gerados
│   │   ├── framework/
│   │   └── logs/
│   ├── public/
│   │   ├── images/
│   │   │   └── rmjardim-logo.png
│   │   └── index.php
│   ├── .env                          # Configuração Laravel
│   ├── composer.json
│   ├── package.json
│   └── vite.config.js
│
├── wppapi/                           # WhatsApp API (Node.js)
│   ├── src/
│   │   ├── server.js                 # Express server + WppConnect
│   │   └── logger.js                 # Sistema de logs customizado
│   ├── tokens/                       # Sessões WhatsApp (gerado)
│   ├── logs/                         # Logs da API
│   │   ├── whatsapp.log
│   │   ├── err.log
│   │   └── out.log
│   ├── package.json
│   └── ecosystem.config.js           # PM2 config (produção)
│
├── docker-compose.yml                # Docker orchestration
├── Dockerfile.laravel                # Laravel container
├── Dockerfile.wppapi                 # WhatsApp API container
├── docker-entrypoint-laravel.sh      # Laravel startup script
├── docker-entrypoint-wppapi.sh       # WppAPI startup script
├── .env.example                      # Exemplo env raiz
├── .gitignore
└── README.md
```

---

## 🎨 Features Implementadas

### **✅ 1. Sistema de Propostas**
- Formulário de cadastro com validação CNPJ/CPF
- Auto-preenchimento de dados via BrasilAPI
- Geração de PDF profissional (DomPDF)
- Tabela de itens com cálculo automático
- Descontos e valores finais
- Armazenamento de PDFs em `storage/app/public/proposals/`

**Rotas:**
```php
GET  /admin/proposals          # Lista propostas
GET  /admin/proposals/create   # Formulário
POST /admin/proposals          # Salva proposta
GET  /api/admin/cnpj/{cnpj}    # Valida CNPJ (BrasilAPI)
GET  /api/admin/cpf/{cpf}      # Valida CPF (BrasilAPI)
```

### **✅ 2. Painel Admin WhatsApp**
- Status em tempo real (conectado/desconectado)
- Informações de uptime e mensagens enviadas
- QR Code dinâmico para conexão
- Logs em tempo real (últimas 50 linhas)
- Ações: Conectar, Desconectar, Reiniciar
- Polling automático (status: 10s, logs: 5s)

**Rotas:**
```php
GET /admin/whatsapp         # Painel visual
GET /admin/whatsapp/status  # Status JSON
GET /admin/whatsapp/logs    # Logs JSON
```

### **✅ 3. WhatsApp API REST**
- Integração com WppConnect
- Endpoints REST para gerenciamento
- Sistema de logs customizado
- Reconexão automática
- Sessão persistente em `tokens/`

**Endpoints:**
```javascript
GET  /status           # { connected: bool, uptime: 123, messagesSent: 45 }
POST /start            # Inicia conexão, retorna QR Code
POST /logout           # Desconecta WhatsApp
GET  /qrcode           # { qrcode: "data:image/png;base64,..." }
POST /send-text        # { number: "5511999999999", message: "Olá!" }
GET  /logs             # { logs: "string\nstring\n..." }
```

### **🚧 4. Sistema de Agendamentos** (Em desenvolvimento)
Próxima feature a ser implementada conforme design do Lovable.

**Funcionalidades planejadas:**
- **Admin:**
  - Configurar horários de trabalho (seg-dom)
  - Definir duração padrão de serviços
  - Bloquear datas específicas (feriados, viagens)
  - Visualizar todos os agendamentos
  - Confirmar/cancelar/concluir agendamentos
  - Enviar mensagens WhatsApp aos clientes
  
- **Público:**
  - Calendário interativo com dias disponíveis
  - Seleção de horários baseada em configuração admin
  - Formulário de dados do cliente
  - Confirmação via WhatsApp automática
  - Link único por agendamento: `/agendar?token=XYZ`

**Rotas planejadas:**
```php
# Admin
GET  /admin/agenda              # Configurações
GET  /admin/agendamentos        # Lista agendamentos
POST /admin/agenda/block-date   # Bloqueia data
POST /admin/agendamentos/status # Atualiza status

# Público
GET  /agendar?token={token}     # Página de agendamento
POST /api/schedules             # Salva agendamento
GET  /api/available-slots       # Horários disponíveis
```

---

## 🔐 Segurança

### **Arquivos Sensíveis (NUNCA commite)**
```bash
# Environment files
.env
site/.env
wppapi/.env

# WhatsApp session
wppapi/tokens/

# Logs
*.log
site/storage/logs/
wppapi/logs/

# PDFs gerados
site/storage/app/public/proposals/*.pdf

# Node modules
node_modules/
site/node_modules/
wppapi/node_modules/

# Composer vendor
site/vendor/

# Cache
site/bootstrap/cache/*.php
site/storage/framework/cache/*
site/storage/framework/sessions/*
site/storage/framework/views/*
```

### **Boas Práticas de Segurança**

#### **1. Senhas fortes**
```env
# ❌ NUNCA use senhas fracas
DB_PASSWORD=admin123

# ✅ Use senhas fortes
DB_PASSWORD=Xy9$mK2#pQw8@vLn4
```

#### **2. APP_KEY único**
```powershell
# Sempre gere uma nova chave
php artisan key:generate
```

#### **3. SSL obrigatório em produção**
```env
# Force HTTPS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rmjardim.com.br
```

#### **4. Validação de entrada**
```php
// SEMPRE valide CNPJ/CPF no backend
// NUNCA confie apenas no frontend
```

#### **5. Rate limiting**
```php
// Configure em app/Http/Kernel.php
'api' => [
    'throttle:60,1',  // 60 requests por minuto
],
```

---

## 🐛 Troubleshooting

### **Docker - Container reiniciando infinitamente**
```bash
# Verifique logs
docker-compose logs laravel

# Causas comuns:
# 1. SQL Server não está pronto (aguarde ~30s após docker-compose up)
# 2. Erro em .env (DB_PASSWORD incorreto)
# 3. Falta de permissões em storage/

# Solução: Aguarde SQL Server healthy
docker-compose ps | grep healthy

# Rebuild se necessário
docker-compose down -v
docker-compose up --build -d
```

### **Docker - Database connection failed**
```bash
# Verifique se SQL Server está healthy
docker-compose ps

# Se unhealthy, reinicie container
docker-compose restart sqlserver

# Teste conexão manualmente
docker-compose exec sqlserver /opt/mssql-tools/bin/sqlcmd \
  -S localhost -U sa -P 'RmJardim@2024' -Q "SELECT @@VERSION"
```

### **Docker - Vite not loading (HMR)**
```bash
# Verifique se porta 5173 está livre
lsof -i :5173  # Mac/Linux
netstat -ano | findstr :5173  # Windows

# Restart Vite
docker-compose exec laravel npm run dev

# Limpe cache do navegador (Ctrl+Shift+R)
```

### **Produção - Erro 500 no site**
```powershell
# 1. Verifique logs do Laravel
Get-Content C:\rmjardim\site\storage\logs\laravel.log -Tail 50

# 2. Verifique permissões de pastas
icacls C:\rmjardim\site\storage
icacls C:\rmjardim\site\bootstrap\cache

# 3. Limpe cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 4. Recompile cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Produção - WhatsApp desconectando**
```powershell
# Verifique logs
pm2 logs rmjardim-whatsapp --lines 100

# OU (NSSM)
Get-Content C:\rmjardim\site\wppapi\logs\service.log -Tail 50

# Causas comuns:
# 1. Telefone sem bateria/internet
# 2. WhatsApp desconectado manualmente no app
# 3. Sessão expirada (>15 dias sem uso)

# Solução: Reconecte via painel admin
# https://rmjardim.com.br/admin/whatsapp
```

### **Produção - PDF não gerando**
```powershell
# 1. Verifique se DomPDF está instalado
composer show | findstr dompdf

# 2. Verifique permissões da pasta
icacls C:\rmjardim\site\storage\app\public\proposals

# 3. Teste geração manual
php artisan tinker
>>> $pdf = Pdf::loadHTML('<h1>Teste</h1>');
>>> $pdf->save('C:\rmjardim\site\storage\app\public\test.pdf');

# 4. Se erro "Class not found", reinstale
composer require dompdf/dompdf --ignore-platform-reqs
php artisan config:clear
```

### **Produção - CNPJ não validando**
```powershell
# 1. Teste conectividade com BrasilAPI
Invoke-WebRequest -Uri "https://brasilapi.com.br/api/cnpj/v1/00000000000191"

# 2. Verifique extensões PHP
php -m | findstr curl
php -m | findstr openssl

# 3. Verifique firewall (saída porta 443)
Test-NetConnection -ComputerName brasilapi.com.br -Port 443

# 4. Se bloqueado, adicione exceção no firewall corporativo
```

### **Composer - Conflito de dependências**
```powershell
# Se composer.lock foi gerado em PHP 8.4 mas servidor tem 8.3:
composer install --ignore-platform-reqs

# CUIDADO: Use apenas se necessário e teste tudo após instalação
```

### **IIS - 404 em rotas Laravel**
```powershell
# Certifique-se que URL Rewrite está instalado
# Download: https://www.iis.net/downloads/microsoft/url-rewrite

# Verifique se web.config existe em site/public/
Test-Path C:\rmjardim\site\public\web.config

# Restart IIS
iisreset
```

---

## 📊 Monitoramento

### **Logs a Monitorar**

#### **Laravel (Desenvolvimento)**
```bash
docker-compose logs -f laravel
docker-compose exec laravel tail -f storage/logs/laravel.log
```

#### **Laravel (Produção)**
```powershell
Get-Content C:\rmjardim\site\storage\logs\laravel.log -Tail 50 -Wait
```

#### **WhatsApp API**
```bash
# Docker
docker-compose logs -f wppapi

# Produção (PM2)
pm2 logs rmjardim-whatsapp

# Produção (NSSM)
Get-Content C:\rmjardim\site\wppapi\logs\service.log -Tail 50 -Wait
```

#### **IIS (Produção)**
```powershell
# Event Viewer
eventvwr.msc
# Navegue: Windows Logs > Application

# IIS Logs
Get-Content C:\inetpub\logs\LogFiles\W3SVC1\*.log -Tail 50
```

### **Métricas Importantes**

```bash
# CPU e Memória (PM2)
pm2 monit

# Espaço em disco
df -h                           # Linux/Mac/Docker
Get-PSDrive C | Select-Object * # Windows

# Conexões de rede
netstat -an | findstr :3002     # WhatsApp API
netstat -an | findstr :1433     # SQL Server
netstat -an | findstr :443      # HTTPS
```

---

## 🔄 Atualizações e Deploy

### **Fluxo de Atualização (Produção)**

```powershell
# 1. Backup do banco de dados
# Faça backup do SQL Server via Management Studio

# 2. Backup dos arquivos
Copy-Item -Recurse C:\rmjardim C:\rmjardim-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')

# 3. Pull das atualizações
cd C:\rmjardim
git pull origin main

# 4. Instale dependências
cd site
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
npm install
npm run build

# 5. Execute migrations
php artisan migrate --force

# 6. Limpe e recompile cache
php artisan optimize:clear
php artisan optimize

# 7. Reinicie serviços
iisreset
pm2 restart rmjardim-whatsapp

# 8. Verifique logs
pm2 logs rmjardim-whatsapp --lines 20
Get-Content C:\rmjardim\site\storage\logs\laravel.log -Tail 20

# 9. Teste site
Invoke-WebRequest -Uri "https://rmjardim.com.br" -UseBasicParsing
```

---

## 📚 Documentação Adicional

### **APIs Externas Utilizadas**

#### **BrasilAPI**
- **Docs:** https://brasilapi.com.br/docs
- **CNPJ:** `GET https://brasilapi.com.br/api/cnpj/v1/{cnpj}`
- **CEP:** `GET https://brasilapi.com.br/api/cep/v1/{cep}`
- **Rate Limit:** Ilimitado (uso responsável)

#### **WppConnect**
- **Docs:** https://wppconnect.io/
- **GitHub:** https://github.com/wppconnect-team/wppconnect
- **Sessão:** Persiste em `tokens/` (não deletar em produção)

### **Frameworks e Bibliotecas**

#### **Laravel**
- **Docs:** https://laravel.com/docs/11.x
- **Blade Templates:** https://laravel.com/docs/11.x/blade
- **Eloquent ORM:** https://laravel.com/docs/11.x/eloquent

#### **TailwindCSS**
- **Docs:** https://tailwindcss.com/docs
- **Customização:** `site/tailwind.config.js`
- **HSL Colors:** Definidas em `site/resources/css/app.css`

#### **Alpine.js**
- **Docs:** https://alpinejs.dev/
- **Versão:** 3.x
- **CDN:** Carregado via `resources/views/layouts/app.blade.php`

#### **DomPDF**
- **Docs:** https://github.com/dompdf/dompdf
- **Laravel Wrapper:** https://github.com/barryvdh/laravel-dompdf
- **Limitações:** CSS2.1, sem JavaScript, sem @font-face externo

---

## 🤝 Contribuindo

### **Git Workflow**

```bash
# 1. Crie uma branch para sua feature
git checkout -b feature/nome-da-feature

# 2. Faça commits descritivos
git add .
git commit -m "feat: adiciona sistema de agendamentos"

# 3. Push para repositório
git push origin feature/nome-da-feature

# 4. Abra Pull Request no GitHub/GitLab
```

### **Padrão de Commits**
```bash
feat:     Nova funcionalidade
fix:      Correção de bug
docs:     Atualização de documentação
style:    Formatação, espaços em branco
refactor: Refatoração de código
test:     Adição de testes
chore:    Tarefas de manutenção
```

---

## 📄 Licença

Propriedade de **RM Jardim** - Todos os direitos reservados.

Este projeto é de uso interno e confidencial. Não distribuir sem autorização.

---

## 👨‍💻 Suporte

### **Canais de Suporte**
- **WhatsApp:** Integrado no sistema (admin)
- **Email:** contato@rmjardim.com.br
- **Telefone:** (19) XXXX-XXXX

### **Desenvolvedor**
- **Nome:** [Seu Nome]
- **GitHub:** [Seu GitHub]
- **Email:** [Seu Email]

---

## 📝 Changelog

### **[1.0.0] - 2025-01-XX**
**Added:**
- Sistema de propostas com validação CNPJ/CPF
- Geração de PDF profissional
- Integração WhatsApp via WppConnect
- Painel admin de gerenciamento WhatsApp
- Auto-preenchimento de dados via BrasilAPI
- Deploy Docker para desenvolvimento
- Instruções completas para produção Windows Server

**In Progress:**
- Sistema de agendamentos online

---

## 🎯 Roadmap

### **Q1 2025**
- [ ] Sistema de agendamentos completo
- [ ] Dashboard com gráficos de estatísticas
- [ ] Sistema de templates de mensagens WhatsApp
- [ ] Histórico de mensagens WhatsApp em tabela

### **Q2 2025**
- [ ] Autenticação multi-usuário (roles: admin, operador)
- [ ] API pública para integração com sites externos
- [ ] Sistema de notificações por email
- [ ] Backup automático do banco de dados

### **Q3 2025**
- [ ] App mobile (React Native)
- [ ] Sistema de relatórios financeiros
- [ ] Integração com gateway de pagamento
- [ ] CRM básico para gestão de clientes

---

**Última atualização:** 2025-01-XX  
**Versão:** 1.0.0  
**Autor:** RM Jardim Development Team