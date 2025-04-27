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

    <!-- Your existing HTML content here -->

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

    function setupEventSource() {
        // Create a new EventSource connection
        const eventSource = new EventSource('live_results_sse.php');
        
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            updateCharts(data);
        };
        
        eventSource.onerror = function() {
            console.error("EventSource failed.");
            // Attempt to reconnect after 5 seconds
            setTimeout(setupEventSource, 5000);
        };
    }

    $(document).ready(function() {
        // Initialize charts first
        initializeCharts();
        
        // Fetch initial data
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                updateCharts(response);
                // After initial load, set up SSE
                setupEventSource();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching initial data: ", status, error);
            }
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