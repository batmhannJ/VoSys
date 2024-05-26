<!DOCTYPE html>
<html>
<?php
include 'includes/session.php';
include 'includes/header_csc.php';
?>
<head>
    <style>
        /* Your existing CSS styles */
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function to generate bar graph
    function generateBarGraph(dataPoints, containerId, imageContainerId) {
        var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

        // Update the image container
        var imageContainer = document.getElementById(imageContainerId);
        imageContainer.innerHTML = dataPoints.map(dataPoint =>
            `<div class="candidate-image">
                <img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">
                <span class="candidate-label">${dataPoint.label}</span>
            </div>`
        ).join('');

        var chart = new CanvasJS.Chart(containerId, {
            animationEnabled: true,
            animationDuration: 3000,
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
                interval: Math.ceil(totalVotes / 10)
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
    }

    // Function to update graphs with new data
    function updateGraphs(data) {
        var categories = [
            'president', 'vice president', 'secretary', 'treasurer', 'auditor',
            'p.r.o', 'businessManager', 'beedRep', 'bsedRep', 'bshmRep',
            'bsoadRep', 'bs crimRep', 'bsitRep'
        ];

        categories.forEach(function (category) {
            if (data[category]) {
                generateBarGraph(data[category], category + 'Graph', category + 'Image');
            }
        });
    }

    $(document).ready(function () {
        // Create a new EventSource object to listen to SSE
        const evtSource = new EventSource('update_data_csc.php');

        // Event listener for receiving SSE messages
        evtSource.onmessage = function(event) {
            const data = JSON.parse(event.data);
            updateGraphs(data);
        };

        // Event listener for SSE errors
        evtSource.onerror = function
        (err) {
            console.error("EventSource failed:", err);
            evtSource.close();
        };

        // Back to top button functionality
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        $('#back-to-top').click(function () {
            $('html, body').animate({ scrollTop: 0 }, 600);
            return false;
        });
    });
</script>
</body>
</html>
