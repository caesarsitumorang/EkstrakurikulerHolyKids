<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Ekstrakurikuler - SMA Holy Kids Bersinar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Font & Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #082465 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    /* Background Partikel */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.15)"/><circle cx="90" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="15" cy="90" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
      background-size: 200px 200px;
      animation: float 20s linear infinite;
      pointer-events: none;
    }

    @keyframes float {
      0%   { transform: translateY(0) rotate(0deg); }
      50%  { transform: translateY(-20px) rotate(180deg); }
      100% { transform: translateY(0) rotate(360deg); }
    }

    /* Container */
    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      padding: 50px 40px;
      border-radius: 24px;
      width: 100%;
      max-width: 450px;
      box-shadow: 
        0 25px 50px rgba(0,0,0,0.15),
        0 0 0 1px rgba(255,255,255,0.2),
        inset 0 1px 0 rgba(255,255,255,0.9);
      animation: slideInUp 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      inset: 0;
      left: -100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      animation: shimmer 3s infinite;
      z-index: 1;
    }

    @keyframes shimmer {
      0%   { left: -100%; }
      50%  { left: 100%; }
      100% { left: 100%; }
    }

    @keyframes slideInUp {
      0%   { opacity: 0; transform: translateY(60px) scale(0.8); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Logo */
    .logo-container {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      z-index: 2;
    }

    .logo {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      animation: logoFloat 3s ease-in-out infinite alternate;
      box-shadow: 
        0 15px 30px rgba(102,126,234,0.3),
        0 0 0 10px rgba(255,255,255,0.1),
        0 0 0 20px rgba(255,255,255,0.05);
    }

    .logo::before {
      content: '';
      position: absolute;
      inset: -5px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea, #764ba2, #667eea);
      animation: rotate 6s linear infinite;
      opacity: 0.3;
      z-index: -1;
    }

    .logo img {
      width: 60%;
      border-radius: 50%;
    }

    @keyframes logoFloat {
      0%   { transform: translateY(0) scale(1); }
      100% { transform: translateY(-10px) scale(1.05); }
    }

    @keyframes rotate {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Judul */
    h2 {
      text-align: center;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 35px;
      background: linear-gradient(135deg, #2c3e50, #3498db);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .school-name {
      text-align: center;
      font-size: 14px;
      color: #7f8c8d;
      margin-bottom: 30px;
    }

    /* Input */
    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group i {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #7f8c8d;
      font-size: 16px;
      transition: color 0.3s;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 16px 18px 16px 50px;
      border: 2px solid #e9ecef;
      border-radius: 12px;
      font-size: 15px;
      background: rgba(255,255,255,0.8);
      transition: 0.3s;
    }

    input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
      background: rgba(255,255,255,0.95);
      transform: translateY(-2px);
    }

    /* Tombol */
    .login-btn {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      color: white;
      background: linear-gradient(135deg, #667eea, #764ba2);
      position: relative;
      overflow: hidden;
      transition: 0.3s;
      box-shadow: 0 8px 20px rgba(102,126,234,0.3);
    }

    .login-btn:hover {
      background: linear-gradient(135deg, #5a6fd8, #6a42a0);
      transform: translateY(-3px);
    }

    .login-btn:active {
      transform: translateY(-1px);
    }

    .login-btn .loading {
      display: none;
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255,255,255,0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-left: 10px;
    }

    .login-btn.loading .loading {
      display: inline-block;
    }

    @keyframes spin {
      0%   { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Footer */
    .footer {
      text-align: center;
      margin-top: 30px;
      font-size: 0.85rem;
      color: #7f8c8d;
      padding-top: 20px;
      border-top: 1px solid rgba(127,140,141,0.2);
    }

    .back-to-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 20px;
      font-size: 14px;
      color: #667eea;
      text-decoration: none;
      transition: 0.3s;
    }

    .back-to-home:hover {
      color: #5a6fd8;
      transform: translateX(-5px);
    }

    /* Responsive */
    @media screen and (max-width: 500px) {
      .login-container { padding: 40px 30px; margin: 20px; }
      .logo { width: 80px; height: 80px; }
      h2 { font-size: 24px; }
    }

    .particles {
      position: absolute;
      inset: 0;
      pointer-events: none;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: rgba(255,255,255,0.6);
      border-radius: 50%;
      animation: particleMove 15s linear infinite;
    }

    @keyframes particleMove {
      0%   { transform: translateY(100vh); opacity: 0; }
      10%  { opacity: 1; }
      90%  { opacity: 1; }
      100% { transform: translateY(-10vh); opacity: 0; }
    }
  </style>
</head>
<body>
  <div class="particles" id="particles"></div>
  
  <div class="login-container">
    <div class="logo-container">
      <div class="logo">
        <img src="images/image.png" alt="Logo">
      </div>
    </div>

    <h2>Login Ekstrakurikuler</h2>
    <div class="school-name">SMA Holy Kids Bersinar Medan</div>
    
    <form action="proses_login.php" method="post" id="loginForm">
      <div class="input-group">
        <input type="text" name="username" placeholder="Masukkan Username" required>
        <i class="fas fa-user"></i>
      </div>

      <div class="input-group">
        <input type="password" name="password" placeholder="Masukkan Password" required>
        <i class="fas fa-lock"></i>
      </div>

      <button type="submit" class="login-btn" id="loginBtn">
        <span>Masuk</span>
        <div class="loading"></div>
      </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
      <a href="index.html" class="back-to-home">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
      </a>
    </div>

    <div class="footer">
      © <span id="currentYear"></span> SMA Holy Kids Bersinar Medan
    </div>
  </div>

  <script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Partikel
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 15;
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (15 + Math.random() * 10) + 's';
        particlesContainer.appendChild(particle);
      }
    }
    window.addEventListener('load', createParticles);

    // Loading tombol
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('loginBtn');
      btn.classList.add('loading');
      btn.querySelector('span').textContent = 'Memproses...';
    });

    // Placeholder typing effect
    function typeEffect(element, text, speed = 100) {
      let i = 0;
      element.placeholder = '';
      const timer = setInterval(() => {
        if (i < text.length) {
          element.placeholder += text.charAt(i);
          i++;
        } else clearInterval(timer);
      }, speed);
    }

    setTimeout(() => {
      const inputs = document.querySelectorAll('input');
      const placeholders = ['Masukkan Username', 'Masukkan Password'];
      inputs.forEach((input, index) => {
        setTimeout(() => typeEffect(input, placeholders[index], 50), index * 1000);
      });
    }, 1000);
  </script>
</body>
</html>
