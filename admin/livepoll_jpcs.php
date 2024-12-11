<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header_jpcs.php'; ?>
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
            transition: background-color 0.3s ease;
        }

        #back-to-top:hover {
            background-color: #555;
        }
        .chart-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.candidate-images {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 150px; /* Set a fixed width for image container */
    margin-right: 20px; /* Move the images to the left of the graph */
    margin-left: 0; /* Ensure no margin on the left side */
}

.candidate-image {
    margin-bottom: 10px;
    margin-right: 50px;
}

.candidate-image img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.candidate-image:hover {
    transform: scale(1.1);
}


        @media (max-width: 768px) {
            .candidate-image img {
                width: 80px;
                height: 80px;
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
<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar_jpcs.php'; ?>
        <?php include 'includes/menubar_jpcs.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Election Results</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Results</li>
                </ol>
            </section>

            <section class="content">
                <!-- Organization Selection Form -->
                <form id="organization-form">
                    <label for="organization-select">Select Organization:</label>
                    <select id="organization-select" name="organization">
                        <option value="jpcs">JPCS</option>
                    </select>
                    <label for="graph-select">Select Graph Type:</label>
                    <select id="graph-select" name="graph-type">
                        <option value="bar">Bar Graph</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                        <option value="donut">Donut Chart</option>
                        <option value="stacked">Stacked Area Chart</option>
                    </select>
                    <button type="submit">Show Results</button>
                </form>
                <br>

                <div class="row justify-content-center" id="results-container">
                    <!-- Results will be dynamically inserted here -->
                </div>
            </section>

            <button id="back-to-top" title="Back to top">&uarr;</button>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="path/to/jquery.min.js"></script>
    <script>
        // Bar Graph
        function generateBarGraph(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

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

        // Pie Chart
        function generatePieChart(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                animationDuration: 3000,
                animationEasing: "easeInOutBounce",
                title: {
                    text: "Vote Counts"
                },
                toolTip: {
                    content: "{label}: {y} Votes ({percent}%)",
                    backgroundColor: "#F1F1F1",
                    borderColor: "#666",
                    borderThickness: 1
                },
                data: [{
                    type: "pie",
                    indexLabel: "{label} - {percent}%",
                    indexLabelFontColor: "gray",
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                    }))
                }]
            });
            chart.render();
        }

        // Line Chart
        function generateLineChart(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

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
                    type: "line",
                    indexLabel: "{label} - {percent}%",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "gray",
                    indexLabelFontSize: 14,
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                    }))
                }]
            });
            chart.render();
        }

        // Donut Chart
        function generateDonutChart(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

            var chart = new CanvasJS.Chart(containerId, {
                animationEnabled: true,
                animationDuration: 3000,
                animationEasing: "easeInOutBounce",
                title: {
                    text: "Vote Counts"
                },
                toolTip: {
                    content: "{label}: {y} Votes ({percent}%)",
                    backgroundColor: "#F1F1F1",
                    borderColor: "#666",
                    borderThickness: 1
                },
                data: [{
                    type: "doughnut",
                    innerRadius: "70%", // This will create the donut shape
                    indexLabel: "{label} - {percent}%",
                    indexLabelFontColor: "gray",
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                    }))
                }]
            });
            chart.render();
        }

        // Stacked Area Chart
        function generateStackedAreaChart(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            dataPoints.forEach(dataPoint => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);
            });

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
                    type: "stackedArea",
                    indexLabel: "{label} - {percent}%",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "gray",
                    indexLabelFontSize: 14,
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        percent: ((dataPoint.y / totalVotes) * 100).toFixed(2)
                    }))
                }]
            });
            chart.render();
        }

        // Fetch and generate graphs
        function fetchAndGenerateGraphs(organization, graphType) {
            $.ajax({
                url: 'update_jpcs_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Clear previous results
                    $('#results-container').empty();

                    // Define categories for each organization
                    var categories = {
                        'jpcs': {
                            'president': 'President',
                            'vp for internal affairs': 'VP for Internal Affairs',
                            'vp for external affairs': 'VP for External Affairs',
                            'secretary': 'Secretary',
                            'treasurer': 'Treasurer',
                            'auditor': 'Auditor',
                            'p.r.o': 'P.R.O',
                            'dir. for membership': 'Dir. for Membership',
                            'dir. for special project': 'Dir. for Special Project',
                            '2-ARep': '2-A Rep',
                            '2-BRep': '2-B Rep',
                            '3-ARep': '3-A Rep',
                            '3-BRep': '3-B Rep',
                            '4-ARep': '4-A Rep',
                            '4-BRep': '4-B Rep'
                        }
                    };

                    // Get categories for the selected organization
                    var selectedCategories = categories[organization];

                    // Generate graphs for the selected categories
                    Object.keys(selectedCategories).forEach(function (category) {
                        if (response[category]) {
                            // Create container for each category
                            var containerHtml = ` 
                                <div class='col-md-12'>
                                    <div class='box'>
                                        <div class='box-header with-border'>
                                            <h3 class='box-title'><b>${selectedCategories[category]}</b></h3>
                                        </div>
                                        <div class='box-body'>
                                            <div class='chart-container'>
                                                <div id='${category}Graph' style='height: 300px; width: 80%;'></div>
                                                <div id='${category}Image' class='candidate-images'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#results-container').append(containerHtml);

                            // Call the respective function based on graph type
                            if (graphType === 'bar') {
                                generateBarGraph(response[category], category + 'Graph', category + 'Image');
                            } else if (graphType === 'pie') {
                                generatePieChart(response[category], category + 'Graph', category + 'Image');
                            } else if (graphType === 'line') {
                                generateLineChart(response[category], category + 'Graph', category + 'Image');
                            } else if (graphType === 'donut') {
                                generateDonutChart(response[category], category + 'Graph', category + 'Image');
                            } else if (graphType === 'stacked') {
                                generateStackedAreaChart(response[category], category + 'Graph', category + 'Image');
                            }
                        }
                    });
                }
            });
        }

        // Event listener for form submission
        $('#organization-form').on('submit', function (event) {
            event.preventDefault();
            var organization = $('#organization-select').val();
            var graphType = $('#graph-select').val();
            fetchAndGenerateGraphs(organization, graphType);
        });

        // Back to top functionality
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        $('#back-to-top').click(function () {
            $('html, body').animate({ scrollTop: 0 }, 500);
            return false;
        });
    </script>
</body>
</html>
