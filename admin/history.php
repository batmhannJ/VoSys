<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
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
              <form method="post" action="">
                <div class="form-group">
                  <label for="organization">Select Organization:</label>
                  <select class="form-control" name="organization" id="organization">
                    <option value="">All Organizations</option>
                    <?php
                    // Fetch and display organizations
                    $organizationQuery = $conn->query("SELECT DISTINCT organization FROM voters");
                    while($organizationRow = $organizationQuery->fetch_assoc()){
                        $selected = '';
                        if(isset($_POST['organization']) && $_POST['organization'] == $organizationRow['organization']) {
                            $selected = 'selected';
                        }
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
        <!-- President and Vice President Ranking List Box -->
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Ranking of Candidates</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- President and Vice President Ranking Table -->
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Position</th>
                    <th>Rank</th>
                    <th>Organization</th>
                    <th>Candidate</th>
                    <th>Vote Count</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // Fetch and display president and vice president candidate ranking based on vote count and organization filter
                    if(isset($_POST['organization']) && $_POST['organization'] != "") {
                      $organization = $_POST['organization'];
                      $organizationFilter = "AND voters1.organization = '$organization'";
                    } else {
                      $organizationFilter = "";
                    }
                    $sql = "SELECT positions.description AS position, voters1.organization, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
                            COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
                            FROM positions 
                            LEFT JOIN candidates ON positions.id = candidates.position_id
                            LEFT JOIN votes ON candidates.id = votes.candidate_id
                            LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
                            WHERE voters1.organization != '' $organizationFilter
                            GROUP BY positions.description, voters1.organization, candidates.id
                            ORDER BY position, vote_count DESC";
                    $query = $conn->query($sql);
                    $rank = 1;
                    while($row = $query->fetch_assoc()){
                      echo "
                        <tr>
                          <td>".$row['position']."</td>
                          <td>".$rank."</td>
                          <td>".$row['organization']."</td>
                          <td>".$row['candidate_name']."</td>
                          <td>".$row['vote_count']."</td>
                        </tr>
                      ";
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

      <!-- Bar Graph for Candidates per Organization -->
      <div class="row">
        <!-- Bar Graph Box -->
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Candidates Vote Count per Organization</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Bar Graph Container -->
              <div id="candidatesGraph" style="height: 300px;"></div>
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
  // Function to generate bar graph for candidates per organization
  function generateBarGraph(dataPoints, containerId) {
    var chart = new CanvasJS.Chart(containerId, {
      animationEnabled: true,
      title:{
        text: "Vote Counts for Candidates"
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

  // Fetch and process candidate data per organization
  <?php
    if(isset($_POST['organization']) && $_POST['organization'] != "") {
      $organization = $_POST['organization'];
      $candidateData = array();
      $sql = "SELECT positions.description AS position, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
              COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
              FROM positions 
              LEFT JOIN candidates ON positions.id = candidates.position_id
              LEFT JOIN votes ON candidates.id = votes.candidate_id
              LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
              WHERE voters1.organization = '$organization'
              GROUP BY positions.description, candidates.id";
      $query = $conn->query($sql);
      while($row = $query->fetch_assoc()) {
        $candidateData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name'] . " (" . $row['position'] . ")");
      }
    } else {
      // Fetch all candidate data if no organization is selected
      $candidateData = array();
      $sql = "SELECT positions.description AS position, CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
              COALESCE(COUNT(votes.candidate_id), 0) AS vote_count
              FROM positions 
              LEFT JOIN candidates ON positions.id = candidates.position_id
              LEFT JOIN votes ON candidates.id = votes.candidate_id
              LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
              WHERE voters1.organization != ''
              GROUP BY positions.description, candidates.id";
      $query = $conn->query($sql);
      while($row = $query->fetch_assoc()) {
        $candidateData[] = array("y" => intval($row['vote_count']), "label" => $row['candidate_name'] . " (" . $row['position'] . ")");
      }
    }
  ?>

  // Generate bar graph for candidates per organization
  generateBarGraph(<?php echo json_encode($candidateData); ?>, "candidatesGraph");
</script>
</body>
</html>
