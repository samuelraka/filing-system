const express = require('express');
const app = express();
const arsipRoutes = require('./routes/arsipRoutes');  // Import route arsip

const port = 3003;

//Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true })); // For form data

// Enable CORS for all routes
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
    res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    if (req.method === 'OPTIONS') {
        res.sendStatus(200);
    } else {
        next();
    }
});

//Routes
app.get("/", (req, res) => {
    res.send("Hello World! ArsipOnline API is running.");
});

app.use('/arsip', arsipRoutes);  // Menggunakan route arsip

//Server
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});
