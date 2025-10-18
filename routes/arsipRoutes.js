const express = require("express");
const router = express.Router();
const promisePool = require("../database/db");
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

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

// Login Endpoint
router.post('/login', async (req, res) => {
    const { username, password } = req.body;
    
    if (!username || !password) {
        return res.status(400).json({ 
            success: false, 
            message: 'Username dan password harus diisi' 
        });
    }

    try {
        // Query user from database (assuming users table exists)
        const [users] = await promisePool.execute(
            'SELECT id, name, email, password, role FROM users WHERE username = ? OR email = ?',
            [username, username]
        );

        if (users.length === 0) {
            return res.status(401).json({ 
                success: false, 
                message: 'Username atau password salah' 
            });
        }

        const user = users[0];

        // For demo purposes, check if password matches (you should use bcrypt in production)
        let isValidPassword = false;
        
        // Try to compare with bcrypt first
        try {
            isValidPassword = await bcrypt.compare(password, user.password);
        } catch (error) {
            // If bcrypt fails, assume plain text comparison (for backward compatibility)
            isValidPassword = password === user.password;
        }

        if (!isValidPassword) {
            return res.status(401).json({ 
                success: false, 
                message: 'Username atau password salah' 
            });
        }

        // Create JWT token
        const token = jwt.sign(
            { 
                userId: user.id, 
                username: user.email,
                name: user.name,
                role: user.role 
            },
            'your-secret-key-change-this-in-production',
            { expiresIn: '24h' }
        );

        // Return success response with user data and token
        res.json({
            success: true,
            message: 'Login berhasil',
            data: {
                user: {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    role: user.role
                },
                token: token
            }
        });

    } catch (err) {
        console.error('Login error:', err);
        res.status(500).json({ 
            success: false, 
            message: 'Terjadi kesalahan server' 
        });
    }
});

// Dummy users endpoint for testing (can be removed later)
router.get('/dummy-users', (req, res) => {
    const dummyUsers = [
        {
            id: 1,
            name: 'Administrator',
            email: 'admin@example.com',
            username: 'admin',
            password: 'admin123', // In production, this should be hashed
            role: 'admin'
        },
        {
            id: 2,
            name: 'Regular User',
            email: 'user@example.com',
            username: 'user',
            password: 'user123', // In production, this should be hashed
            role: 'user'
        },
        {
            id: 3,
            name: 'Manager',
            email: 'manager@example.com',
            username: 'manager',
            password: 'manager123', // In production, this should be hashed
            role: 'manager'
        }
    ];
    
    res.json(dummyUsers);
});

module.exports = router;
