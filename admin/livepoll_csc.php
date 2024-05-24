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
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="presidentGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentGraph" style="height: 300px;"></div>
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
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="businessManagerGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="beedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsedRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bshmRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsoadRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bscrimRepGraph" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
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
    var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
    
    var chart = new CanvasJS.Chart(containerId, {
        animationEnabled: true,
        animationDuration: 2000, // Animation duration
        
        title: {
            text: "Vote Counts"
        },
        axisX: {
            title: "",
            includeZero: true,
            interval: 1,
            labelFormatter: function() {
                return " ";
            }
        },
        axisY: {
            title: "",
            interval: Math.ceil(totalVotes / 10) // Adjust the Y-axis interval for better scaling
        },
        data: [{
            type: "bar",
            indexLabel: "{label} - {percent}%",
            indexLabelPlacement: "inside",
            indexLabelFontColor: "white",
            indexLabelFontSize: 14,
           
            
            dataPoints: dataPoints.map(dataPoint => ({
                ...dataPoint,
                percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
            }))
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
                presidentChart.options.data[0].dataPoints = response.president.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.president.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                presidentChart.render();

                vicePresidentChart.options.data[0].dataPoints = response.vicePresident.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.vicePresident.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                vicePresidentChart.render();

                secretaryChart.options.data[0].dataPoints = response.secretary.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.secretary.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                secretaryChart.render();

                treasurerChart.options.data[0].dataPoints = response.treasurer.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.treasurer.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                treasurerChart.render();

                auditorChart.options.data[0].dataPoints = response.auditor.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.auditor.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                auditorChart.render();

                proChart.options.data[0].dataPoints = response.publicInformationOfficer.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.publicInformationOfficer.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                proChart.render();

                businessManagerChart.options.data[0].dataPoints = response.businessManager.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.businessManager.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                businessManagerChart.render();

                beedRepChart.options.data[0].dataPoints = response.beedRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.beedRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                beedRepChart.render();

                bsedRepChart.options.data[0].dataPoints = response.bsedRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.bsedRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                bsedRepChart.render();

                bshmRepChart.options.data[0].dataPoints = response.bshmRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.bshmRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                bshmRepChart.render();

                bsoadRepChart.options.data[0].dataPoints = response.bsoadRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.bsoadRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                bsoadRepChart.render();

                bscrimRepChart.options.data[0].dataPoints = response.bsCrimRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.bsCrimRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                bscrimRepChart.render();

                bsitRepChart.options.data[0].dataPoints = response.bsitRepresentative.map(dataPoint => ({
                    ...dataPoint,
                    percent: ((dataPoint.y / response.bsitRepresentative.reduce((acc, dp) => acc + dp.y, 0)) * 100).toFixed(2)
                }));
                bsitRepChart.render();
            },
            error: function(error) {
                console.error("Error fetching data", error);
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
