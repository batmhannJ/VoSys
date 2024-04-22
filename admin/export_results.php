<?php
// Include Composer autoload if using mPDF
require_once __DIR__ . '/vendor/autoload.php';

// Include database connection
require_once 'includes/conn.php'; // Adjust the path as per your file structure

// Check if organization filter is set
$organizationFilter = '';
if(isset($_GET['organization']) && !empty($_GET['organization'])) {
    $organizationFilter = " AND voters1.organization = '".$_GET['organization']."'";
}

// Determine whether to include the organization field in the query
$includeOrganization = ($_GET['organization'] === '') ? ', voters1.organization' : '';

// Determine the sorting order for the organization column
$organizationSort = ($_GET['organization'] === '') ? 'voters1.organization ASC,' : '';

// Query to calculate vote count for each candidate with organization filter
$sql = "SELECT candidates.firstname, candidates.lastname, categories.name AS position_name, COUNT(votes.candidate_id) AS vote_count{$includeOrganization}
        FROM candidates
        LEFT JOIN votes ON candidates.id = votes.candidate_id
        LEFT JOIN categories ON candidates.category_id = categories.id
        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
        WHERE voters1.organization != ''
        {$organizationFilter}
        GROUP BY candidates.id{$includeOrganization}
        ORDER BY {$organizationSort} position_name ASC";

$result = $conn->query($sql);

// Create PDF content
$pdfContent = "
<style>
body {
  font-family: Arial, sans-serif;
  color: #333;
}

  h1, h2 {
    font-size: 14px;
    text-align: center;
    color: #000;
  }

  p {
    font-family: Brush Script MT, cursive;
    text-align: center;
    color: #000;
  }

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
</style>
<center>
<p><img src='images/logo.png'>Our Lady of the Sacred Heart College of Guimba, Inc.</p>
<h1>2024 Election Results </h1>
</center>
<table>
    <thead>
    <tr>";
    
// Include organization column header only if filtering by all organizations
if ($_GET['organization'] === '') {
    $pdfContent .= "<th>Organization</th>";
}

$pdfContent .= "<th>Position</th>
        <th>Candidates</th>
        <th>Vote Count</th>
    </tr>
    </thead>
<tbody>";

// Populate data into table rows
while ($row = $result->fetch_assoc()) {
    // If the organization is not specified, skip adding the organization column
    $organizationColumn = ($_GET['organization'] === '') ? "<td>{$row['organization']}</td>" : '';
    
    $pdfContent .= "<tr>{$organizationColumn}
                        <td>{$row['position_name']}</td>
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
