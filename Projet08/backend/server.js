const express = require('express');
const mysql = require('mysql2/promise');

const app = express();
const PORT = process.env.PORT || 3000;

const dbConfig = {
    host: process.env.DB_HOST || 'database',
    port: process.env.DB_PORT || 3306,
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || 'root',
    database: process.env.DB_NAME || 'projetdb'
};

app.get('/', (req, res) => {
    res.json({
        message: 'Bienvenue sur le backend Projet08 (Docker Réseautage).'
    });
});

app.get('/api/status', async(req, res) => {
    try {
        const connection = await mysql.createConnection(dbConfig);
        const [rows] = await connection.query('SELECT NOW() AS server_time');
        await connection.end();

        res.json({
            api: 'ok',
            database: 'connected',
            time: rows[0].server_time
        });
    } catch (error) {
        res.status(500).json({
            api: 'ok',
            database: 'error',
            error: error.message
        });
    }
});

app.listen(PORT, () => {
    console.log(`Backend running on port ${PORT}`);
});