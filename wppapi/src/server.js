require('dotenv').config();
const express = require('express');
const cors = require('cors');
const wppconnect = require('@wppconnect-team/wppconnect');

const app = express();
const PORT = process.env.PORT || 3000;

// Middlewares
app.use(cors());
app.use(express.json());

// Armazena a sessão do WhatsApp
let client = null;

// Inicializa o WppConnect
async function initWhatsApp() {
  client = await wppconnect.create({
    session: 'rmjardim-session',
    catchQR: (base64Qr, asciiQR) => {
      console.log('QR Code gerado! Escaneie com seu WhatsApp:');
      console.log(asciiQR);
    },
    statusFind: (statusSession, session) => {
      console.log('Status da sessão:', statusSession);
    }
  });
  
  console.log('✅ WhatsApp conectado!');
}

// Endpoint de teste
app.get('/', (req, res) => {
  res.json({ message: 'API WppConnect - RMJardim', status: 'online' });
});

// Endpoint para enviar mensagem
app.post('/send-message', async (req, res) => {
  try {
    const { phone, message } = req.body;
    
    if (!client) {
      return res.status(503).json({ error: 'WhatsApp não conectado' });
    }
    
    if (!phone || !message) {
      return res.status(400).json({ error: 'Phone e message são obrigatórios' });
    }
    
    // Formata o número (remove caracteres especiais)
    const formattedPhone = phone.replace(/\D/g, '') + '@c.us';
    
    await client.sendText(formattedPhone, message);
    
    res.json({ success: true, message: 'Mensagem enviada!' });
  } catch (error) {
    console.error('Erro ao enviar mensagem:', error);
    res.status(500).json({ error: error.message });
  }
});

// Inicia servidor
app.listen(PORT, async () => {
  console.log(`🚀 Servidor rodando na porta ${PORT}`);
  console.log('Iniciando WhatsApp...');
  await initWhatsApp();
});