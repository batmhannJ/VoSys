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

            <!-- President and Vice President Ranking Boxes -->
            <div class="row">
                <!-- President Ranking List Box -->
                <div class="col-md-6">
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
                                $organizationFilter = !empty($_GET['organization']) ? " AND voters.organization = '".$_GET['organization']."'" : "";
                                $sql = "SELECT voters.organization, CONCAT(voters.firstname, ' ', voters.lastname) AS candidate_name, 
                                        COUNT(votes.voters_id) AS vote_count
                                        FROM votes 
                                        LEFT JOIN voters ON votes.voters_id = voters.id
                                        WHERE voters.position = 'President'".$organizationFilter."
                                        GROUP BY voters.organization, votes.voters_id
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

                <!-- Vice President Ranking List Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Vice President Candidates</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Ranking Table -->
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
                                // Fetch and display vice president candidate ranking based on vote count and organization filter
                                $sql = "SELECT voters.organization, CONCAT(voters.firstname, ' ', voters.lastname) AS candidate_name, 
                                        COUNT(votes.voters_id) AS vote_count
                                        FROM votes 
                                        LEFT JOIN voters ON votes.voters_id = voters.id
                                        WHERE voters.position = 'Vice President'".$organizationFilter."
                                        GROUP BY voters.organization, votes.voters_id
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

            <!-- Bar Graphs for President and Vice President -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
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

                <!-- Vice President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President Candidates Vote Count</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Vice President Bar Graph Container -->
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="pull-right">
                              <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span
                              <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                            </span>
                        </div>
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
    $sql = "SELECT CONCAT(voters.firstname, ' ', voters.lastname) AS candidate_name, 
            COUNT(votes.voters_id) AS vote_count
            FROM votes 
            LEFT JOIN voters ON votes.voters_id = voters.id
            WHERE voters.position = 'President'
            ".$organizationFilter."
            GROUP BY votes.voters_id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $presidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>

    // Generate president bar graph
    generateBarGraph(<?php echo json_encode($presidentData); ?>, "presidentGraph");

    // Fetch and process vice president data
    <?php
    $vicePresidentData = array();
    $sql = "SELECT CONCAT(voters.firstname, ' ', voters.lastname) AS candidate_name, 
            COUNT(votes.voters_id) AS vote_count
            FROM votes 
            LEFT JOIN voters ON votes.voters_id = voters.id
            WHERE voters.position = 'Vice President'
            ".$organizationFilter."
            GROUP BY votes.voters_id";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $vicePresidentData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name']);
    }
    ?>

    // Generate vice president bar graph
    generateBarGraph(<?php echo json_encode($vicePresidentData); ?>, "vicePresidentGraph");
</script>
</body>
</html>
