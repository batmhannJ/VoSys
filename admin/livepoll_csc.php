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

        .chart-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .candidate-image {
            margin-right: 20px;
        }

        .candidate-image img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .chart-container {
                flex-direction: column;
                align-items: center;
            }

            .candidate-image {
                margin-bottom: 20px;
                margin-right: 0;
            }

            .candidate-image img {
                width: 75px;
                height: 75px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image img {
                width: 50px;
                height: 50px;
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
                                <div class="candidate-image" id="presidentImage"></div>
                                <div id="presidentGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="vicePresidentImage"></div>
                                <div id="vicePresidentGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="secretaryImage"></div>
                                <div id="secretaryGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="treasurerImage"></div>
                                <div id="treasurerGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="auditorImage"></div>
                                <div id="auditorGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="businessManagerImage"></div>
                                <div id="businessManagerGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: 100%;"></div>
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
                                <div class="candidate-image" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BS CRIM Representative</b></h3>
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-image" id="bsCrimRepImage"></div>
                                <div id="bsCrimRepGraph" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script>
        // Show the Back to Top button when scrolling down
        window.onscroll = function() {
            var backToTopButton = document.getElementById("back-to-top");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        };

        // Scroll to the top of the document when the Back to Top button is clicked
        document.getElementById("back-to-top").onclick = function() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        };

        // Function to generate the bar graph
        function generateBarGraph(data, elementId, imageElementId) {
            var labels = data.map(function(candidate) {
                return candidate.name;
            });

            var votes = data.map(function(candidate) {
                return candidate.votes;
            });

            var images = data.map(function(candidate) {
                return candidate.image;
            });

            // Display candidate images
            var imageElement = document.getElementById(imageElementId);
            images.forEach(function(image) {
                var img = document.createElement('img');
                img.src = image;
                img.alt = 'Candidate Photo';
                imageElement.appendChild(img);
            });

            var chart = new Chart(document.getElementById(elementId), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Votes',
                        data: votes,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        }

        // Sample data for each position
        var presidentData = [
            {name: 'John Doe', votes: 120, image: 'path_to_john_image.jpg'},
            {name: 'Jane Smith', votes: 150, image: 'path_to_jane_image.jpg'}
        ];

        var vicePresidentData = [
            {name: 'Alice Brown', votes: 90, image: 'path_to_alice_image.jpg'},
            {name: 'Bob White', votes: 110, image: 'path_to_bob_image.jpg'}
        ];

        // Generate the bar graphs
        generateBarGraph(presidentData, 'presidentGraph', 'presidentImage');
        generateBarGraph(vicePresidentData, 'vicePresidentGraph', 'vicePresidentImage');
        // Add more calls to generateBarGraph for other positions with their respective data
    </script>
    <!-- Back to Top button -->
    <button id="back-to-top" title="Back to Top">↑</button>
</div>
</body>
</html>
