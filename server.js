const express = require('express');
const app = express();
const arsipRoutes = require('./routes/arsipRoutes');  // Import route arsip

const port = 3003;

//Middleware
app.use(express.json());

//Routes
app.get("/", (req, res) => {
    res.send("Hello World!");
});

app.use('/arsip', arsipRoutes);  // Menggunakan route arsip

//Server
app.listen(port, () => {
    console.log(`Server running on port ${port}`);
});
