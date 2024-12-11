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
        }

        #back-to-top:hover {
            background-color: #555;
        }

        .chart-container {
            position: relative;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
        }

      /* Main container for images */
.candidate-wrapper {
    display: inline-block;
    position: relative;
    margin: 10px;  /* Smaller margin for compact display */
    width: 100px;  /* Smaller width for each image container */
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 50%;
}

/* Hover effect for the entire wrapper */
.candidate-wrapper:hover {
    transform: translateY(-8px);  /* Slightly smaller hover effect */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);  /* Slightly smaller shadow */
}

/* Style the candidate image */
.image-container {
    position: relative;
    overflow: hidden;
    height: 80px;  /* Smaller height for images */
    width: 80px;   /* Smaller width for images */
    margin: 0 auto 8px;  /* Adjusted margin for better spacing */
    border-radius: 50%;  /* Circular image container */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);  /* Soft shadow */
}

.candidate-image {
    width: 100%;
    height: 100%;
    object-fit: cover;  /* Make sure image fits perfectly in the circle */
    transition: transform 0.3s ease;  /* Image zoom effect */
}

/* Image zoom effect on hover */
.candidate-wrapper:hover .candidate-image {
    transform: scale(1.1);  /* Slight zoom-in effect */
}

/* Name label for the candidate */
.name-label {
    font-size: 12px;  /* Smaller font size for the name */
    font-weight: bold;
    color: #333;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Show name label with smooth transition */
.candidate-wrapper:hover .name-label {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive: Adjust layout for smaller screens */
@media (max-width: 768px) {
    .candidate-wrapper {
        width: 80px;  /* Make images even smaller on mobile */
    }
    .candidate-image {
        width: 100%;  /* Ensure image fits well on smaller screens */
        height: 100%;
    }
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
       // Bar Graph with Dynamic Image and Name Layout
function generateBarGraph(dataPoints, containerId, imageContainerId) {
    var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

    // Clear previous images in the container
    var imageContainer = document.getElementById(imageContainerId);
    imageContainer.innerHTML = '';

    // Loop to create unique and dynamic display for each candidate
    dataPoints.forEach(dataPoint => {
        var candidateDiv = document.createElement('div');
        candidateDiv.className = 'candidate-wrapper';

        // Create image container
        var imgContainer = document.createElement('div');
        imgContainer.className = 'image-container';
        var imgElement = document.createElement('img');
        imgElement.src = dataPoint.image;
        imgElement.alt = dataPoint.label;
        imgElement.title = dataPoint.label;
        imgElement.className = 'candidate-image';

        // Create name label that appears below the image
        var nameLabel = document.createElement('div');
        nameLabel.className = 'name-label';
        nameLabel.innerText = dataPoint.label;

        // Append image and name label to the wrapper
        imgContainer.appendChild(imgElement);
        candidateDiv.appendChild(imgContainer);
        candidateDiv.appendChild(nameLabel);

        // Append to the image container
        imageContainer.appendChild(candidateDiv);
    });

    // Generate the bar graph
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
                data: [{
                    type: "pie",
                    indexLabel: "{label} - {percent}%",
                    indexLabelFontColor: "white",
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
                    title: "Candidates"
                },
                axisY: {
                    title: "Votes"
                },
                data: [{
                    type: "line",
                    dataPoints: dataPoints
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
                data: [{
                    type: "doughnut",
                    indexLabel: "{label} - {percent}%",
                    indexLabelFontColor: "white",
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
                    title: "Candidates"
                },
                axisY: {
                    title: "Votes"
                },
                data: [{
                    type: "stackedArea",
                    dataPoints: dataPoints
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
                                                <div class='candidate-images' id='${category}Image'></div>
                                                <div id='${category}Graph' style='height: 300px; width: calc(100% - 80px);'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#results-container').append(containerHtml);

                            // Generate the selected graph type for the category
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
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        // Event listener for form submission
        $('#organization-form').on('submit', function (e) {
            e.preventDefault();

            var organization = $('#organization-select').val();
            var graphType = $('#graph-select').val();

            fetchAndGenerateGraphs(organization, graphType);
        });

        // Scroll to top button
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