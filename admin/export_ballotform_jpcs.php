<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include session and database connection
include 'includes/session.php';

// Set up election positions (from your database logic, similar to the HTML page)
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

// Create PDF content with circles next to the candidates
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
</style>
<img src='images/logo.png' alt='School Logo' style='float: left;'>
    <img src='images/j.png' alt='JPCS Logo' style='float: right;'>
    <p class='school-name'>
        Our Lady of the Sacred Heart College of Guimba, Inc.<br>Guimba, Nueva Ecija
    </p>
    <p class='report-title'>Election Ballot Form</p>
    <p class='shading-instructions'>Please shade the circle next to the candidate's name of your choice.<br>As of $currentDate</p>

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

    // Check if candidates exist for this position
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $candidate_name = $row['firstname'] . ' ' . $row['lastname'];
            $pdfContent .= "
            <tr>
                <td></td>
                <td class='candidate-name'>
                    <span style='display:inline-block;width:15px;height:15px;border:2px solid black;border-radius:50%;margin-right:10px;'></span>$candidate_name
                </td>
            </tr>";
        }
    } else {
        // If no candidates, show "No candidates"
        $pdfContent .= "
        <tr>
            <td></td>
            <td class='candidate-name'>
                <span style='display:inline-block;width:15px;height:15px;border:2px solid black;border-radius:50%;margin-right:10px;'></span>No candidates
            </td>
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
