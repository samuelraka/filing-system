const express = require("express");
const router = express.Router();
const promisePool = require("../database/db");

// Middleware untuk autentikasi (sementara dilewati)
const isAuthenticated = (req, res, next) => {
    next();
};

// ========================= LOGIN =========================
router.post('/login', async (req, res) => {
    const { username, password } = req.body;
    
    if (!username || !password) {
        return res.status(400).json({ 
            success: false,
            message: 'Username dan password wajib diisi'
        });
    }
    
    try {
        const [rows] = await promisePool.execute(
            'SELECT id, username, password, role FROM user WHERE username = ?',
            [username]
        );
        
        if (rows.length === 0) {
            return res.status(401).json({ 
                success: false,
                message: 'Username tidak ditemukan'
            });
        }
        
        const user = rows[0];
        
        // Untuk sementara plain text password check
        if (password !== user.password) {
            return res.status(401).json({ 
                success: false,
                message: 'Password salah'
            });
        }
        
        // Jika berhasil login
        return res.json({ 
            success: true,
            message: 'Login berhasil',
            user: {
                id: user.id,
                username: user.username,
                role: user.role
            }
        });
        
    } catch (err) {
        console.error('Login error:', err);
        return res.status(500).json({ 
            success: false,
            message: 'Terjadi kesalahan pada server / database'
        });
    }
});

// ========================= LOGOUT =========================
router.post('/logout', (req, res) => {
    // Di sini nanti bisa hapus session/token
    res.json({ 
        success: true,
        message: 'Logout berhasil'
    });
});

// ========================= GET USER (Mock) =========================
router.get('/user', isAuthenticated, async (req, res) => {
    res.json({
        success: true,
        user: {
            id: 1,
            username: 'Administrator',
            role: 'superadmin'
        }
    });
});

// ========================= GET ARSIP =========================
router.get('/arsip', async (req, res) => {
    try {
        const [rows] = await promisePool.execute('SELECT * FROM archives');
        res.json(rows);
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: 'Database error' });
    }
});

// ========================= POST ARSIP =========================
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
        res.status(201).json({ message: 'Arsip berhasil ditambahkan', id: result.insertId });
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: 'Database error' });
    }
});

module.exports = router;
