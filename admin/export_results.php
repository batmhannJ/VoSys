<?php
// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include database connection
require_once 'includes/conn.php'; // Adjust the path as per your file structure


// Query to calculate vote count for each candidate
$sql = "SELECT candidates.firstname, candidates.lastname, COUNT(votes.candidate_id) AS vote_count
        FROM candidates
        LEFT JOIN votes ON candidates.id = votes.candidate_id
        GROUP BY candidates.id";

$result = $conn->query($sql);

// Create PDF content
$pdfContent = "
<style>
  body {
    font-family: Arial, sans-serif;
    color: #333;
  }

  h1, h2 {
    text-align: center;
    color: #000;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    padding: 10px;
    border: 1px solid #ddd;
  }

  th {
    background-color: #f5f5f5; /* Light gray background for table headers */
    font-weight: bold;
  }

  tr:nth-child(even) {
    background-color: #fff; /* White background for even rows */
  }

  tr:nth-child(odd) {
    background-color: #f9f9f9; /* Light gray background for odd rows */
  }
</style>
<center><h1>Election Results</h1></center>
<h2>Candidates Vote Count</h2>
<table>
  <thead>
    <tr>
      <th>Candidates</th>
      <th>Vote Count</th>
    </tr>
  </thead>
  <tbody>";

// Populate data into table rows
while ($row = $result->fetch_assoc()) {
    $pdfContent .= "<tr>
                        <td>{$row['firstname']} {$row['lastname']}</td>
                        <td>{$row['vote_count']}</td>
                    </tr>";
}

$pdfContent .= "
  </tbody>
</table>";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('election_results.pdf', 'D'); // 'D' indicates to force download

exit;
?>
