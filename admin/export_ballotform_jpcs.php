<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Create PDF content for ballot form
$currentDate = date('F j, Y');

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
  color: #fff;
  background-color: maroon;
  font-weight: bold;
  text-align: center;
}

td {
  text-align: left;
}

.shaded-circle {
  display: inline-block;
  height: 15px;
  width: 15px;
  border-radius: 50%;
  border: 1px solid black;
  margin-right: 10px;
}

.header-container {
  text-align: center;
  margin-bottom: 10px;
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
  <img src='images/logo.png' alt='Logo' style='height: 70px; width: 70px; float: left;'>
  <img src='images/j.png' alt='Logo' style='height: 70px; width: 70px; float: right;'>
  <p class='school-name' style='font-size: 16px; font-weight: bold; margin-top: -10px;'>Our Lady of the Sacred Heart College of Guimba, Inc.<br>Guimba, Nueva Ecija</p>
  <p class='report-title' style='font-size: 16px; margin-top: 50px;'>Election Ballot</p>
</div>

<p style='text-align: right;'>As of {$currentDate}</p>

<p><strong>Instructions:</strong> To vote, please shade the circle beside the name of your preferred candidate. You can only choose one candidate per position.</p>

<table>
    <thead>
    <tr>
        <th>Position</th>
        <th>Candidate</th>
    </tr>
    </thead>
    <tbody>
        <!-- President -->
        <tr>
            <td rowspan='4'><strong>For President</strong></td>
            <td><span class='shaded-circle'></span>Candidate 1</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 2</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 3</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 4</td>
        </tr>
        
        <!-- Vice President -->
        <tr>
            <td rowspan='4'><strong>For Vice President</strong></td>
            <td><span class='shaded-circle'></span>Candidate 1</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 2</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 3</td>
        </tr>
        <tr>
            <td><span class='shaded-circle'></span>Candidate 4</td>
        </tr>

        <!-- Senators (Choose up to 12) -->
        <tr>
            <td rowspan='12'><strong>For Senators</strong></td>";
            
// Add candidates for Senators
for ($i = 1; $i <= 12; $i++) {
    $pdfContent .= "<tr><td><span class='shaded-circle'></span>Senator Candidate {$i}</td></tr>";
}

$pdfContent .= "
    </tbody>
</table>";

$pdfContent .= "
<br><br>
<p><b>Signatures:</b></p>
<div class='signature-block'>
  <span class='name'>HANNAH JOY REYES</span><br>
  <span class='role'>Tabulator</span>
</div>
<!-- Add other signatures here -->
";

// Create PDF using mPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('election_ballot_form.pdf', 'D'); // 'D' forces download

exit;
?>
