<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <!-- Add the style block to center the box titles and style the back to top button -->
    <style>
        .box-title {
            text-align: center;
            width: 100%;
            display: inline-block;
        }

        /* Back to Top button styles */
        #back-to-top {
            position: fixed;
            bottom: 40px;
            right: 40px;
            display: none;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 22px;
            line-height: 50px;
            cursor: pointer;
            z-index: 1000;
        }

        #back-to-top:hover {
            background-color: #555;
        }
    </style>
</head>
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
            <div class="row justify-content-center">
                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="businessManagerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="beedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bshmRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsoadRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bscrimRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 offset-md-1">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
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

    // Back to top button script
    $(document).ready(function() {
        var btn = $('#back-to-top');
        
        $(window).scroll(function() {
            if ($(window).scrollTop() > 100) {
                btn.fadeIn();
            } else {
                btn.fadeOut();
            }
        });
        
        btn.click(function() {
            $('html, body').animate({scrollTop: 0}, '100');
            return false;
        });
    });
</script>

<!-- Back to Top Button -->
<button id="back-to-top" title="Back to Top">&uarr;</button>

</body>
</html>
