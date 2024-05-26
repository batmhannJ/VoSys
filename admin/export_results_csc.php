<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include database connection
require_once 'includes/conn.php'; // Adjust the path as per your file structure

// Set the desired election ID
$election_id = 20;

// Query to calculate vote count for each candidate from votes_csc table for a specific election ID
$sql = "SELECT 
            candidates.firstname, 
            candidates.lastname, 
            categories.name AS position_name, 
            COUNT(votes_csc.candidate_id) AS vote_count
        FROM 
            candidates
        LEFT JOIN 
            votes_csc ON candidates.id = votes_csc.candidate_id
        LEFT JOIN 
            categories ON candidates.category_id = categories.id
        WHERE
            votes_csc.election_id = ?
        GROUP BY 
            categories.name, candidates.id
        ORDER BY 
            categories.priority ASC, vote_count DESC"; // Ordering by priority in categories

// Prepare and execute the SQL query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $election_id);
$stmt->execute();
$result = $stmt->get_result();

// Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Count total voters
$sql_total_voters = "SELECT COUNT(*) AS total_voters FROM voters";
$result_total_voters = $conn->query($sql_total_voters);
$total_voters_row = $result_total_voters->fetch_assoc();
$total_voters = $total_voters_row['total_voters'];

// Count voters who voted
$sql_voted_voters = "SELECT COUNT(DISTINCT voters_id) AS voted_voters FROM votes_csc WHERE election_id = ?";
$stmt_voted_voters = $conn->prepare($sql_voted_voters);
$stmt_voted_voters->bind_param("i", $election_id);
$stmt_voted_voters->execute();
$result_voted_voters = $stmt_voted_voters->get_result();
$voted_voters_row = $result_voted_voters->fetch_assoc();
$voted_voters = $voted_voters_row['voted_voters'];

// Calculate remaining voters
$remaining_voters = $total_voters - $voted_voters;

// Calculate voter turnout percentage with two decimal places
$voter_turnout = number_format(($total_voters > 0) ? (($voted_voters / $total_voters) * 100) : 0, 2);

// Create PDF content
$pdfContent = "
<style>
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
.header-container {
  text-align: center;
  margin-bottom: 20px;
}
.header-container img {
  height: 100px;
  width: 100px;
}
.header-container .school-name {
  font-size: 22px;
  font-weight: bold;
}
.header-container .report-title {
  font-size: 20px;
}
</style>
<div class='header-container'>
  <p class='school-name' style='font-size: 18px; font-weight: bold; margin-left: -10px;'>Our Lady of the Sacred Heart College of Guimba, Inc.</p>
  <p class='report-title' style='font-size: 16px; margin: 0; top: -50px; left: 50%; transform: translateX(-50%);'>2024 Election Results</p>
</div>
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
</table>
<br>
<p style='text-align: left;'><b>Total Voters:</b> {$total_voters}</p>
<p style='text-align: left;'><b>Voters Voted:</b> {$voted_voters}</p>
<p style='text-align: left;'><b>Remaining Voters:</b> {$remaining_voters}</p>
<p style='text-align: left;'><b>Voter Turnout:</b> {$voter_turnout}%</p>
";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('election_results_csc.pdf', 'D'); // 'D' indicates to force download

exit;
?>
