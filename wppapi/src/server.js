const wppconnect = require('@wppconnect-team/wppconnect');
const express = require('express');
const axios = require('axios');
const fs = require('fs');
const path = require('path');
const app = express();

app.use(express.json());

let client;
let qrCodeData = null;
let isConnected = false;
let connectionStartTime = null;
let messagesSent = 0;
let customLogs = [];
const MAX_LOGS = 100;

function addLog(message, type = 'info') {
    const timestamp = new Date().toLocaleTimeString('pt-BR');
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️',
        message: '📤',
        received: '📥',
        qr: '📱',
        disconnect: '🔌',
        restart: '🔄'
    };
    
    const icon = icons[type] || 'ℹ️';
    const logEntry = `[${timestamp}] ${icon} ${message}`;
    
    // Adiciona no início do array
    customLogs.unshift(logEntry);
    if (customLogs.length > MAX_LOGS) {
        customLogs.pop();
    }
    
    console.log(logEntry);
}

// Função para formatar número de telefone
function formatPhoneNumber(phone) {
    let cleaned = phone.replace(/\D/g, '');
    
    if (cleaned.startsWith('0')) {
        cleaned = cleaned.substring(1);
    }
    
    if (!cleaned.startsWith('55')) {
        cleaned = '55' + cleaned;
    }
    
    return cleaned + '@c.us';
}

// Função para baixar imagem da URL
async function downloadImage(url) {
    try {
        const response = await axios.get(url, { responseType: 'arraybuffer' });
        const buffer = Buffer.from(response.data, 'binary');
        
        const tempDir = path.join(__dirname, 'temp');
        if (!fs.existsSync(tempDir)) {
            fs.mkdirSync(tempDir);
        }
        
        const filename = `temp_${Date.now()}.${url.split('.').pop()}`;
        const filepath = path.join(tempDir, filename);
        fs.writeFileSync(filepath, buffer);
        
        return filepath;
    } catch (error) {
        throw new Error(`Erro ao baixar imagem: ${error.message}`);
    }
}

// Função para iniciar cliente WhatsApp
async function startWhatsAppClient() {
    addLog('Iniciando conexão WhatsApp...', 'info');
    
    return wppconnect.create({
        session: 'rmjardim-session',
        catchQR: (base64Qrimg, asciiQR, attempts, urlCode) => {
            qrCodeData = base64Qrimg;
            addLog('QR Code gerado - aguardando scan', 'qr');
        },
        statusFind: (statusSession, session) => {
            if (statusSession === 'qrReadSuccess') {
                addLog('QR Code escaneado com sucesso!', 'success');
            } else if (statusSession === 'inChat') {
                addLog('WhatsApp conectado!', 'success');
                isConnected = true;
                connectionStartTime = Date.now();
                qrCodeData = null;
            } else if (statusSession === 'notLogged') {
                addLog('Aguardando autenticação...', 'warning');
                isConnected = false;
            }
        },
        headless: 'new',
        devtools: false,
        useChrome: true,
        debug: false,
        logQR: false,
        browserArgs: [
            '--disable-web-security',
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-features=IsolateOrigins,site-per-process'
        ],
        disableWelcome: true,
        updatesLog: false,
        autoClose: 60000,
        tokenStore: 'file',
        folderNameToken: './tokens',
    })
    .then((cli) => {
        client = cli;
        isConnected = true;
        connectionStartTime = Date.now();
        addLog('Cliente WhatsApp inicializado', 'success');
        
        // Webhook para mensagens
        client.onAnyMessage(async (message) => {
            try {
                // Ignorar mensagens de grupos
                if (message.isGroupMsg) return;
                
                // Ignorar mensagens de status
                if (message.from === 'status@broadcast') return;
                
                // Ignorar mensagens vazias
                if (!message.body || message.body.trim() === '') return;

                addLog(`Mensagem recebida de: ${message.from}`, 'received');

                // Enviar para o Laravel
                const webhookUrl = 'http://localhost/webhook/whatsapp';
                
                await axios.post(webhookUrl, {
                    from: message.from,
                    body: message.body,
                    timestamp: message.timestamp,
                    sender: message.sender
                });

            } catch (error) {
                addLog(`Erro ao processar mensagem: ${error.message}`, 'error');
            }
        });
        
        return cli;
    })
    .catch((error) => {
        addLog(`Erro ao conectar: ${error.message}`, 'error');
        isConnected = false;
        throw error;
    });
}

// Iniciar cliente automaticamente
startWhatsAppClient();

// GET /status
app.get('/status', (req, res) => {
    const uptime = connectionStartTime ? Math.floor((Date.now() - connectionStartTime) / 1000) : 0;
    
    res.json({
        connected: isConnected,
        uptime: uptime,
        messagesSent: messagesSent
    });
});

// GET /qrcode
app.get('/qrcode', (req, res) => {
    if (qrCodeData) {
        res.json({
            qrcode: qrCodeData
        });
    } else {
        res.status(404).json({
            error: 'QR Code não disponível'
        });
    }
});

// GET /logs
app.get('/logs', (req, res) => {
    const limit = parseInt(req.query.limit) || 50;
    const recentLogs = customLogs.slice(0, limit);
    
    res.json({
        logs: recentLogs
    });
});

// POST /connect
app.post('/connect', async (req, res) => {
    try {
        if (isConnected) {
            return res.status(400).json({
                success: false,
                error: 'WhatsApp já está conectado'
            });
        }
        
        addLog('Iniciando nova conexão...', 'restart');
        await startWhatsAppClient();
        
        res.json({
            success: true,
            message: 'Conexão iniciada'
        });
    } catch (error) {
        addLog(`Erro ao conectar: ${error.message}`, 'error');
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// POST /disconnect
app.post('/disconnect', async (req, res) => {
    try {
        if (!client) {
            return res.status(400).json({
                success: false,
                error: 'Cliente não inicializado'
            });
        }
        
        addLog('Desconectando WhatsApp...', 'disconnect');
        await client.close();
        
        isConnected = false;
        connectionStartTime = null;
        qrCodeData = null;
        client = null;
        
        addLog('WhatsApp desconectado', 'disconnect');
        
        res.json({
            success: true,
            message: 'Desconectado com sucesso'
        });
    } catch (error) {
        addLog(`Erro ao desconectar: ${error.message}`, 'error');
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// POST /restart
app.post('/restart', async (req, res) => {
    try {
        addLog('Reiniciando conexão...', 'restart');
        
        if (client) {
            await client.close();
            isConnected = false;
            client = null;
        }
        
        // Deletar tokens para forçar novo QR Code
        const tokensPath = path.join(__dirname, 'tokens');
        if (fs.existsSync(tokensPath)) {
            fs.rmSync(tokensPath, { recursive: true, force: true });
            addLog('Tokens removidos', 'info');
        }
        
        qrCodeData = null;
        connectionStartTime = null;
        messagesSent = 0;
        
        setTimeout(async () => {
            await startWhatsAppClient();
        }, 2000);
        
        res.json({
            success: true,
            message: 'Reiniciando...'
        });
    } catch (error) {
        addLog(`Erro ao reiniciar: ${error.message}`, 'error');
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// Endpoint para enviar mensagem de texto
app.post('/send-message', async (req, res) => {
    try {
        const { phone, message } = req.body;

        if (!phone || !message) {
            return res.status(400).json({ 
                success: false, 
                error: 'Phone e message são obrigatórios' 
            });
        }

        if (!client || !isConnected) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        addLog(`Enviando mensagem para: ${phone}`, 'message');

        await client.sendText(formattedPhone, message);
        messagesSent++;

        res.json({ 
            success: true, 
            message: 'Mensagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        addLog(`Erro ao enviar mensagem: ${error.message}`, 'error');
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

// Endpoint para enviar imagem
app.post('/send-image', async (req, res) => {
    let tempFilePath = null;
    
    try {
        const { phone, image, caption } = req.body;

        if (!phone || !image) {
            return res.status(400).json({ 
                success: false, 
                error: 'Phone e image são obrigatórios' 
            });
        }

        if (!client || !isConnected) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        addLog(`Enviando imagem para: ${phone}`, 'message');

        tempFilePath = await downloadImage(image);

        await client.sendImage(
            formattedPhone,
            tempFilePath,
            path.basename(tempFilePath),
            caption || ''
        );

        messagesSent++;

        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }

        res.json({ 
            success: true, 
            message: 'Imagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        addLog(`Erro ao enviar imagem: ${error.message}`, 'error');
        
        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }
        
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

// Endpoint para enviar arquivo
app.post('/send-file', async (req, res) => {
    let tempFilePath = null;
    
    try {
        const { phone, file, filename, caption } = req.body;

        if (!phone || !file) {
            return res.status(400).json({ 
                success: false, 
                error: 'Phone e file são obrigatórios' 
            });
        }

        if (!client || !isConnected) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        addLog(`Enviando arquivo para: ${phone}`, 'message');

        tempFilePath = await downloadImage(file);

        await client.sendFile(
            formattedPhone,
            tempFilePath,
            filename || path.basename(tempFilePath),
            caption || ''
        );

        messagesSent++;

        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }

        res.json({ 
            success: true, 
            message: 'Arquivo enviado com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        addLog(`Erro ao enviar arquivo: ${error.message}`, 'error');
        
        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }
        
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

// Endpoint para enviar imagem usando caminho local do arquivo
app.post('/send-image-file', async (req, res) => {
    try {
        const { phone, imagePath, caption } = req.body;

        if (!phone || !imagePath) {
            return res.status(400).json({ 
                success: false, 
                error: 'Phone e imagePath são obrigatórios' 
            });
        }

        if (!client || !isConnected) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        if (!fs.existsSync(imagePath)) {
            return res.status(404).json({ 
                success: false, 
                error: 'Arquivo não encontrado: ' + imagePath 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        addLog(`Enviando imagem local para: ${phone}`, 'message');

        await client.sendImage(
            formattedPhone,
            imagePath,
            path.basename(imagePath),
            caption || ''
        );

        messagesSent++;

        res.json({ 
            success: true, 
            message: 'Imagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        addLog(`Erro ao enviar imagem: ${error.message}`, 'error');
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

const PORT = process.env.PORT || 3002;
app.listen(PORT, () => {
    addLog(`API rodando na porta ${PORT}`, 'success');
});