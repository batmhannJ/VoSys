<?php
// Include necessary files and initialize database connection
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

            <!-- Bar Graphs for all Positions -->
            <?php
            // Define an array of positions
            $positions = array(
                'President',
                'Vice President for Internal Affairs',
                'Vice President for External Affairs',
                'Secretary',
                'Treasurer',
                'Auditor',
                'P.R.O',
                'Dir. for Membership',
                'Dir. for Special Project',
                'Block A 1st Year Representative',
                'Block B 1st Year Representative',
                'Block A 2nd Year Representative',
                'Block B 2nd Year Representative',
                'Block A 3rd Year Representative',
                'Block B 3rd Year Representative',
                'Block A 4th Year Representative',
                'Block B 4th Year Representative'
            );

            // Iterate over each position and display its bar graph
            foreach ($positions as $position) {
            ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $position; ?> Candidates Vote Count</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- Bar Graph Container for <?php echo $position; ?> -->
                                <div id="<?php echo strtolower(str_replace(' ', '', $position)); ?>Graph" style="height: 300px;"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            <?php } ?>

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
    // Function to generate bar graph for a specific position
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title:{
                text: "Vote Counts"
            },
            axisY: {
                title: "Candidates"
            },
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "bar", // Change type to "bar"
                dataPoints: dataPoints
            }]
        });
        chart.render();
        return chart;
    }

    // Function to update data and graphs for all positions
    function updateDataAndGraphs() {
        $.ajax({
            url: 'update_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Iterate over each position and update its graph
                <?php foreach ($positions as $position) { ?>
                    var positionData = response['<?php echo $position; ?>'];
                    var positionGraphId = "<?php echo strtolower(str_replace(' ', '', $position)); ?>Graph";
                    var positionChart = generateBarGraph(positionData, positionGraphId);
                <?php } ?>
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    // Call the updateDataAndGraphs function initially
    updateDataAndGraphs();

    // Call the updateDataAndGraphs function every 5 seconds
    setInterval(updateDataAndGraphs, 5000);
</script>
</body>
</html>
