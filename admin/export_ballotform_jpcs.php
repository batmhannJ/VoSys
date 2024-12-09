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

// Create PDF content
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
    .header-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .school-name {
        font-size: 18px;
        font-weight: bold;
    }
    .report-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .voter-id-container {
        margin-bottom: 20px;
        font-size: 14px;
    }
</style>

<div class='header-container'>
    <img src='images/logo.png' alt='School Logo' style='width: 80px; float: left;'>
    <img src='images/j.png' alt='JPCS Logo' style='width: 80px; float: right;'>
    <p class='school-name'>
        Our Lady of the Sacred Heart College of Guimba, Inc.<br>Guimba, Nueva Ecija
    </p>
    <p class='report-title'>Election Ballot Form</p>
</div>

<div class='voter-id-container'>
    <strong>Voter ID:</strong> _____________________________
</div>

<table>
    <tbody>";

// Iterate through positions
foreach ($positions as $position) {
    // Get the max_vote for the position from the categories table
    $sql_max_vote = "SELECT max_vote FROM categories WHERE name = '$position' AND election_id = 1";
    $max_vote_result = $conn->query($sql_max_vote);
    $max_vote = 0;

    if ($max_vote_result->num_rows > 0) {
        $max_vote_row = $max_vote_result->fetch_assoc();
        $max_vote = $max_vote_row['max_vote'];
    }

    // Display position with the max vote instruction (Position in bold, the rest not bold)
    $pdfContent .= "
    <tr>
        <td colspan='2' class='position-title'>$position - You can only vote $max_vote candidate/s for this position</td>
    </tr>";

    // Fetch candidates for each position from JPCS and Election ID 1
    $sql = "SELECT * FROM candidates 
            LEFT JOIN categories ON categories.id = candidates.category_id 
            WHERE categories.name = '$position'
              AND candidates.election_id = 1
            ORDER BY candidates.lastname ASC";

    $query = $conn->query($sql);

    // Check if candidates exist for this position
    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $candidate_name = $row['firstname'] . ' ' . $row['lastname'];
            $pdfContent .= "
            <tr>
                <td colspan='2' class='candidate-name'>
                    &#x25CB; $candidate_name <!-- Unicode for circle (⚪) -->
                </td>
            </tr>";
        }
    } else {
        // If no candidates, show "No candidates"
        $pdfContent .= "
        <tr>
            <td colspan='2' class='candidate-name'>
                &#x25CB; No candidates <!-- Unicode for circle (⚪) -->
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
