<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Election Results
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Organization Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Election:</label>
                                    <select class="form-control" name="organization" id="organization">
                                        <option value="">All Organizations</option>
                                        <?php
                                        // Fetch and display organizations
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM election");
                                        while($organizationRow = $organizationQuery->fetch_assoc()){
                                            $selected = ($_GET['organization'] ?? '') == $organizationRow['organization'] ? 'selected' : '';
                                            echo "<option value='".$organizationRow['organization']."' $selected>".$organizationRow['organization']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking Boxes for Various Positions -->
            <div class="row">
                <?php
                // Function to display ranking table
                function displayRankingTable($conn, $position, $organizationFilter) {
                    $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes_csc.candidate_id), 0) AS vote_count
                            FROM categories 
                            LEFT JOIN candidates ON categories.id = candidates.category_id
                            LEFT JOIN votes_csc ON candidates.id = votes_csc.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes_csc.voters_id 
                            WHERE voters1.organization != '' AND categories.name = '".$position."'".$organizationFilter."
                            GROUP BY voters1.organization, candidates.id
                            ORDER BY vote_count DESC";
                    $query = $conn->query($sql);
                    $rank = 1;
                    while($row = $query->fetch_assoc()){
                        echo "
                            <tr>
                            <td>".$rank."</td>
                            <td>".$row['organization']."</td>
                            <td>".$row['candidate_name']."</td>
                            <td>".$row['vote_count']."</td>
                            </tr>";
                        $rank++;
                    }
                }

                // Array of positions
                $positions = [
                    'President', 
                    'Vice President', 
                    'Secretary', 
                    'Treasurer', 
                    'Auditor', 
                    'P.R.O.', 
                    'Business Manager', 
                    'Dir. for Special Project', 
                    'BSED Representative', 
                    'BEED Representative', 
                    'BSOAD Representative', 
                    'BSHM Representative', 
                    'BS CRIM Representative', 
                    'BSIT Representative'
                ];

                $organizationFilter = !empty($_GET['organization']) ? " AND voters1.organization = '".$_GET['organization']."'" : "";

                // Loop through each position and display the ranking box
                foreach ($positions as $position) {
                    echo "
                    <div class='col-md-12'>
                        <div class='box'>
                            <div class='box-header with-border'>
                                <h3 class='box-title text-center'>Ranking of ".$position." Candidates</h3>
                            </div>
                            <div class='box-body'>
                                <table class='table table-bordered'>
                                    <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Organization</th>
                                        <th>Candidate</th>
                                        <th>Vote Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>";
                    displayRankingTable($conn, $position, $organizationFilter);
                    echo "
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>";
                }
                ?>
                <!-- Export Button -->
                <div class="row">
                    <div class="col-xs-12">
                        <span class="pull-right">
                            <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
</body>
</html>
