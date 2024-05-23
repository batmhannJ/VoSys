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
                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">President Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for Internal Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentInternalGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vice President for External Affairs Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentExternalGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Secretary Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional positions -->
            <div class="row">
                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Treasurer Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Auditor Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">P.R.O. Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dir. for Membership Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="dirMembershipGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dir. for Special Project Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="dirSpecialProjectGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block A 1st Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockA1stYearRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Block B 1st Year Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="blockB1stYearRepGraph" style="height: 300px;"></div>
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
            title:{
                text: "Vote Counts"
            },
            axisX: {
                title: "Candidates"
            },
            axisY: {
                title: "Vote Count",
                includeZero: true
            },
            data: [{
                type: "column",
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

    updateVoteCounts();

    setInterval(updateVoteCounts, 5000);
</script>
</body>
</html>
