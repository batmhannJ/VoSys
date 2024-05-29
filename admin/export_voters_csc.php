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

// Query to get the list of remaining voters who have not voted
$sql_remaining_voters = "
    SELECT 
        voters.firstname, 
        voters.lastname, 
        voters.organization, 
        voters.email, 
        voters.voters_id,
        voters.yearLvl
    FROM 
        voters
    LEFT JOIN 
        votes_csc ON voters.id = votes_csc.voters_id AND votes_csc.election_id = ?
    WHERE 
        votes_csc.voters_id IS NULL";

// Prepare and execute the SQL query
$stmt_remaining_voters = $conn->prepare($sql_remaining_voters);
$stmt_remaining_voters->bind_param("i", $election_id);
$stmt_remaining_voters->execute();
$result_remaining_voters = $stmt_remaining_voters->get_result();

// Check for SQL errors
if (!$result_remaining_voters) {
    die("Query failed: " . $conn->error);
}

$currentDate = date('F j, Y');

// Create PDF content
$pdfContent = "
<style>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
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
  margin-bottom: 10px;
  margin: 0;
  padding: 0;
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
.signature-block {
  text-align: center;
  margin-top: 30px;
}
.signature-block .name {
  border-bottom: 1px solid #000;
  display: inline-block;
  margin-bottom: 5px;
  font-weight: bold;
}
.signature-block .role {
  display: block;
}
</style>
<div class='header-container'>
  <img src='images/logo.png' alt='Logo' style='height: 70px; width: 70px; float: left;'>
  <img src='images/j.png' alt='Logo' style='height: 70px; width: 70px; float: right;'>
  <p class='school-name' style='font-size: 16px; font-weight: bold; margin-top: -10px; margin-left: 10px; margin-right: 10px;'>Our Lady of the Sacred Heart College of Guimba, Inc.<br>Guimba, Nueva Ecija</p>
  <p class='report-title' style='font-size: 16px; margin-top: 50px; margin-bottom: 10px;'>Remaining Voters Report</p>
</div>
<p style='text-align: right; padding-top: 0;'>As of {$currentDate}</p>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Organization</th>
        <th>Year Level</th>
        <th>Email</th>
        <th>Voter ID</th>
    </tr>
    </thead>
<tbody>";

// Populate data into table rows
while ($row = $result_remaining_voters->fetch_assoc()) {
    $pdfContent .= "<tr>
                        <td>{$row['firstname']} {$row['lastname']}</td>
                        <td>{$row['organization']}</td>
                        <td>{$row['yearLvl']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['voters_id']}</td>
                    </tr>";
}

$pdfContent .= "
  </tbody>
</table>
<br><br>
<div class='signature-block'>
  <span class='name'>HANNAH JOY REYES</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>CHARMAINE JOYCE COLOMA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <img src='images/lyka-esign.png' alt='Signature'><br>
  <span class='name'>LYKA REFUGIA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>MARIE LORAIN PERONA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <img src='images/santy-esign.png' alt='Signature'><br>
  <span class='name'>SANTY P. BALMORES</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <img src='images/luis-esign-removebg-preview.png' alt='Signature'><br>
  <span class='name'>LUIS B. TADENA</span><br>
  <span class='role'>Head of COMELEC</span>
</div>
<div class='signature-block'>
  <span class='name'>JESSICA MAE C. SALAZAR</span><br>
  <span class='role'>Student Affair Officer</span>
</div>
";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('remaining_voters.pdf', 'D'); // 'D' indicates to force download

exit;
?>
