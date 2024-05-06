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
                        // Fetch and display candidate names and their corresponding vote counts
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
</body>
</html>
