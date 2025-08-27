const { Client, LocalAuth } = require("whatsapp-web.js");
const qrcode = require("qrcode-terminal");
const express = require("express");
const puppeteer = require("puppeteer"); 

const app = express();
app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        executablePath: puppeteer.executablePath(),
        headless: true,
        args: [
            "--no-sandbox",
            "--disable-setuid-sandbox",
            "--disable-dev-shm-usage",
            "--disable-accelerated-2d-canvas",
            "--no-first-run",
            "--no-zygote",
            "--disable-gpu"
        ]
    },
    webVersionCache: {
        type: "remote",
        remotePath: "https://raw.githubusercontent.com/wppconnect-team/wa-version/main/html/2.2412.54.html"
    }
});


client.on("qr", (qr) => {
    qrcode.generate(qr, { small: true });
    console.log("📲 Scan QR di atas dengan WhatsApp!");
});

client.on("ready", () => {
    console.log("✅ WhatsApp Web siap digunakan!");
});

client.on("authenticated", () => {
    console.log("🔑 Login berhasil!");
});

client.on("auth_failure", (msg) => {
    console.error("❌ Autentikasi gagal:", msg);
});

client.on("disconnected", (reason) => {
    console.log("⚠️ Terputus:", reason);
    client.initialize();
});

client.initialize();

app.post("/send-message", async (req, res) => {
    const { number, message } = req.body;

    try {
        const chatId = number + "@c.us";
        await client.sendMessage(chatId, message);
        res.json({ status: "success", number, message });
    } catch (err) {
        res.status(500).json({ status: "error", error: err.message });
    }
});

app.listen(8000, () => {
    console.log("🚀 Server WA berjalan di http://localhost:8000");
});