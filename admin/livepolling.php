<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Election Results</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <form method="get" action="">
                                <div class="form-group">
                                    <label for="organization">Select Organization:</label>
                                    <select class="form-control" name="organization" id="organization" onchange="updateVoteCounts()">
                                        <option value="">All Organizations</option>
                                        <?php
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

            <div class="row">
                <!-- Original positions -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b</h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President for Internal Affairs</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentInternalGraph" style="height: 300px;"style="text-align:center"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President for External Affairs</b> </h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentExternalGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Additional positions -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>P.R.O</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Dir. for Membership</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="dirMembershipGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Dir. for Special Project</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="dirSpecialProjectGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block A 1st Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA1stYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block B 1st Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB1stYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <!-- New positions -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block A 2nd Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA2ndYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block B 2nd Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB2ndYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block A 3rd Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA3rdYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block B 3rd Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB3rdYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block A 4th Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA4thYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Block B 4th Year Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB4thYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function generateBarGraph(dataPoints, containerId) {
        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            title: {
                text: "Vote Counts"
            },
            axisX: {
                title: "Vote Count",
                includeZero: true
            },
            axisY: {
                title: "Candidates"
            },
            data: [{
                type: "bar",
                dataPoints: dataPoints
            }]
        });
        chart.render();
        return chart;
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data.php?organization=' + $('#organization').val(),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                presidentChart.options.data[0].dataPoints = response.president;
                presidentChart.render();

                vicePresidentInternalChart.options.data[0].dataPoints = response.vicePresidentInternal;
                vicePresidentInternalChart.render();

                vicePresidentExternalChart.options.data[0].dataPoints = response.vicePresidentExternal;
                vicePresidentExternalChart.render();

                secretaryChart.options.data[0].dataPoints = response.secretary;
                secretaryChart.render();

                treasurerChart.options.data[0].dataPoints = response.treasurer;
                treasurerChart.render();

                auditorChart.options.data[0].dataPoints = response.auditor;
                auditorChart.render();

                proChart.options.data[0].dataPoints = response.pro;
                proChart.render();

                dirMembershipChart.options.data[0].dataPoints = response.dirMembership;
                dirMembershipChart.render();

                dirSpecialProjectChart.options.data[0].dataPoints = response.dirSpecialProject;
                dirSpecialProjectChart.render();

                blockA1stYearRepChart.options.data[0].dataPoints = response.blockA1stYearRep;
                blockA1stYearRepChart.render();

                blockB1stYearRepChart.options.data[0].dataPoints = response.blockB1stYearRep;
                blockB1stYearRepChart.render();

                blockA2ndYearRepChart.options.data[0].dataPoints = response.blockA2ndYearRep;
                blockA2ndYearRepChart.render();

                blockB2ndYearRepChart.options.data[0].dataPoints = response.blockB2ndYearRep;
                blockB2ndYearRepChart.render();

                blockA3rdYearRepChart.options.data[0].dataPoints = response.blockA3rdYearRep;
                blockA3rdYearRepChart.render();

                blockB3rdYearRepChart.options.data[0].dataPoints = response.blockB3rdYearRep;
                blockB3rdYearRepChart.render();

                blockA4thYearRepChart.options.data[0].dataPoints = response.blockA4thYearRep;
                blockA4thYearRepChart.render();

                blockB4thYearRepChart.options.data[0].dataPoints = response.blockB4thYearRep;
                blockB4thYearRepChart.render();
            }
        });
    }

    var presidentChart = generateBarGraph([], "presidentGraph");
    var vicePresidentInternalChart = generateBarGraph([], "vicePresidentInternalGraph");
    var vicePresidentExternalChart = generateBarGraph([], "vicePresidentExternalGraph");
    var secretaryChart = generateBarGraph([], "secretaryGraph");
    var treasurerChart = generateBarGraph([], "treasurerGraph");
    var auditorChart = generateBarGraph([], "auditorGraph");
    var proChart = generateBarGraph([], "proGraph");
    var dirMembershipChart = generateBarGraph([], "dirMembershipGraph");
    var dirSpecialProjectChart = generateBarGraph([], "dirSpecialProjectGraph");
    var blockA1stYearRepChart = generateBarGraph([], "blockA1stYearRepGraph");
    var blockB1stYearRepChart = generateBarGraph([], "blockB1stYearRepGraph");

    var blockA2ndYearRepChart = generateBarGraph([], "blockA2ndYearRepGraph");
    var blockB2ndYearRepChart = generateBarGraph([], "blockB2ndYearRepGraph");
    var blockA3rdYearRepChart = generateBarGraph([], "blockA3rdYearRepGraph");
    var blockB3rdYearRepChart = generateBarGraph([], "blockB3rdYearRepGraph");
    var blockA4thYearRepChart = generateBarGraph([], "blockA4thYearRepGraph");
    var blockB4thYearRepChart = generateBarGraph([], "blockB4thYearRepGraph");

    updateVoteCounts();

    setInterval(updateVoteCounts, 5000);
</script>
</body>
</html>
