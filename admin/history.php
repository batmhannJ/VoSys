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

            <!-- President, Vice Presidents, and Secretary Ranking Boxes -->
            <div class="row">
                <!-- President Ranking List Box -->
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of President Candidates</h3>
                        </div>
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
                                $organizationFilter = !empty($_GET['organization']) ? " AND voters1.organization = '".$_GET['organization']."'" : "";
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != '' AND categories.name = 'President'".$organizationFilter."
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
                    </div>
                </div>

                <!-- Vice President for Internal Affairs Ranking List Box -->
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Vice President for Internal Affairs Candidates</h3>
                        </div>
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
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != '' AND categories.name = 'Vice President for Internal Affairs'".$organizationFilter."
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
                    </div>
                </div>

                <!-- Vice President for External Affairs Ranking List Box -->
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Vice President for External Affairs Candidates</h3>
                        </div>
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
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != '' AND categories.name = 'Vice President for External Affairs'".$organizationFilter."
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
                                        <td>".$row['vote_count']."</td>";
                                    $rank++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Secretary Ranking List Box -->
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ranking of Secretary Candidates</h3>
                        </div>
                        <div class="box-body">
                            <!-- Secretary Ranking Table -->
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
                                // Fetch and display secretary candidate ranking based on vote count and organization filter
                                $sql = "SELECT voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                                        COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                                        FROM categories 
                                        LEFT JOIN candidates ON categories.id = candidates.category_id
                                        LEFT JOIN votes ON candidates.id = votes.candidate_id
                                        LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                                        WHERE voters1.organization != '' AND categories.name = 'Secretary'".$organizationFilter."
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
                                        <td>".$row['vote_count']."</td>";
                                    $rank++;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-right">
                                    <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                                </span>
                            </div>
                        </div>
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
