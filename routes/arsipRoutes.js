const express = require("express");
const router = express.Router();
const promisePool = require("../database/db");

//Endpoint Get Arsip
router.get("/arsip", async (req, res) => {
    try {
        const [rows, fields] = await promisePool.execute('SELECT * FROM archives');
        res.json(rows);
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: 'Database error' });
    }
});

//Endpoint Post Arsip
router.post('/', async (req, res) => {
    const { code, title, description, status, record_type_id } = req.body;
    
    if (!code || !title || !status || !record_type_id) {
        return res.status(400).json({ error: 'Missing required fields' });
    }

    try {
        const [result] = await promisePool.execute(
            'INSERT INTO archives (code, title, description, status, record_type_id) VALUES (?, ?, ?, ?, ?)',
            [code, title, description, status, record_type_id]
        );
        res.status(201).json({ message: 'Arsip added successfully', id: result.insertId });
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: 'Database error' });
    }
});

module.exports = router;