const mysql = require("mysql2");

//Connection
const db = mysql.createPool({
    host: 'localhost',
    user: 'root', // Ganti dengan username MySQL kamu
    password: '', // Ganti dengan password MySQL kamu
    database: 'archives_db', // Nama database yang kamu gunakan
    waitForConnections: true,
    connectionLimit: 10, // Tentukan jumlah koneksi pool
    queueLimit: 0
});

//Make connection
const promisePool = db.promise();