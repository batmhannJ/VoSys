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
                <tbody id="presidentTableBody">
                  <!-- This will be filled dynamically via AJAX -->
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
                <tbody id="vicePresidentTableBody">
                  <!-- This will be filled dynamically via AJAX -->
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

  // Function to update president and vice president tables and graphs
  function updateData() {
    // AJAX to fetch updated data
    $.ajax({
      url: 'fetch_data.php', // Replace with the PHP script to fetch data from the server
      method: 'GET',
      data: { organization: $('#organization').val() }, // Send organization filter if selected
      dataType: 'json',
      success: function(data) {
        // Update president table
        $('#presidentTableBody').html(data.presidentTable);
        // Update vice president table
        $('#vicePresidentTableBody').html(data.vicePresidentTable);
        // Generate president graph
        generateBarGraph(data.presidentGraphData, 'presidentGraph');
        // Generate vice president graph
        generateBarGraph(data.vicePresidentGraphData, 'vicePresidentGraph');
      }
    });
  }

  // Call updateData function initially
  updateData();

  // Call updateData function every 10 seconds
  setInterval(updateData, 10000); // Adjust interval as needed
</script>
</body>
</html>
