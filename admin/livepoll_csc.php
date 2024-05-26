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
                                <div id="presidentGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div id="vicePresidentGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div id="secretaryGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div id="treasurerGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>P.R.O</b></h3> <!-- Changed to P.R.O -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="proImage"></div>
                                <div id="proGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
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
                                <div id="businessManagerGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BEED Rep</b></h3> <!-- Changed to BEED Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="beedRepImage"></div>
                                <div id="beedRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSED Rep</b></h3> <!-- Changed to BSED Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsedRepImage"></div>
                                <div id="bsedRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSHM Rep</b></h3> <!-- Changed to BSHM Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bshmRepImage"></div>
                                <div id="bshmRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSOAD Rep</b></h3> <!-- Changed to BSOAD Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsoadRepImage"></div>
                                <div id="bsoadRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSCRIM Rep</b></h3> <!-- Changed to BSCRIM Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bscrimRepImage"></div>
                                <div id="bscrimRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>BSIT Rep</b></h3> <!-- Changed to BSIT Rep -->
                        </div>
                        <div class="box-body">
                            <div class="chart-container">
                                <div class="candidate-images" id="bsitRepImage"></div>
                                <div id="bsitRepGraph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<!-- Back to Top button -->
<button id="back-to-top" title="Back to Top">↑</button>

<script>
    // Back to Top button functionality
    const backToTopButton = document.getElementById('back-to-top');

    window.onscroll = function() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    };

    backToTopButton.onclick = function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    };
</script>
</body>
</html>
