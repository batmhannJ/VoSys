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

            <!-- Bar Graphs for President, Vice Presidents, and Secretary -->
            <div class="row">
                <!-- President Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="presidentGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Vice President for Internal Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for Internal Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="vicePresidentInternalGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Vice President for External Affairs Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for External Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="vicePresidentExternalGraph" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Secretary Bar Graph Box -->
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Secretary Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <canvas id="secretaryGraph" style="height: 300px;"></canvas>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <span class="pull-right">
                                    <a href="export_results.php?organization=<?php echo $_GET['organization'] ?? ''; ?>" class="btn btn-success btn-sm btn-flat"><span class="glyphicon glyphicon-print"></span> Export PDF</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var presidentChart, vicePresidentInternalChart, vicePresidentExternalChart, secretaryChart;

    function createChart(ctx, data) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Vote Count',
                    data: data.map(item => item.y),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function updateChart(chart, data) {
        chart.data.labels = data.map(item => item.label);
        chart.data.datasets[0].data = data.map(item => item.y);
        chart.update();
    }

    function fetchData() {
        $.ajax({
            url: 'update_data.php?organization=<?php echo $_GET['organization'] ?? ''; ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (presidentChart) {
                    updateChart(presidentChart, response.president);
                    updateChart(vicePresidentInternalChart, response.vicePresidentInternal);
                    updateChart(vicePresidentExternalChart, response.vicePresidentExternal);
                    updateChart(secretaryChart, response.secretary);
                } else {
                    var presidentCtx = document.getElementById('presidentGraph').getContext('2d');
                    var vicePresidentInternalCtx = document.getElementById('vicePresidentInternalGraph').getContext('2d');
                    var vicePresidentExternalCtx = document.getElementById('vicePresidentExternalGraph').getContext('2d');
                    var secretaryCtx = document.getElementById('secretaryGraph').getContext('2d');

                    presidentChart = createChart(presidentCtx, response.president);
                    vicePresidentInternalChart = createChart(vicePresidentInternalCtx, response.vicePresidentInternal);
                    vicePresidentExternalChart = createChart(vicePresidentExternalCtx, response.vicePresidentExternal);
                    secretaryChart = createChart(secretaryCtx, response.secretary);
                }
            }
        });
    }

    $(document).ready(function() {
        fetchData();
        setInterval(fetchData, 5000); // Fetch data every 5 seconds
    });
</script>
</body>
</html>
