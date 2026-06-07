const express = require('express');

const app = express();
const PORT = process.env.PORT || 3000;

const missions = [
    'Redémarrer le module Docker Engine',
    'Vérifier le mapping des ports critiques',
    'Isoler les conteneurs arrêtés',
    'Scanner les images non utilisées',
    'Sécuriser la passerelle réseau locale'
];

app.get('/api/health', (req, res) => {
    res.json({
        status: 'OPERATIONAL',
        uptimeSeconds: Math.floor(process.uptime()),
        timestamp: new Date().toISOString(),
        memoryMB: Math.round(process.memoryUsage().rss / 1024 / 1024),
        mappedPort: PORT
    });
});

app.get('/api/mission', (req, res) => {
    const mission = missions[Math.floor(Math.random() * missions.length)];
    res.json({ mission });
});

app.get('/', (req, res) => {
    res.send(`
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Industrial Apocalypse Control Panel</title>
  <style>
    :root {
      --bg: #0f1115;
      --panel: #1a1f28;
      --accent: #ff7a18;
      --accent2: #ffe53b;
      --text: #d6dbe3;
      --danger: #ff3b3b;
      --ok: #2cff87;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Segoe UI", Arial, sans-serif;
      color: var(--text);
      background: radial-gradient(circle at 20% 20%, #262b33 0%, var(--bg) 45%),
                  repeating-linear-gradient(90deg, rgba(255,255,255,.03) 0 2px, transparent 2px 8px);
      min-height: 100vh;
      overflow-x: hidden;
    }
    .glow {
      position: fixed;
      inset: 0;
      pointer-events: none;
      background: radial-gradient(circle at 80% 10%, rgba(255,122,24,.25), transparent 35%),
                  radial-gradient(circle at 10% 85%, rgba(255,59,59,.22), transparent 35%);
      mix-blend-mode: screen;
    }
    .scanlines::before {
      content: "";
      position: fixed;
      inset: 0;
      pointer-events: none;
      background: repeating-linear-gradient(
        0deg,
        rgba(255,255,255,0.03),
        rgba(255,255,255,0.03) 1px,
        transparent 1px,
        transparent 3px
      );
    }
    .container {
      max-width: 1050px;
      margin: 0 auto;
      padding: 24px;
    }
    .title {
      font-size: clamp(1.6rem, 4vw, 2.6rem);
      margin: 0 0 8px;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--accent2);
      text-shadow: 0 0 12px rgba(255,229,59,.4);
    }
    .subtitle { opacity: .9; margin-bottom: 20px; }
    .grid {
      display: grid;
      gap: 14px;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      margin-bottom: 16px;
    }
    .card {
      background: linear-gradient(180deg, #1d2330, var(--panel));
      border: 1px solid rgba(255,122,24,.35);
      border-radius: 12px;
      padding: 14px;
      box-shadow: 0 0 18px rgba(0,0,0,.35);
    }
    .label { font-size: .78rem; opacity: .8; text-transform: uppercase; letter-spacing: .08em; }
    .value { font-size: 1.35rem; font-weight: 700; margin-top: 6px; }
    .ok { color: var(--ok); }
    .danger { color: var(--danger); }
    .controls { display: flex; flex-wrap: wrap; gap: 10px; margin: 14px 0; }
    button {
      border: none;
      border-radius: 10px;
      padding: 10px 14px;
      color: #111;
      background: linear-gradient(90deg, var(--accent), var(--accent2));
      font-weight: 700;
      cursor: pointer;
    }
    button.alt {
      background: linear-gradient(90deg, #9f2b2b, #ff3b3b);
      color: #fff;
    }
    .terminal {
      margin-top: 8px;
      background: #0b0d12;
      border: 1px solid #2a2f3a;
      border-radius: 12px;
      padding: 12px;
      min-height: 150px;
      font-family: Consolas, monospace;
      font-size: .9rem;
      line-height: 1.45;
      white-space: pre-wrap;
      color: #9df6b0;
    }
    .urgent {
      filter: saturate(1.3);
      animation: pulse .8s infinite alternate;
    }
    @keyframes pulse {
      from { box-shadow: 0 0 0 rgba(255,59,59,0); }
      to { box-shadow: 0 0 30px rgba(255,59,59,.35); }
    }
  </style>
</head>
<body class="scanlines">
  <div class="glow"></div>
  <div class="container" id="panel">
    <h1 class="title">Industrial Apocalypse Dock</h1>
    <p class="subtitle">Image déployée avec succès. Conteneur accessible sur <strong>http://localhost:${PORT}</strong></p>

    <div class="grid">
      <div class="card">
        <div class="label">Statut conteneur</div>
        <div class="value ok" id="status">EN LIGNE</div>
      </div>
      <div class="card">
        <div class="label">Port mappé</div>
        <div class="value">${PORT}:3000</div>
      </div>
      <div class="card">
        <div class="label">Compte à rebours maintenance</div>
        <div class="value" id="countdown">05:00</div>
      </div>
      <div class="card">
        <div class="label">Mission du moment</div>
        <div class="value" id="mission">En attente...</div>
      </div>
    </div>

    <div class="controls">
      <button id="diagBtn">Lancer diagnostic</button>
      <button id="missionBtn">Nouvelle mission</button>
      <button id="modeBtn" class="alt">Mode urgence</button>
    </div>

    <div class="terminal" id="log">[BOOT] Système initialisé.\n[INFO] Prêt pour les opérations Docker.</div>
  </div>

  <script>
    const logEl = document.getElementById('log');
    const statusEl = document.getElementById('status');
    const missionEl = document.getElementById('mission');
    const countdownEl = document.getElementById('countdown');
    const panelEl = document.getElementById('panel');

    const appendLog = (line) => {
      logEl.textContent += '\n' + line;
      logEl.scrollTop = logEl.scrollHeight;
    };

    let seconds = 300;
    setInterval(() => {
      seconds = Math.max(0, seconds - 1);
      const m = String(Math.floor(seconds / 60)).padStart(2, '0');
      const s = String(seconds % 60).padStart(2, '0');
      countdownEl.textContent = m + ':' + s;
      if (seconds === 0) {
        appendLog('[ALERTE] Fenêtre de maintenance atteinte.');
        seconds = 300;
      }
    }, 1000);

    document.getElementById('diagBtn').addEventListener('click', async () => {
      appendLog('[SCAN] Diagnostic en cours...');
      try {
        const res = await fetch('/api/health');
        const data = await res.json();
        statusEl.textContent = data.status;
        statusEl.className = 'value ok';
        appendLog('[OK] Uptime: ' + data.uptimeSeconds + 's | RAM: ' + data.memoryMB + 'MB | ' + data.timestamp);
      } catch (e) {
        statusEl.textContent = 'OFFLINE';
        statusEl.className = 'value danger';
        appendLog('[ERREUR] Impossible de joindre l\'API de santé.');
      }
    });

    document.getElementById('missionBtn').addEventListener('click', async () => {
      const res = await fetch('/api/mission');
      const data = await res.json();
      missionEl.textContent = data.mission;
      appendLog('[MISSION] ' + data.mission);
    });

    document.getElementById('modeBtn').addEventListener('click', () => {
      panelEl.classList.toggle('urgent');
      appendLog(panelEl.classList.contains('urgent')
        ? '[MODE] Urgence activée.'
        : '[MODE] Urgence désactivée.');
    });
  </script>
</body>
</html>
  `);
});

app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});