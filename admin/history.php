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
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization">
                                        <option value="">All Organizations</option>
                                        <?php
                                        // Fetch and display organizations
                                        $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
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

            <!-- President Ranking List Box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of President Candidates</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Ranking Table -->
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Organization</th>
                                    <th>Candidate</th>
                                    <th>Vote Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Fetch and display president candidate ranking based on vote count and organization filter
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != ''
                                        AND categories.name = 'President'".$organizationFilter."
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
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- President Bar Graph -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- President Bar Graph Container -->
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Vice President for External Affairs Ranking List Box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Vice President Candidates for External Affairs</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for External Affairs Ranking Table -->
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Organization</th>
                                    <th>Candidate</th>
                                    <th>Vote Count</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Fetch and display vice president candidate ranking for external affairs based on vote count and organization filter
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != ''
                                        AND categories.name = 'Vice President for External Affairs'".$organizationFilter."
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
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Vice President for External Affairs Bar Graph -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President Candidates for External Affairs Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President for External Affairs Bar Graph Container -->
<div id="vicePresidentExternalGraph" style="height: 300px;"></div>
</div>
<!-- /.box-body -->
</div>
<!-- /.box -->
</div>
<!-- /.col -->
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
<!-- Bar Graph Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisX: {
                title: "Candidates"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "column",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    // Fetch and process president data
    <?php
    $presidentData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != ''
            AND categories.name = 'President'".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>

    // Generate president bar graph
    generateBarGraph(<?php echo json_encode($presidentData); ?>, "presidentGraph");

    // Fetch and process vice president data for external affairs
    <?php
    $vicePresidentExternalData = array();
    $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
            FROM categories 
            LEFT JOIN candidates ON categories.id = candidates.category_id
            LEFT JOIN votes ON candidates.id = votes.candidate_id
            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
            WHERE voters1.organization != ''
            AND categories.name = 'Vice President for External Affairs'".$organizationFilter."
            GROUP BY candidates.id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $vicePresidentExternalData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>

    // Generate vice president for external affairs bar graph
    generateBarGraph(<?php echo json_encode($vicePresidentExternalData); ?>, "vicePresidentExternalGraph");
</script>
</body>
</html>
