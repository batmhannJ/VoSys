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
                <?php
                $positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor', 'Public Information Officer (P.R.O)', 'Business Manager', 'BEED Representative', 'BSED Representative', 'BSHM Representative', 'BSOAD Representative'];
                foreach ($positions as $position) {
                    $slug = slugify($position);
                    echo '<div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>' . $position . '</b></h3>
                            </div>
                            <div class="box-body">
                                <div class="chart-container">
                                    <div class="candidate-images" id="' . $slug . 'Image"></div>
                                    <div id="' . $slug . 'Graph" style="height: 300px; width: calc(100% - 70px); margin-left: 70px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <button id="back-to-top" title="Back to Top"><i class="fa fa-chevron-up"></i></button>

    <script>
        // Scroll to top functionality
        var backToTopButton = document.getElementById("back-to-top");
        window.onscroll = function () {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        }

        backToTopButton.addEventListener("click", function () {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        });

        // Fetch election results and display charts
        document.addEventListener("DOMContentLoaded", function () {
            <?php
            foreach ($positions as $position) {
                $slug = slugify($position);
                $stmt = $conn->prepare("SELECT * FROM candidates WHERE position_id IN (SELECT id FROM positions WHERE description=?) ORDER BY lastname ASC");
                $stmt->execute([$position]);
                $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $votes = [];
                $images = [];
                foreach ($candidates as $candidate) {
                    $votes[] = ['y' => intval($candidate['votes']), 'label' => $candidate['firstname'] . ' ' . $candidate['lastname']];
                    $images[] = $candidate['photo'];
                }
                ?>
                // Data for <?php echo $position; ?>
                var dataPoints<?php echo $slug; ?> = <?php echo json_encode($votes); ?>;
                var candidateImages<?php echo $slug; ?> = <?php echo json_encode($images); ?>;

                var chart<?php echo $slug; ?> = new CanvasJS.Chart("<?php echo $slug; ?>Graph", {
                    animationEnabled: true,
                    theme: "light2",
                    title: {
                        text: "<?php echo $position; ?>"
                    },
                    axisY: {
                        title: "Votes"
                    },
                    data: [{
                        type: "column",
                        yValueFormatString: "#,##0",
                        dataPoints: dataPoints<?php echo $slug; ?>
                    }]
                });
                chart<?php echo $slug; ?>.render();

                // Append candidate images
                var imageContainer<?php echo $slug; ?> = document.getElementById("<?php echo $slug; ?>Image");
                candidateImages<?php echo $slug; ?>.forEach(function (image, index) {
                    var imageDiv = document.createElement("div");
                    imageDiv.className = "candidate-image";
                    imageDiv.innerHTML = `<img src="images/${image}" alt="Candidate Image"> <div class="candidate-label">${dataPoints<?php echo $slug; ?>[index].label}</div>`;
                    imageContainer<?php echo $slug; ?>.appendChild(imageDiv);
                });
                <?php
            }
            ?>
        });
    </script>
</div>
</body>
</html>
