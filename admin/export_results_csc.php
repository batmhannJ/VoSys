<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include database connection
require_once 'includes/conn.php'; // Adjust the path as per your file structure

// Query to calculate vote count for each candidate from votes_csc table
$sql = "SELECT candidates.firstname, candidates.lastname, categories.name AS position_name, COUNT(votes_csc.candidate_id) AS vote_count
        FROM candidates
        LEFT JOIN votes_csc ON candidates.id = votes_csc.candidate_id
        LEFT JOIN categories ON candidates.category_id = categories.id
        GROUP BY categories.name, candidates.id
        ORDER BY categories.priority ASC, vote_count DESC"; // Ordering by priority in categories

$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Create PDF content
$pdfContent = "
<style>
body {
  font-family: Arial, sans-serif;
  color: #333;
}

h1, h2 {
  font-size: 14px;
  text-align: center;
  color: #000;
}

p {
  font-family: Brush Script MT, cursive;
  text-align: center;
  color: #000;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  opacity: 0.8; /* Adjust the opacity value to make the table more transparent */
}

th, td {
  padding: 10px;
  border: 1px solid #ddd;
}

th {
  color: #fff;
  background-color: maroon; /* Light gray background for table headers */
  font-weight: bold;
}

tr:nth-child(even) {
  background-color: #fff; /* White background for even rows */
}

tr:nth-child(odd) {
  background-color: #f9f9f9; /* Light gray background for odd rows */
}

.highlight {
  background-color: #ffe6e6; /* Light red background for highest count of votes */
}
</style>
<center>
<p style='font-family, cursive;'>Our Lady of the Sacred Heart College of Guimba, Inc.</p>
<h1>2024 Election Results</h1>
</center>
<table>
    <thead>
    <tr>
        <th>Position</th>
        <th>Candidate</th>
        <th>Vote Count</th>
    </tr>
    </thead>
<tbody>";

// Initialize array to track highest vote count for each position
$positionMaxVotes = array();

// Populate data into table rows and highlight the candidate with the highest count of votes for each position
while ($row = $result->fetch_assoc()) {
    $position = $row['position_name'];
    $voteCount = $row['vote_count'];

    // Check if position exists in the array
    if (!isset($positionMaxVotes[$position]) || $voteCount > $positionMaxVotes[$position]) {
        // Update highest vote count for the position
        $positionMaxVotes[$position] = $voteCount;
    }
}

// Reset the data pointer to the beginning of the result set
$result->data_seek(0);

// Populate data into table rows and apply highlighting
while ($row = $result->fetch_assoc()) {
    $position = $row['position_name'];
    $voteCount = $row['vote_count'];
    $highlightClass = ($voteCount == $positionMaxVotes[$position]) ? 'highlight' : '';

    // Generate table row with conditional highlighting
    $pdfContent .= "<tr>
                        <td>{$position}</td>
                        <td>{$row['firstname']} {$row['lastname']}</td>
                        <td class='{$highlightClass}'>{$voteCount}</td>
                    </tr>";
}

$pdfContent .= "
  </tbody>
</table>";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('election_results_csc.pdf', 'D'); // 'D' indicates to force download

exit;
?>
