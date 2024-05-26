<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
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

        .chart-container {
            position: relative;
            margin-bottom: 40px;
        }

        .candidate-images {
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 10px;
        }

        .candidate-image {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .candidate-label {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .candidate-image img {
                width: 75px;
                height: 75px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image img {
                width: 100px;
                height: 100px;
            }
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
                            <div class="chart-container">
                                <div class="candidate-images" id="presidentImage"></div>
                                <div id="presidentGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Vice President</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Secretary</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="secretaryImage"></div>
                                <div id="secretaryGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Treasurer</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="treasurerImage"></div>
                                <div id="treasurerGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Auditor</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="auditorImage"></div>
                                <div id="auditorGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Public Information Officer (P.R.O)</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Business Manager</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="businessManagerImage"></div>
                                <div id="businessManagerGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsitRepImage"></div>
                                <div id="bsitRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSTM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bstmRepImage"></div>
                                <div id="bstmRepGraph" style="height: 300px; width: calc(100% - 100px); margin-left: 100px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<!-- Back to Top button -->
<button id="back-to-top" title="Back to Top">&uarr;</button>

<?php include 'config_modal.php'; ?>

<!-- Scripts -->
<?php include 'includes/scripts.php'; ?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.chart-container').forEach(container => {
            const graphDiv = container.querySelector('div[id$="Graph"]');
            const imageDiv = container.querySelector('div[id$="Image"]');
            const position = graphDiv.id.replace('Graph', '');

            fetch(`election_results_${position.toLowerCase()}.php`)
                .then(response => response.json())
                .then(data => {
                    const candidateImages = data.map(candidate => `
                        <div class="candidate-image">
                            <img src="${candidate.image}" alt="${candidate.name}">
                            <span class="candidate-label">${candidate.name}</span>
                        </div>
                    `).join('');

                    imageDiv.innerHTML = candidateImages;

                    Highcharts.chart(graphDiv.id, {
                        chart: { type: 'bar' },
                        title: { text: '' },
                        xAxis: {
                            categories: data.map(candidate => candidate.name),
                            title: { text: null },
                            labels: { enabled: false }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Votes',
                                align: 'high'
                            },
                            labels: { overflow: 'justify' }
                        },
                        tooltip: { valueSuffix: ' votes' },
                        plotOptions: {
                            bar: { dataLabels: { enabled: true } }
                        },
                        legend: { enabled: false },
                        credits: { enabled: false },
                        series: [{
                            name: 'Votes',
                            data: data.map(candidate => candidate.votes)
                        }]
                    });
                });
        });

        // Back to Top button
        const backToTopButton = document.getElementById("back-to-top");

        window.onscroll = function () {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        };

        backToTopButton.onclick = function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    });
</script>
</body>
</html>
