<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
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
          <div class="box-header with-border">
            
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-3"> <!-- Half width for organization dropdown -->
                <div class="form-group">
                  <label for="organization">Select Organization:</label>
                  <select class="form-control smaller-dropdown" id="organization" onchange="updateCharts()">
                    <option value="JPCS">JPCS</option>
                    <option value="PASOA">PASOA</option>
                    <option value="CSC">CSC</option>
                    <option value="YMF">YMF</option>
                    <option value="CODE-TG">CODE-TG</option>
                    <option value="HMSO">HMSO</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="col-xs-6">
            <div id="presidentChart" style="height: 370px; width: 100%; margin-left: 20px; margin-top: 20px; display: inline-block;"></div>
          </div>
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
  var organizationColors = {
    "JPCS": "#ffcc00",
    "PASOA": "#339966",
    "CSC": "#000000",
    "YMF": "#6666ff",
    "CODE-TG": "#cc99ff",
    "HMSO": "#ff9933"
  };

  function updateCharts() {
    var organization = document.getElementById("organization").value;

    updatePresidentChart(organization);
  }

  function updatePresidentChart(organization) {
    // Fetch data from votes.php
    fetch('votes.php')
      .then(response => response.json())
      .then(data => {
        var filteredData = data.filter(item => item.organization === organization);
        var dataPoints = filteredData.map(candidate => ({ y: candidate.votes, label: candidate.candidate + ' - ' + candidate.position }));

        var chart = new CanvasJS.Chart("presidentChart", {
          title: { text: "Candidates and Positions" },
          data: [{
            type: "bar",
            dataPoints: dataPoints,
            color: organizationColors[organization] // Set organization-specific color
          }]
        });

        chart.render();

        // Update chart every second
        setInterval(function () {
          updatePresidentDataPoints(organization, chart);
        }, 1000);
      });
  }

  function updatePresidentDataPoints(organization, chart) {
    // Update dataPoints based on the selected organization
    // For demonstration, I'm using random values for each data point
    var newDataPoints = [];
    for (var i = 0; i < chart.options.data[0].dataPoints.length; i++) {
      newDataPoints.push({ label: chart.options.data[0].dataPoints[i].label, y: Math.random() * 100 });
    }
    chart.options.data[0].dataPoints = newDataPoints;
    chart.render();
  }

  window.onload = function () {
    updateCharts();
  };
</script>
</body>
</html>
