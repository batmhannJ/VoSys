<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include session and database connection
include 'includes/session.php';

// Set up election positions
$positions = [
    'President',
    'VP for Internal Affairs',
    'VP for External Affairs',
    'Secretary',
    'Treasurer',
    'Auditor',
    'P.R.O',
    'Dir. for Membership',
    'Dir. for Special Project',
    '2-A Rep',
    '2-B Rep',
    '3-A Rep',
    '3-B Rep',
    '4-A Rep',
    '4-B Rep'
];

// Get current date
$currentDate = date('F j, Y');

// Create PDF content with header, logo, and school name
$pdfContent = "
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    th {
        background-color: maroon;
        color: white;
        font-weight: bold;
        text-align: left;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .position-title {
        font-weight: bold;
        font-size: 16px;
        background-color: #eee;
    }
    .candidate-name {
        font-size: 14px;
        padding: 5px;
    }
    .shading-instructions {
        font-style: italic;
        font-size: 12px;
        color: #555;
        text-align: center;
    }
    .header-container {
        text-align: center;
        margin-bottom: 10px;
    }
    .header-container img {
        height: 70px;
        width: 70px;
        margin: 0 20px;
    }
    .header-container .school-name {
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
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
    <img src='images/logo.png' alt='Logo' style='float: left;'>
    <img src='images/j.png' alt='Logo' style='float: right;'>
    <p class='school-name'>Our Lady of the Sacred Heart College of Guimba, Inc.<br>Guimba, Nueva Ecija</p>
    <p class='report-title'>2024 Election Ballot Form</p>
</div>
<p style='text-align: right;'>As of {$currentDate}</p>
<p class='shading-instructions'>Please shade the circle next to the candidate's name of your choice.</p>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Candidate</th>
        </tr>
    </thead>
    <tbody>";

// Iterate through positions
foreach ($positions as $position) {
    $pdfContent .= "
    <tr>
        <td colspan='2' class='position-title'>$position</td>
    </tr>";

    // Fetch candidates for each position
    $sql = "SELECT * FROM candidates 
            LEFT JOIN categories ON categories.id = candidates.category_id 
            WHERE categories.name = '$position'
            ORDER BY candidates.lastname ASC";
    
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $candidate_name = $row['firstname'] . ' ' . $row['lastname'];
            $pdfContent .= "
            <tr>
                <td></td>
                <td class='candidate-name'>
                    &#x25CB; $candidate_name <!-- Unicode for circle (⚪) -->
                </td>
            </tr>";
        }
    } else {
        $pdfContent .= "
        <tr>
            <td></td>
            <td class='candidate-name'>
                &#x25CB; No candidates <!-- Unicode for circle (⚪) -->
            </td>
        </tr>";
    }
}

$pdfContent .= "
    </tbody>
</table>
<br><br>
<p><b>Signatures:</b></p>
<div class='signature-block'>
  <span class='name'>HANNAH JOY REYES</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>CHARMAINE JOYCE COLOMA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>LYKA REFUGIA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>MARIE LORAIN PERONA</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>SANTY P. BALMORES</span><br>
  <span class='role'>Tabulator</span>
</div>
<div class='signature-block'>
  <span class='name'>ELIZOR M. VILLANUEVA</span><br>
  <span class='role'>JPCS Adviser</span>
</div>
<div class='signature-block'>
  <span class='name'>JESSICA MAE C. SALAZAR</span><br>
  <span class='role'>Student Affair Officer</span>
</div>";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('ballot_form_with_header.pdf', 'D'); // 'D' for download

exit;
?>
