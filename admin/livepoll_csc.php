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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            font-weight: bold;
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
                $categories = [
                    'president' => 'President',
                    'vice president' => 'Vice President',
                    'secretary' => 'Secretary',
                    'treasurer' => 'Treasurer',
                    'auditor' => 'Auditor',
                    'p.r.o' => 'P.R.O',
                    'businessManager' => 'Business Manager',
                    'beedRep' => 'BEED Rep',
                    'bsedRep' => 'BSED Rep',
                    'bshmRep' => 'BSHM Rep',
                    'bsoadRep' => 'BSOAD Rep',
                    'bs crimRep' => 'BS CRIM Rep',
                    'bsitRep' => 'BSIT Rep'
                ];

                foreach ($categories as $categoryKey => $categoryName) {
                    echo "
                    <div class='col-md-12'>
                        <div class='box'>
                            <div class='box-header with-border'>
                                <h3 class='box-title'><b>$categoryName</b></h3>
                            </div>
                            <div class='box-body'>
                                <div class='chart-container'>
                                    <div id='{$categoryKey}Graph' style='height: 300px; width: calc(100% - 70px); margin-left: 70px;'></div>
                                </div>
                                <div class='candidate-images' id='{$categoryKey}Image'></div>
                            </div>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </section>

        <button id="back-to-top" title="Back to top">&uarr;</button>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    // Store chart instances globally
    var charts = {};

    function initializeCharts() {
        var categories = [
            'president', 'vice president', 'secretary', 'treasurer', 'auditor',
            'p.r.o', 'businessManager', 'beedRep', 'bsedRep', 'bshmRep',
            'bsoadRep', 'bs crimRep', 'bsitRep'
        ];

        // Initialize empty charts for each category
        categories.forEach(function(category) {
            var chart = new CanvasJS.Chart(category + 'Graph', {
                animationEnabled: true,
                animationDuration: 1000,
                animationEasing: "easeInOutBounce",
                title: {
                    text: "Vote Counts"
                },
                axisX: {
                    title: "",
                    includeZero: true,
                    interval: 1,
                    labelFormatter: function () {
                        return " ";
                    }
                },
                axisY: {
                    title: "",
                    interval: 1
                },
                data: [{
                    type: "bar",
                    indexLabel: "{label} - {percent}%",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 14,
                    dataPoints: []
                }]
            });
            chart.render();
            charts[category] = chart;
        });
    }

    function updateCharts(data) {
        // Update all charts with new data
        for (var category in data) {
            if (charts[category]) {
                var dataPoints = data[category];
                var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

                // Update chart data
                charts[category].options.data[0].dataPoints = dataPoints.map(dataPoint => ({
                    ...dataPoint,
                    percent: totalVotes > 0 ? ((dataPoint.y / totalVotes) * 100).toFixed(2) : 0
                }));

                // Calculate new interval for Y-axis
                var maxVotes = Math.max(...dataPoints.map(dp => dp.y), 1);
                charts[category].options.axisY.interval = Math.ceil(maxVotes / 10) || 1;

                charts[category].render();

                // Update candidate images
                var imageContainer = document.getElementById(category + 'Image');
                if (imageContainer) {
                    imageContainer.innerHTML = dataPoints.map(dataPoint =>
                        `<div class="candidate-image">
                            <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                            <span class="candidate-label">${dataPoint.label}</span>
                        </div>`
                    ).join('');
                }
            }
        }
    }

    function fetchVoteData() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                updateCharts(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching vote data: ", status, error);
            }
        });
    }

    $(document).ready(function() {
        // Initialize charts
        initializeCharts();

        // Fetch initial data
        fetchVoteData();

        // Handle vote submission
        $('#voteForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: 'submitted_vote.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Fetch updated vote data after successful submission
                        fetchVoteData();
                        alert('Vote submitted successfully!');
                    } else {
                        alert('Error submitting vote: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error submitting vote: ", status, error);
                    alert('Error submitting vote.');
                }
            });
        });

        // Back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        $('#back-to-top').click(function() {
            $('html, body').animate({ scrollTop: 0 }, 600);
            return false;
        });
    });
</script>
</body>
</html>