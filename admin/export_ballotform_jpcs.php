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

// Dummy candidate names (replace with dynamic content if needed)
$candidates = [
    'Candidate 1',
    'Candidate 2',
    'Candidate 3',
    'Candidate 4',
    'Candidate 5'
];

// Create ballot content
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
    .shading-area {
        text-align: center;
    }
    .circle {
        height: 15px;
        width: 15px;
        border: 2px solid black;
        border-radius: 50%;
        display: inline-block;
    }
    .shading-instructions {
        font-style: italic;
        font-size: 12px;
        color: #555;
        text-align: center;
    }
</style>

<h2 style='text-align: center;'>Election Ballot Form</h2>
<p class='shading-instructions'>Please shade the circle next to the candidate's name of your choice.</p>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Candidate</th>
            <th>Shading Area</th>
        </tr>
    </thead>
    <tbody>";

// Iterate through positions and add 5 candidates for each
foreach ($positions as $position) {
    $pdfContent .= "
    <tr>
        <td colspan='3' class='position-title'>$position</td>
    </tr>";

    // Add candidates for each position
    foreach ($candidates as $candidate) {
        $pdfContent .= "
        <tr>
            <td></td>
            <td class='candidate-name'>$candidate</td>
            <td class='shading-area'><span class='circle'></span></td>
        </tr>";
    }
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
