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
          <div class="col-xs-6">
            <div id="representativeChart" style="height: 370px; width: 100%; margin-left: 20px; margin-top: 20px; display: inline-block;"></div>
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
    "PASOA": "#fff080",
    "CSC": "#595959",
    "YMF": "#00008b",
    "CODE-TG": "#800000",
    "HMSO": "#fff080"
  };

  function updateCharts() {
    var organization = document.getElementById("organization").value;

    updatePresidentChart(organization);
    updateRepresentativeChart(organization);
  }

  function updatePresidentChart(organization) {
    var dataPoints = [];

    // You can fetch data dynamically based on the selected organization here
    // For demonstration, I'm using static data for each organization

    if (organization === "JPCS") {
      dataPoints = [
        { y: 20, label: "President" },
        { y: 30, label: "Vice President" },
        { y: 40, label: "Secretary" }
      ];
    } else if (organization === "PASOA") {
      dataPoints = [
        { y: 25, label: "President" },
        { y: 35, label: "Vice President" },
        { y: 45, label: "Secretary" }
      ];
    } else if (organization === "CSC") {
      dataPoints = [
        { y: 35, label: "President" },
        { y: 25, label: "Vice President" },
        { y: 40, label: "Secretary" }
      ];
    } else if (organization === "YMF") {
      dataPoints = [
        { y: 30, label: "President" },
        { y: 40, label: "Vice President" },
        { y: 30, label: "Secretary" }
      ];
    } else if (organization === "CODE-TG") {
      dataPoints = [
        { y: 20, label: "President" },
        { y: 30, label: "Vice President" },
        { y: 50, label: "Secretary" }
      ];
    } else if (organization === "HMSO") {
      dataPoints = [
        { y: 15, label: "President" },
        { y: 35, label: "Vice President" },
        { y: 50, label: "Secretary" }
      ];
    }
    // Add more else if conditions for other organizations

    var chart = new CanvasJS.Chart("presidentChart", {
      title: { text: "President, Vice President, Secretary" },
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
  }

  function updateRepresentativeChart(organization) {
    var dataPoints = [];

    // You can fetch data dynamically based on the selected organization here
    // For demonstration, I'm using static data for each organization

    if (organization === "JPCS") {
      dataPoints = [
        { y: 30, label: "Representative 1" },
        { y: 40, label: "Representative 2" }
      ];
    } else if (organization === "PASOA") {
      dataPoints = [
        { y: 20, label: "Representative 1" },
        { y: 30, label: "Representative 2" }
      ];
    } else if (organization === "CSC") {
      dataPoints = [
        { y: 25, label: "Representative 1" },
        { y: 35, label: "Representative 2" }
      ];
    } else if (organization === "YMF") {
      dataPoints = [
        { y: 30, label: "Representative 1" },
        { y: 40, label: "Representative 2" }
      ];
    } else if (organization === "CODE-TG") {
      dataPoints = [
        { y: 20, label: "Representative 1" },
        { y: 30, label: "Representative 2" }
      ];
    } else if (organization === "HMSO") {
      dataPoints = [
        { y: 15, label: "Representative 1" },
        { y: 25, label: "Representative 2" }
      ];
    }
    // Add more else if conditions for other organizations

    var chart = new CanvasJS.Chart("representativeChart", {
      title: { text: "Representatives" },
      data: [{
        type: "bar",
        dataPoints: dataPoints,
        color: organizationColors[organization] // Set organization-specific color
      }]
    });

    chart.render();

    // Update chart every second
    setInterval(function () {
      updateRepresentativeDataPoints(organization, chart);
    }, 1000);
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

  function updateRepresentativeDataPoints(organization, chart) {
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
