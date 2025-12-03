const wppconnect = require('@wppconnect-team/wppconnect');
const express = require('express');
const axios = require('axios');
const fs = require('fs');
const path = require('path');
const app = express();

app.use(express.json());

let client;

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

wppconnect
    .create({
        session: 'rmjardim-session',
        catchQR: (base64Qrimg, asciiQR, attempts, urlCode) => {
            console.log('QR Code:', asciiQR);
        },
        statusFind: (statusSession, session) => {
            console.log('Status:', statusSession);
        },
        headless: 'new',
        devtools: false,
        useChrome: true,
        debug: false,
        logQR: true,
        browserArgs: [
            '--disable-web-security',
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-features=IsolateOrigins,site-per-process'
        ],
        disableWelcome: true,
        updatesLog: true,
        autoClose: 60000,
        tokenStore: 'file',
        folderNameToken: './tokens',
    })
    .then((cli) => {
        client = cli;
        console.log('✅ WhatsApp conectado!');
        
        // ============================================
        //               WEBHOOK
        // ============================================
        client.onAnyMessage(async (message) => {
            try {
                console.log('📩 Mensagem recebida:', {
                    from: message.from,
                    body: message.body,
                    isGroupMsg: message.isGroupMsg
                });

                // Ignorar mensagens de grupos
                if (message.isGroupMsg) {
                    return;
                }

                // Ignorar mensagens de status
                if (message.from === 'status@broadcast') {
                    return;
                }

                // Ignorar mensagens vazias
                if (!message.body || message.body.trim() === '') {
                    return;
                }

                // Enviar para o Laravel
                const webhookUrl = 'http://rmjardim-laravel:8000/webhook/whatsapp';
                
                const response = await axios.post(webhookUrl, {
                    from: message.from,
                    body: message.body,
                    timestamp: message.timestamp,
                    sender: message.sender
                });

                console.log('✅ Webhook enviado:', response.data);

            } catch (error) {
                console.error('❌ Erro ao processar mensagem:', error.message);
            }
        });
        
        console.log('🎯 Webhook configurado para enviar comandos ao Laravel');
    })
    .catch((error) => {
        console.error('❌ Erro ao conectar:', error);
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

        if (!client) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        console.log(`Enviando mensagem para: ${formattedPhone}`);

        await client.sendText(formattedPhone, message);

        res.json({ 
            success: true, 
            message: 'Mensagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        console.error('Erro ao enviar mensagem:', error);
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

        if (!client) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        console.log(`Enviando imagem para: ${formattedPhone}`);
        console.log(`URL da imagem: ${image}`);

        tempFilePath = await downloadImage(image);
        console.log(`Imagem baixada: ${tempFilePath}`);

        await client.sendImage(
            formattedPhone,
            tempFilePath,
            path.basename(tempFilePath),
            caption || ''
        );

        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }

        res.json({ 
            success: true, 
            message: 'Imagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        console.error('Erro ao enviar imagem:', error);
        
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

        if (!client) {
            return res.status(503).json({ 
                success: false, 
                error: 'WhatsApp não conectado' 
            });
        }

        const formattedPhone = formatPhoneNumber(phone);
        console.log(`Enviando arquivo para: ${formattedPhone}`);

        tempFilePath = await downloadImage(file);

        await client.sendFile(
            formattedPhone,
            tempFilePath,
            filename || path.basename(tempFilePath),
            caption || ''
        );

        if (tempFilePath && fs.existsSync(tempFilePath)) {
            fs.unlinkSync(tempFilePath);
        }

        res.json({ 
            success: true, 
            message: 'Arquivo enviado com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        console.error('Erro ao enviar arquivo:', error);
        
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

        if (!client) {
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
        console.log(`Enviando imagem para: ${formattedPhone}`);
        console.log(`Arquivo: ${imagePath}`);

        await client.sendImage(
            formattedPhone,
            imagePath,
            path.basename(imagePath),
            caption || ''
        );

        res.json({ 
            success: true, 
            message: 'Imagem enviada com sucesso',
            to: formattedPhone
        });
    } catch (error) {
        console.error('Erro ao enviar imagem:', error);
        res.status(500).json({ 
            success: false, 
            error: error.message 
        });
    }
});

app.listen(3000, () => {
    console.log('🚀 API rodando na porta 3000');
});