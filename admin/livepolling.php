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
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId, organization) {
        var color;

        // Set color based on organization
        switch (organization) {
            case 'JPCS':
                color = "#4CAF50"; // Green
                break;
            case 'CSC':
                color = "#000000"; // Black
                break;
            case 'CODE-TG':
                color = "#800000"; // Maroon
                break;
            case 'YMF':
                color = "#00008b"; // Dark Blue
                break;
            case 'HMSO':
                color = "#cba328"; // Gold
                break;
            case 'PASOA':
                color = "#e6cc00"; // Yellow
                break;
            default:
                color = "#000000"; // Default to Black
        }

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisY: {
                 title: "Candidates",
                includeZero: true,
                 labelFormatter: function (e) {
        // Include candidate name and round vote count to whole number
        return dataPoints[e.value].label + " - " + Math.round(e.value);
    }
},

            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "bar", // Change type to "bar"
                dataPoints: dataPoints,
                color: color // Set the color based on organization
            }]
        });
        chart.render();
    }

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                // Update president bar graph with color based on organization
                generateBarGraph(response.presidentData, "presidentGraph", $('#organization').val());

                // Update vice president bar graph with color based on organization
                generateBarGraph(response.vicePresidentData, "vicePresidentGraph", $('#organization').val());

                // Update secretary bar graph with color based on organization
                generateBarGraph(response.secretaryData, "secretaryGraph", $('#organization').val());
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Call the updateData function initially
    updateData();

    // Call the updateData function every 60 seconds (adjust as needed)
    setInterval(updateData, 3000); // 60000 milliseconds = 60 seconds
</script>
</body>
</html>
