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
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(presidentData, vicePresidentData) {
        var presidentBars = [];
        var vicePresidentBars = [];
        var organizations = <?php echo json_encode($organizations); ?>; // Assuming you have fetched organizations data

        // Generate president bars
        for (var i = 0; i < presidentData.length; i++) {
            var dataPoints = [];
            for (var j = 0; j < presidentData[i].candidates.length; j++) {
                var candidate = presidentData[i].candidates[j];
                dataPoints.push({
                    y: candidate.vote_count,
                    label: candidate.firstname + ' ' + candidate.lastname,
                    indexLabel: candidate.organization,
                    color: organizations.indexOf(candidate.organization) !== -1 ? getColor(organizations.indexOf(candidate.organization)) : null
                });
            }
            presidentBars.push({
                type: "bar",
                showInLegend: true,
                name: presidentData[i].position,
                dataPoints: dataPoints
            });
        }

        // Generate vice president bars
        for (var i = 0; i < vicePresidentData.length; i++) {
            var dataPoints = [];
            for (var j = 0; j < vicePresidentData[i].candidates.length; j++) {
                var candidate = vicePresidentData[i].candidates[j];
                dataPoints.push({
                    y: candidate.vote_count,
                    label: candidate.firstname + ' ' + candidate.lastname,
                    indexLabel: candidate.organization,
                    color: organizations.indexOf(candidate.organization) !== -1 ? getColor(organizations.indexOf(candidate.organization)) : null
                });
            }
            vicePresidentBars.push({
                type: "bar",
                showInLegend: true,
                name: vicePresidentData[i].position,
                dataPoints: dataPoints
            });
        }

        var chart = new CanvasJS.Chart("presidentGraph", {
            animationEnabled: true,
            title:{
                text: "President Candidates Vote Count"
            },
            axisY: {
                title: "Vote Count"
            },
            axisX: {
                title: "Candidates"
            },
            data: presidentBars
        });
        chart.render();

        var chart2 = new CanvasJS.Chart("vicePresidentGraph", {
            animationEnabled: true,
            title:{
                text: "Vice President Candidates Vote Count"
            },
            axisY: {
                title: "Vote Count"
            },
            axisX: {
                title: "Candidates"
            },
            data: vicePresidentBars
        });
        chart2.render();
    }

    // Function to fetch updated data from the server
    function updateData() {
        $.ajax({
            url: 'update_data.php', // Change this to the URL of your update data script
            type: 'GET',
            dataType: 'json',
            data: {organization: $('#organization').val()}, // Pass the selected organization to the server
            success: function(response) {
                generateBarGraph(response.presidentData, response.vicePresidentData);
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

    // Function to get color based on index
    function getColor(index) {
        var colors = ["#FF5733", "#33FF57", "#5733FF", "#33A8FF", "#FF33A8", "#A8FF33", "#33FFA8", "#A833FF", "#FFA833", "#8FFFA8"];
        return colors[index % colors.length];
    }
</script>

</body>
</html>
