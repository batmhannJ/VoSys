const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');

// Set up the express app
const app = express();
app.use(bodyParser.json());

// Create MySQL connection
const db = mysql.createConnection({
    host: 'localhost',    // e.g., 'localhost' or Hostinger database host
    user: 'u247141684_vosys', // e.g., 'root' or your Hostinger username
    password: 'vosysOlshco5', // your MySQL password
    database: 'u247141684_votesystem', // your database name on Hostinger
    port: 3306 
});

// Connect to the database
db.connect(err => {
    if (err) {
        console.error('Error connecting to the database:', err);
        return;
    }
    console.log('Connected to the database');
});

// Endpoint to receive votes and save them to MySQL
app.post('/votes', (req, res) => {
    const voteData = req.body;

    // SQL query to insert vote data
    const query = `
    INSERT INTO votes (election_id, category_id, voters_id, org_id, candidate_id, position_id, organization)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    `;

    // Values from the extracted vote data
    const values = [
        voteData.election_id,       // The election ID
        voteData.category_id,       // The category (e.g., President)
        voteData.voters_id,         // The voter ID
        voteData.org_id,            // The organization ID
        voteData.candidate_id,      // The candidate selected
        voteData.position_id,       // The position (e.g., President)
        voteData.organization       // The organization name (if needed)
    ];

    // Execute the query
    db.query(query, values, (err, result) => {
        if (err) {
            console.error('Error saving vote:', err);
            return res.status(500).send('Error saving vote');
        }
        console.log('Vote saved successfully!');
        res.status(200).send('Vote saved successfully!');
    });
});

// Start the Express server
app.listen(3000, () => {
    console.log('Server is running on http://localhost:3000');
});
