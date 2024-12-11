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

        .candidate-images {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-right: 10px;
        }

        .candidate-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px;
        }

        .candidate-image img {
            width: 60px;
            height: 60px;
            margin-right: -10px;
            margin-bottom: 25px;
            margin-top: 35px;
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
        function generateGraph(dataPoints, containerId, imageContainerId, graphType) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);
            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            
            // Define custom colors based on your request
            const candidateColors = [
                "rgb(43, 8, 168)",   // Blue color
                "rgb(158, 9, 29)",   // Red color
                "rgb(43, 8, 168)",   // Blue color (repeating the color)
                "rgb(158, 9, 29)",   // Red color (repeating the color)
                "rgb(43, 8, 168)",   // Blue color (repeating the color)
                "rgb(158, 9, 29)"    // Red color (repeating the color)
            ];
            
            dataPoints.forEach((dataPoint, index) => {
                var candidateDiv = document.createElement('div');
                candidateDiv.className = 'candidate-image';
                candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                imageContainer.appendChild(candidateDiv);

                // Assign a color based on the index (repeating the custom color palette)
                dataPoint.color = candidateColors[index % candidateColors.length];
            });

            var chartOptions = {
                animationEnabled: true,
                theme: "light2",
                title: { text: "Vote Counts" },
                data: [{
                    type: graphType,
                    dataPoints: dataPoints.map(dataPoint => ({
                        ...dataPoint,
                        color: dataPoint.color || "#4F81BC", // Default color if not assigned
                        indexLabel: `${dataPoint.label} - ${(dataPoint.y / totalVotes * 100).toFixed(2)}%`,
                        indexLabelFontColor: "lightgray",  // Changed to light gray
                        indexLabelPlacement: "inside",
                        indexLabelFontSize: 14,
                        indexLabelFontWeight: "bold"
                    }))
                }]
            };

            // Add specific options for stacked area and donut charts
            if (graphType === "stackedArea") {
                chartOptions.data[0].type = "stackedArea";
            } else if (graphType === "doughnut") {
                chartOptions.data[0].type = "doughnut";
                chartOptions.data[0].innerRadius = 70; // Create a donut effect
            }

            var chart = new CanvasJS.Chart(containerId, chartOptions);
            chart.render();
        }

        function fetchAndGenerateGraphs(organization) {
            const graphType = $('#graph-type').val();
            $.ajax({
                url: 'update_jpcs_data.php',  // Adjust this path for JPCS data
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Clear previous results
                    $('#results-container').empty();

                    // Define categories for JPCS organization
                    var categories = {
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
                    };

                    // Generate graphs for the selected categories
                    Object.keys(categories).forEach(function (category) {
                        if (response[category]) {
                            // Create container for each category
                            var containerHtml = ` 
                                <div class='col-md-12'>
                                    <div class='box'>
                                        <div class='box-header with-border'>
                                            <h3 class='box-title'><b>${categories[category]}</b></h3>
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

                            // Generate the graph for the category
                            generateGraph(response[category], category + 'Graph', category + 'Image', graphType);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function () {
            fetchAndGenerateGraphs('jpcs');  // Default organization is JPCS
            $('#organization-form').submit(function (event) {
                event.preventDefault();
                const selectedOrganization = $('#organization-select').val();
                fetchAndGenerateGraphs(selectedOrganization);
            });

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
