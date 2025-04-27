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
            display: flex;
            align-items: flex-start;
        }

        .chart-wrapper {
            flex-grow: 1;
            margin-left: 20px;
        }

        .candidate-images {
            width: 200px;
            min-width: 200px;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .candidate-image {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            height: 50px; /* Fixed height to match bar alignment */
        }

        .candidate-image img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .candidate-label {
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Ensure bars and images align vertically */
        .canvasjs-chart-container {
            position: relative;
            top: -25px; /* Adjust this to align bars with images */
        }

        @media (max-width: 992px) {
            .chart-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .candidate-images {
                width: 100%;
                flex-direction: row;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }
            
            .candidate-image {
                margin-right: 20px;
            }
            
            .chart-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            .canvasjs-chart-container {
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .candidate-image img {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .candidate-images {
                flex-direction: column;
            }
            
            .candidate-image img {
                width: 70px;
                height: 70px;
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
                                    <div class='candidate-images' id='{$categoryKey}Image'></div>
                                    <div class='chart-wrapper'>
                                        <div id='{$categoryKey}Graph' style='height: 300px; width: 100%;'></div>
                                    </div>
                                </div>
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
                    indexLabel: "{y} votes ({percent}%)",
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
                
                // Sort candidates by votes (descending)
                dataPoints.sort((a, b) => b.y - a.y);
                
                // Update chart data
                charts[category].options.data[0].dataPoints = dataPoints.map((dataPoint, index) => ({
                    label: dataPoint.label,
                    y: dataPoint.y,
                    color: getColorForIndex(index), // Assign different colors
                    percent: totalVotes > 0 ? ((dataPoint.y / totalVotes) * 100).toFixed(2) : 0
                }));
                
                // Calculate new interval for Y-axis
                var maxVotes = Math.max(...dataPoints.map(dp => dp.y), 1);
                charts[category].options.axisY.interval = Math.ceil(maxVotes / 10) || 1;
                
                charts[category].render();
                
                // Update candidate images - ensure same order as bars
                var imageContainer = document.getElementById(category + 'Image');
                if (imageContainer) {
                    imageContainer.innerHTML = dataPoints.map((dataPoint, index) => {
                        // Shorten long names with ellipsis
                        const shortName = dataPoint.label.length > 15 
                            ? dataPoint.label.substring(0, 15) + '...' 
                            : dataPoint.label;
                            
                        return `
                        <div class="candidate-image" data-fullname="${dataPoint.label}">
                            <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                            <span class="candidate-label">${shortName}</span>
                        </div>`;
                    }).join('');
                }
            }
        }
    }

    // Helper function to get distinct colors for bars
    function getColorForIndex(index) {
        const colors = [
            "#2E86C1", "#E74C3C", "#F39C12", "#27AE60", 
            "#8E44AD", "#16A085", "#D35400", "#2980B9",
            "#C0392B", "#F1C40F", "#2ECC71", "#9B59B6"
        ];
        return colors[index % colors.length];
    }

    function fetchResults() {
        $.ajax({
            url: 'update_data_csc.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Ensure data is properly formatted
                for (var category in response) {
                    if (response[category]) {
                        response[category] = response[category].map(item => ({
                            label: item.firstname + ' ' + item.lastname,
                            y: parseInt(item.votes),
                            image: item.photo ? 'images/' + item.photo : 'images/profile.jpg'
                        }));
                    }
                }
                updateCharts(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data: ", status, error);
            }
        });
    }

    $(document).ready(function() {
        // Initialize charts first
        initializeCharts();
        
        // Fetch initial data
        fetchResults();
        
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