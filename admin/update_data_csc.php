<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header.php';
?>
<head>
    <style>
        .center-title {
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>CSC Election Results</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Results</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <!-- CSC Positions -->
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>P.R.O</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="businessManagerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="beedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bshmRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsoadRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BS Crim Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsCrimRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title center-title"><b>BSIT Representative</b></h3>
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
            title: {
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
                type: "bar",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data_csc.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                generateBarGraph(response.president, "presidentGraph");
                generateBarGraph(response.vicePresident, "vicePresidentGraph");
                generateBarGraph(response.secretary, "secretaryGraph");
                generateBarGraph(response.treasurer, "treasurerGraph");
                generateBarGraph(response.auditor, "auditorGraph");
                generateBarGraph(response.pro, "proGraph");
                generateBarGraph(response.businessManager, "businessManagerGraph");
                generateBarGraph(response.beedRep, "beedRepGraph");
                generateBarGraph(response.bsedRep, "bsedRepGraph");
                generateBarGraph(response.bshmRep, "bshmRepGraph");
                generateBarGraph(response.bsoadRep, "bsoadRepGraph");
                generateBarGraph(response.bsCrimRep, "bsCrimRepGraph");
                generateBarGraph(response.bsitRep, "bsitRepGraph");
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data: ' + error);
            }
        });
    }

    $(document).ready(function() {
        updateVoteCounts();
        setInterval(updateVoteCounts, 5000);
    });
</script>
</body>
</html>
