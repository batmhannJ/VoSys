<?php
// Assume $conn is your database connection
include 'includes/session.php';
include 'includes/header.php';

// Define the colors array
$colors = array("#FF0000", "#D60404", "#9F0101", "#6B0101", "#450101");
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1></h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php
            if(isset($_SESSION['error'])){
                echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".htmlspecialchars($_SESSION['error'])."
            </div>
          ";
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])){
                echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".htmlspecialchars($_SESSION['success'])."
            </div>
          ";
                unset($_SESSION['success']);
            }
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <!-- Form to select organization -->
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization">
                                        <option value="">All Organizations</option>
                                        <?php
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                                        while($organizationRow = $organizationQuery->fetch_assoc()){
                                            $selected = (isset($_POST['organization']) && $_POST['organization'] == $organizationRow['organization']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($organizationRow['organization']) . "' $selected>" . htmlspecialchars($organizationRow['organization']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>

                            <!-- Display the results based on the selected organization -->
                            <table id="example1" class="table table-bordered">
                                <thead>
                                <th>Organization</th>
                                <th>Position</th>
                                <th>Candidate</th>
                                <th>Count Votes</th> <!-- Changed header to "Count Votes" -->
                                </thead>
                                <tbody>
                                <?php
                                // Define the SQL query with organization filter
                                if(isset($_POST['organization']) && !empty($_POST['organization'])){
                                    $organizationFilter = " AND voters1.organization = '" . htmlspecialchars($_POST['organization']) . "'";
                                } else {
                                    $organizationFilter = ""; // No organization selected, so no filter
                                }
                                
                                $sql = "SELECT voters1.organization, positions.id AS position_id, positions.description AS position, 
                                        CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS count_votes
                                        FROM positions 
                                        CROSS JOIN candidates
                                        LEFT JOIN votes ON positions.id = votes.position_id AND candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE 1" . $organizationFilter . "
                                        GROUP BY voters1.organization, positions.id, candidates.id
                                        ORDER BY voters1.organization, positions.priority ASC";
                                $query = $conn->query($sql);
                                
                                // Check if there are results
                                if ($query->num_rows > 0) {
                                    while($row = $query->fetch_assoc()){
                                        echo "
                                            <tr>
                                              <td>" . htmlspecialchars($row['organization']) . "</td>
                                              <td>" . htmlspecialchars($row['position']) . "</td>
                                              <td>" . htmlspecialchars($row['candidate_name']) . "</td>
                                              <td>" . htmlspecialchars($row['count_votes']) . "</td>
                                            </tr>
                                          ";
                                    }
                                } else {
                                    // No data available for the selected organization
                                    echo "<tr><td colspan='4'>No data available for the selected organization.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>

                            <!-- Bar graph to display winners -->
                            <h2>Bar Graph of Winners</h2>
                            <div id="winnersChart" style="height: 370px; width: 100%;"></div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function() {
    // Initialize an empty array to store data points for the bar graph
    var dataPoints = [];
    
    // Loop through the query results to populate dataPoints
    <?php
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()):
        $totalVotes = $row['count_votes'];
        $candidateName = htmlspecialchars($row['candidate_name']);
        echo "dataPoints.push({ y: " . $totalVotes . ", label: '" . $candidateName . "', color: '" . $colors[array_rand($colors)] . "' });";
    endwhile;
    ?>

    var chart = new CanvasJS.Chart("winnersChart", {
        animationEnabled: true,
        title:{
            text: "Count of Votes for Candidates"
        },
        axisX: {
            title: "Candidates",
            includeZero: true
        },
        axisY: {
            title: "Count of Votes"
        },
        data: [{
            type: "bar",
            dataPoints: dataPoints
        }]
    });
    chart.render();
}
</script>

</body>
</html>
