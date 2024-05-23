<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar_csc.php'; ?>
    <?php include 'includes/menubar_csc.php'; ?>

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
                            <!-- Select Organization and Filter button removed -->
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
                            <h3 class="box-title">Vice President Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
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
                            <h3 class="box-title">P.R.O Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Business Manager Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="businessManagerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BEED Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="beedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BSED Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="bsedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BSHM Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="bshmRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BSOAD Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="bsoadRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BS CRIM Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="bscrimRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">BSIT Representative Candidates Vote Count</h3>
                        </div>
                        <div class="box-body">
                            <div id="bsitRepGraph" style="height: 300px;"></div>
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
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                presidentChart.options.data[0].dataPoints = response.president;
                presidentChart.render();

                vicePresidentChart.options.data[0].dataPoints = response.vicePresident;
                vicePresidentChart.render();

                secretaryChart.options.data[0].dataPoints = response.secretary;
                secretaryChart.render();

                treasurerChart.options.data[0].dataPoints = response.treasurer;
                treasurerChart.render();

                auditorChart.options.data[0].dataPoints = response.auditor;
                auditorChart.render();

                proChart.options.data[0].dataPoints = response.pro;
                proChart.render();

                businessManagerChart.options.data[0].dataPoints = response.businessManager;
                businessManagerChart.render();

                beedRepChart.options.data[0].dataPoints = response.beedRep;
                beedRepChart.render();

                bsedRepChart.options.data[0].dataPoints = response.bsedRep;
                bsedRepChart.render();

                bshmRepChart.options.data[0].dataPoints = response.bshmRep;
                bshmRepChart.render();

                bsoadRepChart.options.data[0].dataPoints = response.bsoadRep;
                bsoadRepChart.render();

                bscrimRepChart.options.data[0].dataPoints = response.bscrimRep;
                bscrimRepChart.render();

                bsitRepChart.options.data[0].dataPoints = response.bsitRep;
                bsitRepChart.render();
            }
        });
    }

    var presidentChart = generateBarGraph([], "presidentGraph");
    var vicePresidentChart = generateBarGraph([], "vicePresidentGraph");
    var secretaryChart = generateBarGraph([], "secretaryGraph");
    var treasurerChart = generateBarGraph([], "treasurerGraph");
    var auditorChart = generateBarGraph([], "auditorGraph");
    var proChart = generateBarGraph([], "proGraph");
    var businessManagerChart = generateBarGraph([], "businessManagerGraph");
    var beedRepChart = generateBarGraph([], "beedRepGraph");
    var bsedRepChart = generateBarGraph([], "bsedRepGraph");
    var bshmRepChart = generateBarGraph([], "bshmRepGraph");
    var bsoadRepChart = generateBarGraph([], "bsoadRepGraph");
    var bscrimRepChart = generateBarGraph([], "bscrimRepGraph");
    var bsitRepChart = generateBarGraph([], "bsitRepGraph");

    updateVoteCounts();

    setInterval(updateVoteCounts, 5000);
</script>
</body>
</html>
