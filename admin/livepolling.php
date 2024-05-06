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
            <!-- Chart -->
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <button id="change-chart">Change to Classic</button>
            <br><br>
            <div id="chart_div" style="width: 800px; height: 500px;"></div>

            <script>
                google.charts.load('current', {'packages':['corechart', 'bar']});
                google.charts.setOnLoadCallback(drawStuff);

                function drawStuff() {
                    var button = document.getElementById('change-chart');
                    var chartDiv = document.getElementById('chart_div');

                    var data = google.visualization.arrayToDataTable([
                        ['Candidate', 'Votes'],
                        <?php
                        // Fetch and display vote counts
                        $result = $conn->query("SELECT candidate_name, vote_count FROM candidates");
                        while($row = $result->fetch_assoc()){
                            echo "['" . $row['candidate_name'] . "', " . $row['vote_count'] . "],";
                        }
                        ?>
                    ]);

                    var materialOptions = {
                        width: 900,
                        chart: {
                            title: 'Election Results',
                            subtitle: 'Vote Counts'
                        },
                        bars: 'vertical' // vertical bars
                    };

                    var classicOptions = {
                        width: 900,
                        chart: {
                            title: 'Election Results',
                            subtitle: 'Vote Counts'
                        },
                        bars: 'horizontal' // horizontal bars
                    };

                    var currentOptions = materialOptions; // Start with Material Design options

                    var currentChart; // To hold the reference to the currently drawn chart

                    function drawChart() {
                        currentChart = new google.charts.Bar(chartDiv);
                        currentChart.draw(data, google.charts.Bar.convertOptions(currentOptions));
                    }

                    function toggleChart() {
                        if (currentOptions === materialOptions) {
                            currentOptions = classicOptions;
                            button.innerText = 'Change to Material';
                        } else {
                            currentOptions = materialOptions;
                            button.innerText = 'Change to Classic';
                        }
                        drawChart();
                    }

                    button.onclick = toggleChart;

                    drawChart(); // Draw initial chart
                }
            </script>

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
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                    return Math.round(e.value);
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