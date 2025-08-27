const { Client, LocalAuth } = require("whatsapp-web.js");
const qrcode = require("qrcode-terminal");
const express = require("express");

const app = express();
app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: { headless: true }
});

client.on("qr", (qr) => {
    qrcode.generate(qr, { small: true });
});

client.on("ready", () => {
    console.log("WhatsApp Web siap digunakan!");
});

client.initialize();

// API untuk kirim pesan
app.post("/send-message", async (req, res) => {
    const { number, message } = req.body;

    try {
        // format nomor WA ke internasional (misalnya 628xxx)
        const chatId = number + "@c.us";
        await client.sendMessage(chatId, message);
        res.json({ status: "success", number, message });
    } catch (err) {
        res.status(500).json({ status: "error", error: err.message });
    }
});

app.listen(8000, () => {
    console.log("Server WA berjalan di http://localhost:8000");
});
