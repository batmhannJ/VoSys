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
        <!-- Combined Bar Graph Box -->
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">President and Vice President Candidates Vote Count</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Combined Bar Graph Container -->
              <div id="combinedGraph" style="height: 300px;"></div>
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
  // Function to generate combined bar graph for president and vice president
  function generateCombinedBarGraph(dataPoints, containerId) {
    var chart = new CanvasJS.Chart(containerId, {
      animationEnabled: true,
      title: {
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
        dataPoints: dataPoints["President"],
        name: "President"
      },
      {
        type: "column",
        dataPoints: dataPoints["Vice President"],
        name: "Vice President"
      }]
    });
    chart.render();
  }

  // Fetch data for president and vice president candidates together
  <?php
  $combinedData = array();
  $sql = "SELECT CONCAT(candidates.firstname, ' ', candidates.lastname) AS candidate_name, 
          COALESCE(COUNT(votes.candidate_id), 0) AS vote_count,
          positions.description AS position
          FROM positions 
          LEFT JOIN candidates ON positions.id = candidates.position_id
          LEFT JOIN votes ON candidates.id = votes.candidate_id
          LEFT JOIN voters AS voters1 ON voters1.id = votes.voters_id 
          WHERE voters1.organization != ''
          GROUP BY candidates.id, positions.description";

  $query = $conn->query($sql);
  while ($row = $query->fetch_assoc()) {
      $combinedData[$row['position']][] = array(
          "y" => intval($row['vote_count']),
          "label" => $row['candidate_name']
      );
  }
  ?>

  // Generate combined bar graph for president and vice president
  generateCombinedBarGraph(<?php echo json_encode($combinedData); ?>, "combinedGraph");
</script>
</body>
</html>
