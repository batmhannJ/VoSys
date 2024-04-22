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
        Live Polling
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Live Polling </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div id="presidentChart" style="height: 370px; width: 100%; margin-left: 20px; margin-top: 20px; display: inline-block;"></div>
        </div>
      </div>
    </section>
  </div>
  <?php include 'includes/footer.php'; ?>
</div>
<!-- ./wrapper -->
<?php include 'includes/scripts.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
<script>
  $(document).ready(function() {
    function fetchData() {
      $.ajax({
        url: 'votes.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          updatePresidentChart(data);
        },
        error: function(xhr, status, error) {
          console.error('Error fetching data:', error);
        }
      });
    }

    function updatePresidentChart(data) {
      var organizationColors = {
        "JPCS": "#ffcc00",
        "PASOA": "#339966",
        "CSC": "#ff5050",
        "YMF": "#6666ff",
        "CODE-TG": "#cc99ff",
        "HMSO": "#ff9933"
      };

      var organization = document.getElementById("organization").value;

      var dataPoints = [];

      data.forEach(function(candidate) {
        dataPoints.push({ y: candidate.votes, label: candidate.name + " (" + candidate.position + ")" });
      });

      var chart = new CanvasJS.Chart("presidentChart", {
        title: { text: "Candidates by Position" },
        data: [{
          type: "bar",
          dataPoints: dataPoints,
          color: organizationColors[organization]
        }]
      });

      chart.render();
    }

    // Fetch data initially
    fetchData();

    // Fetch data every 5 seconds
    setInterval(fetchData, 5000); // Adjust the interval as needed
  });
</script>
</body>
</html>
