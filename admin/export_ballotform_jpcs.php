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

<h2 style='text-align: center;'>Election Ballot Form</h2>
<p class='shading-instructions'>Please shade the circle next to the candidate's name of your choice.</p>

<table>
    <thead>
        <tr>
            <th>Position</th>
            <th>Candidate</th>
        </tr>
    </thead>
    <tbody>";

// Loop through each position
foreach ($positions as $position) {
    $pdfContent .= "
    <tr>
        <td colspan='2' class='position-title'>$position</td>
    </tr>";

    // Fetch candidates for the current position
    $sql = "SELECT firstname, lastname 
            FROM candidates 
            LEFT JOIN categories ON categories.id = candidates.category_id 
            WHERE categories.name = '$position' AND candidates.election_id = 1 
            ORDER BY candidates.id ASC";
    $query = $conn->query($sql);

    // Loop through each candidate and add to the PDF content
    while($row = $query->fetch_assoc()) {
        $candidate_name = $row['firstname'] . ' ' . $row['lastname'];
        $pdfContent .= "
        <tr>
            <td class='candidate-name'>&#9675; $candidate_name</td>
        </tr>";
    }
}

$pdfContent .= "
    </tbody>
</table>";

// Generate PDF
$mpdf->WriteHTML($pdfContent);
$mpdf->Output('ballot_form.pdf', 'D');

exit;
?>
