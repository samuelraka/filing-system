const express = require('express');
const path = require('path');
const app = express();
const arsipRoutes = require('./routes/arsipRoutes');  // Import route arsip
const authRoutes = require('./routes/authRoutes');  // Import route auth

const port = 8000;

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static files (CSS, JS, images)
app.use(express.static('assets'));

// Serve PHP files (you'll need a PHP server for this to work properly)
// For now, we'll serve them as static files, but PHP won't execute
app.use(express.static(__dirname));

// API Routes
app.get("/", (req, res) => {
    res.send("Filing System Server - API is running!");
});

app.use('/arsip', arsipRoutes);  // Menggunakan route arsip

// Authentication routes
app.use('/api', authRoutes);

// Serve admin login page
app.get('/admin_login', (req, res) => {
    res.sendFile(path.join(__dirname, 'admin_login.php'));
});

// Server
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
    console.log(`Access admin login at: http://localhost:${port}/admin_login`);
    console.log(`API endpoints available at: http://localhost:${port}/arsip`);
    console.log(`Authentication API at: http://localhost:${port}/api/`);
});
