<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/header_csc.php'; ?>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_csc.php'; ?>
  <?php include 'includes/menubar_csc.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM categories WHERE election_id = 20";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>

              <p>Positions</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="positions_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
          <!-- small box -->
          <!--<div class="small-box">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM categories WHERE election_id = 20";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>

              <p>No. of Positions</p>
            </div>
            <div class="icon">
              <i class="fa fa-tasks"></i>
            </div>
            <a href="positions.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>-->
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM candidates WHERE election_id = 20";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
          
              <p>Candidates</p>
            </div>
            <div class="icon">
              <i class="fa fa-black-tie"></i>
            </div>
            <a href="candidates_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box">
            <div class="inner">
              <?php
                $sql = "SELECT * FROM voters";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
             
              <p>Total Voters</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="voters_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box">
            <div class="inner">
            <?php
              $sql = "SELECT COUNT(DISTINCT voters_id) AS total_voters
              FROM votes_csc
              WHERE voters_id NOT IN (SELECT voters_id FROM voters WHERE archived = 1)";      
              $query = $conn->query($sql);
              $row = $query->fetch_assoc();
              $totalVoters = $row['total_voters'];

              echo "<h3>".$totalVoters."</h3>";
              ?>


              <p>Voters Voted</p>
            </div>
            <div class="icon">
              <i class="fa fa-edit"></i>
            </div>
            <a href="votersVoted_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box">
            <div class="inner">
              <?php
                $sql = "SELECT voters.id, voters.lastname
        FROM voters
        LEFT JOIN votes_csc ON voters.id = votes_csc.voters_id
        WHERE votes_csc.voters_id IS NULL";
                $query = $conn->query($sql);

                echo "<h3>".$query->num_rows."</h3>";
              ?>
          
              <p>Remaining Voters</p>
            </div>
            <div class="icon">
              <i class="fa fa-black-tie"></i>
            </div>
            <a href="remainingVoters_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box">
            <div class="inner">
            <?php
              $sql = "SELECT * 
                      FROM votes_csc 
                      JOIN voters ON votes_csc.voters_id = voters.id
                      GROUP BY votes_csc.voters_id";
              $query = $conn->query($sql);

              $totalRows = $query->num_rows; // Total number of rows fetched
              // Assuming you have a database connection, replace 'your_db_connection' with your actual database connection variable
              $sql_count_voters = "SELECT COUNT(*) AS total_voters FROM voters";
              $result_count_voters = $conn->query($sql_count_voters);
              $row_count_voters = $result_count_voters->fetch_assoc();
              $totalNumberOfVoters = $row_count_voters['total_voters'];
              // Assuming this is the total number of voters in your database

              // Display each row and calculate the percentage
              /*while ($row = $query->fetch_assoc()) {
                  // Display your data here
                  // For example:
                  echo "Voter ID: " . $row['voters_id'] . ", Last Name: " . $row['lastname'] . "<br>";
              }*/

              // Calculate and display the percentage
              if ($totalNumberOfVoters > 0) {
                  $percentage = ($totalRows / $totalNumberOfVoters) * 100;
                  $percentage = number_format($percentage, 1);
                  echo "<h3>" . $percentage . "%" ."</h3>";
              } else {
                  echo "Total number of voters is 0. Cannot calculate percentage.";
              }
              ?>

              <p>Voters Turnout</p>
            </div>
            <div class="icon">
              <i class="fa fa-black-tie"></i>
            </div>
            <a href="turnout_csc.php" class="small-box-footer_csc">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

      <!--<div class="row">
        <div class="col-xs-12">
          <h3>Votes Tally
            <span class="pull-right">
              <a href="print.php" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Print</a>
            </span>
          </h3>
        </div>
      </div>

      <?php
        $sql = "SELECT * FROM categories WHERE election_id = 20 ORDER BY priority ASC";
        $query = $conn->query($sql);
        $inc = 2;
        while($row = $query->fetch_assoc()){
          $inc = ($inc == 2) ? 1 : $inc+1; 
          if($inc == 1) echo "<div class='row'>";
          echo "
            <div class='col-sm-6'>
              <div class='box box-solid'>
                <div class='box-header with-border'>
                  <h4 class='box-title'><b>".$row['name']."</b></h4>
                </div>
                <div class='box-body'>
                  <div class='chart'>
                    <canvas id='".slugify($row['name'])."' style='height:200px'></canvas>
                  </div>
                </div>
              </div>
            </div>
          ";
          if($inc == 2) echo "</div>";  
        }
        if($inc == 1) echo "<div class='col-sm-6'></div></div>";
      ?>

      </section>
      right col -->
    </div>
  	<?php include 'includes/footer.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<?php
  $sql = "SELECT * FROM positions ORDER BY priority ASC";
  $query = $conn->query($sql);
  while($row = $query->fetch_assoc()){
    $sql = "SELECT * FROM candidates WHERE position_id = '".$row['id']."'";
    $cquery = $conn->query($sql);
    $carray = array();
    $varray = array();
    while($crow = $cquery->fetch_assoc()){
      array_push($carray, $crow['lastname']);
      $sql = "SELECT * FROM votes WHERE candidate_id = '".$crow['id']."'";
      $vquery = $conn->query($sql);
      array_push($varray, $vquery->num_rows);
    }
    $carray = json_encode($carray);
    $varray = json_encode($varray);
    ?>
    <script>
    $(function(){
      var rowid = '<?php echo $row['id']; ?>';
      var description = '<?php echo slugify($row['description']); ?>';
      var barChartCanvas = $('#'+description).get(0).getContext('2d')
      var barChart = new Chart(barChartCanvas)
      var barChartData = {
        labels  : <?php echo $carray; ?>,
        datasets: [
          {
            label               : 'Votes',
            fillColor           : 'rgba(60,141,188,0.9)',
            strokeColor         : 'rgba(60,141,188,0.8)',
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : <?php echo $varray; ?>
          }
        ]
      }
      var barChartOptions                  = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero        : true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines      : true,
        //String - Colour of the grid lines
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth      : 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines  : true,
        //Boolean - If there is a stroke on each bar
        barShowStroke           : true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth          : 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing         : 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing       : 1,
        //String - A legend template
        legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to make the chart responsive
        responsive              : true,
        maintainAspectRatio     : true
      }

      barChartOptions.datasetFill = false
      var myChart = barChart.HorizontalBar(barChartData, barChartOptions)
      //document.getElementById('legend_'+rowid).innerHTML = myChart.generateLegend();
    });
    </script>
    <?php
  }
?>
</body>
<style>
  .small-box-footer_csc{
    border-bottom-right-radius:20px;
    border-bottom-left-radius:20px;
    position:relative;
    text-align:center;
    padding:3px 0;
    color:#0000ff;
    display:block;
    z-index:10;
    background:rgba(0,0,0,0.1);
    text-decoration:none
  }
  .small-box-footer_csc:hover{
    color: #fff;
    background-color: black;
  }
</style>
</html>
