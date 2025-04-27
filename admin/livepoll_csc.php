<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <style>
        /* Your existing CSS styles here */
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

    // Function to fetch updated results
    function fetchUpdatedResults() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                updateCharts(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching updated results: ", status, error);
            }
        });
    }

    // Listen for vote submission events
    function setupVoteSubmissionListener() {
        // This assumes your voting form submits via AJAX
        $(document).ajaxComplete(function(event, xhr, settings) {
            // Check if this was a vote submission
            if (settings.url.includes('submit_vote.php')) {
                fetchUpdatedResults();
            }
        });
        
        // Alternative: If using form submission without AJAX
        // You can use a server-side push mechanism (like SSE or WebSocket)
        // when the vote is processed
    }

    $(document).ready(function() {
        // Initialize charts first
        initializeCharts();
        
        // Fetch initial data
        fetchUpdatedResults();
        
        // Set up vote submission listener
        setupVoteSubmissionListener();

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