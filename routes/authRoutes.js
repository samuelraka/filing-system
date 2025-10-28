const express = require("express");
const router = express.Router();
const promisePool = require("../database/db");

// Login endpoint
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
            'SELECT id, name, username, password, role FROM users WHERE username = ?',
            [username]
        );

        if (rows.length === 0) {
            return res.status(401).json({ 
                success: false,
                message: 'Username tidak ditemukan' 
            });
        }

        const user = rows[0];

        // Cek password (plain text untuk testing)
        if (password !== user.password) {
            return res.status(401).json({ 
                success: false,
                message: 'Password salah' 
            });
        }

        res.json({ 
            success: true,
            message: 'Login berhasil',
            user: {
                id: user.id,
                name: user.name,
                username: user.username,
                role: user.role
            }
        });

    } catch (err) {
        console.error('Login error:', err);
        res.status(500).json({ 
            success: false,
            message: 'Kesalahan server saat login' 
        });
    }
});

module.exports = router;
