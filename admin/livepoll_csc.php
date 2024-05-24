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

        .candidate-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 50%;
        }

        .chart-container {
            width: calc(100% - 120px); /* Adjusting the width to account for image size and margin */
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
                            <div id="presidentGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="vicePresidentGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="secretaryGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="treasurerGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="auditorGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="proGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="businessManagerGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="beedRepGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsedRepGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bshmRepGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsoadRepGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bscrimRepGraphContainer"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div id="bsitRepGraphContainer"></div>
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
    function generateBarGraph(dataPoints, containerId, images) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
        
        var container = document.getElementById(containerId);
        container.innerHTML = ""; // Clear previous content

        dataPoints.forEach((dataPoint, index) => {
            var candidateContainer = document.createElement('div');
            candidateContainer.classList.add('candidate-container');

            var img = document.createElement('img');
            img.src = images[index];
            candidateContainer.appendChild(img);

            var chartContainer = document.createElement('div');
            chartContainer.classList.add('chart-container');
            candidateContainer.appendChild(chartContainer);

            container.appendChild(candidateContainer);

            var chart = new CanvasJS.Chart(chartContainer, {
                animationEnabled: true,
                axisX: {
                    title: "",
                    includeZero: true,
                    interval: 1,
                    labelFormatter: function() {
                        return " ";
                    }
                },
                axisY: {
                    title: ""
                },
                data: [{
                    type: "bar",
                    indexLabel: "{label} - {percent}%",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 14,
                    dataPoints: [{
                        ...dataPoint,
                        percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                    }]
                }]
            });
            chart.render();
        });
    }

    function updateVoteCounts() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                generateBarGraph(response.president, "presidentGraphContainer", response.presidentImages);
                generateBarGraph(response.vicePresident, "vicePresidentGraphContainer", response.vicePresidentImages);
                generateBarGraph(response.secretary, "secretaryGraphContainer", response.secretaryImages);
                generateBarGraph(response.treasurer, "treasurerGraphContainer", response.treasurerImages);
                generateBarGraph(response.auditor, "auditorGraphContainer", response.auditorImages);
                generateBarGraph(response.publicInformationOfficer, "proGraphContainer", response.proImages);
                generateBarGraph(response.businessManager, "businessManagerGraphContainer", response.businessManagerImages);
                generateBarGraph(response.beedRepresentative, "beedRepGraphContainer", response.beedRepImages);
                generateBarGraph(response.bsedRepresentative, "bsedRepGraphContainer", response.bsedRepImages);
                generateBarGraph(response.bshmRepresentative, "bshmRepGraphContainer", response.bshmRepImages);
                generateBarGraph(response.bsoadRepresentative, "bsoadRepGraphContainer", response.bsoadRepImages);
                generateBarGraph(response.bsCrimRepresentative, "bscrimRepGraphContainer", response.bscrimRepImages);
                generateBarGraph(response.bsitRepresentative, "bsitRepGraphContainer", response.bsitRepImages);
            },
            error: function(error) {
                console.error("Error fetching data", error);
            }
        });
    }

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
