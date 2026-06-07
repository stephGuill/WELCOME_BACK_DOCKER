// Framework HTTP Express.js
const express = require('express');
// Driver MySQL2 avec support des Promises (async/await)
const mysql = require('mysql2/promise');

const app = express();
// Port : défini par variable d'environnement (injectée par Docker Compose) ou 3000 par défaut
const PORT = process.env.PORT || 3000;

// Configuration de connexion MySQL
// Les valeurs viennent des variables d'environnement injectées par Docker Compose
// DB_HOST='database' = nom du service MySQL résolu via le réseau Docker interne
const dbConfig = {
    host: process.env.DB_HOST || 'database',
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || 'root',
    database: process.env.DB_NAME || 'projetdb'
};

// Route GET / : message de bienvenue de l'API
app.get('/', (req, res) => {
    res.json({
        message: 'Bienvenue sur le backend Projet08 (Docker Réseautage).'
    });
});

// Route GET /api/status : teste la connexion à MySQL et retourne l'heure du serveur DB
app.get('/api/status', async(req, res) => {
    try {
        // Crée une connexion MySQL temporaire (ponctuelle, pas de pool)
        const connection = await mysql.createConnection(dbConfig);
        // Requête simple pour vérifier que la DB répond et récupérer son heure
        const [rows] = await connection.query('SELECT NOW() AS server_time');
        await connection.end(); // Ferme proprement la connexion

        res.json({
            api: 'ok',
            database: 'connected',
            time: rows[0].server_time
        });
    } catch (error) {
        // En cas d'erreur de connexion, renvoie HTTP 500 avec le message d'erreur
        res.status(500).json({
            api: 'ok',
            database: 'error',
            error: error.message
        });
    }
});

// Démarre le serveur sur le port configuré
app.listen(PORT, () => {
    console.log(`Backend running on port ${PORT}`);
});