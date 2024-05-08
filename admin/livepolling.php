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
              <div class="col-md-12"> <!-- Half width for organization dropdown -->
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
          <div class="col-xs-12">
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
    "JPCS": "#4CAF50",
    "PASOA": "#e6cc00",
    "CSC": "#595959",
    "YMF": "#00008b",
    "CODE-TG": "#800000",
    "HMSO": "#fff080"
  };

  var chart; // Declare the chart variable globally

  function updateCharts() {
    var organization = document.getElementById("organization").value;

    updatePresidentChart(organization);
  }

  function updatePresidentChart(organization) {
    var presidentDataPoints = [];
    var vicePresidentDataPoints = [];

    // Fetch data dynamically based on the selected organization
    // For demonstration, I'm using static data for each organization
    if (organization === "JPCS") {
        presidentDataPoints = [
            { y: 20, label: "President" }
        ];
        vicePresidentDataPoints = [
            { y: 30, label: "Vice President" }
        ];
    }
    // Add more else if conditions for other organizations

    chart = new CanvasJS.Chart("presidentChart", {
        title: { text: "President and Vice President" },
        axisY: {
            title: "Votes"
        },
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            verticalAlign: "top",
            itemclick: function(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                e.chart.render();
            }
        },
        data: [{
            type: "bar",
            showInLegend: true,
            name: "President",
            dataPoints: presidentDataPoints,
            color: organizationColors[organization]
        },
        {
            type: "bar",
            showInLegend: true,
            name: "Vice President",
            dataPoints: vicePresidentDataPoints,
            color: organizationColors[organization]
        }]
    });

    chart.render();

    // Update chart every second
    setInterval(function () {
        updatePresidentDataPoints(organization);
    }, 1000);
}

  function updatePresidentDataPoints(organization) {
    // Update dataPoints based on the selected organization
    // For demonstration, I'm using random values for each data point
    var newPresidentDataPoints = [];
    var newVicePresidentDataPoints = [];
    
    for (var i = 0; i < chart.options.data[0].dataPoints.length; i++) {
      newPresidentDataPoints.push({ label: "President", y: Math.random() * 100 });
    }

    for (var i = 0; i < chart.options.data[1].dataPoints.length; i++) {
      newVicePresidentDataPoints.push({ label: "Vice President", y: Math.random() * 100 });
    }
    
    chart.options.data[0].dataPoints = newPresidentDataPoints;
    chart.options.data[1].dataPoints = newVicePresidentDataPoints;
    chart.render();
  }

  window.onload = function () {
    updateCharts();
  };
</script>
</body>
</html>
