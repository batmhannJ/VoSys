<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar_csc.php'; ?>
    <?php include 'includes/menubar_csc.php'; ?>

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
            <!-- Ranking Boxes for Various Positions -->
            <div class="row">
                <?php
                // Function to display ranking table
                function displayRankingTable($conn, $position) {
                    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes_csc.candidate_id), 0) AS vote_count
                            FROM categories 
                            LEFT JOIN candidates ON categories.id = candidates.category_id
                            LEFT JOIN votes_csc ON candidates.id = votes_csc.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes_csc.voters_id 
                            WHERE categories.name = ? AND voters1.organization != ''
                            GROUP BY candidates.firstname, candidates.lastname
                            ORDER BY vote_count DESC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $position);
                    $stmt->execute();
                    $query = $stmt->get_result();
                    $rank = 1;
                    while($row = $query->fetch_assoc()){
                        echo "
                            <tr>
                                <td>".$rank."</td>
                                <td>".$row['candidate_name']."</td>
                                <td>".$row['vote_count']."</td>
                            </tr>";
                        $rank++;
                    }
                    $stmt->close();
                }

                // Array of positions
                $positions = [
                    'President', 
                    'Vice President', 
                    'Secretary', 
                    'Treasurer', 
                    'Auditor', 
                    'P.R.O',
                    'Business Manager',
                    'BEED Rep',
                    'BSED Rep',
                    'BSHM Rep',
                    'BSOAD Rep',
                    'BS CRIM Rep',
                    'BSIT Rep'
                ];

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
                                        <th>Candidate</th>
                                        <th>Vote Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>";
                    displayRankingTable($conn, $position);
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
                            <a href="export_results_csc.php" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
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
