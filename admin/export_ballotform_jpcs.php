<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

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

// Create ballot content
$pdfContent = "
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    th {
        background-color: maroon;
        color: white;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .position-title {
        font-weight: bold;
        font-size: 16px;
    }
    .shading-instructions {
        font-style: italic;
        font-size: 12px;
        color: #555;
    }
</style>

<h2 style='text-align: center;'>Election Ballot Form</h2>
<p class='shading-instructions' style='text-align: center;'>Please shade the box next to the candidate's name of your choice.</p>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Candidate</th>
            <th>Shading Area</th>
        </tr>
    </thead>
    <tbody>";

// Iterate through positions and add them to the ballot
foreach ($positions as $position) {
    $pdfContent .= "
    <tr>
        <td class='position-title'>$position</td>
        <td>Candidate Name</td>
        <td>[ ]</td>
    </tr>";
}

$pdfContent .= "
    </tbody>
</table>";

// Create PDF using mPDF library
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfContent);

// Output PDF to browser
$mpdf->Output('ballot_form.pdf', 'D'); // 'D' for download, 'I' for inline display

exit;
?>
