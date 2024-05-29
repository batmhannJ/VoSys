<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/session.php'; ?>
    <?php include 'includes/header.php'; ?>
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
            border-radius: 50%;
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
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

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
                        <option value="csc">CSC</option>
                        <option value="jpcs">JPCS</option>
                        <option value="ymf">YMF</option>
                        <option value="pasoa">PASOA</option>
                        <option value="code-tg">CODE-TG</option>
                        <option value="hmso">HMSO</option>
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
        function generateBarGraph(dataPoints, containerId, imageContainerId) {
            var totalVotes = dataPoints.reduce((acc, dataPoint) => acc + dataPoint.y, 0);

            // Ensure images match the data points by iterating in the same order
            var imageContainer = document.getElementById(imageContainerId);
            imageContainer.innerHTML = '';
            // Swap positions of the first two images for demonstration
            if (dataPoints.length > 1) {
                var temp = dataPoints[0].image;
                dataPoints[0].image = dataPoints[1].image;
                dataPoints[1].image = temp;
            }
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

        function fetchAndGenerateGraphs(organization) {
            $.ajax({
                url: 'update_data.php',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Clear previous results
                    $('#results-container').empty();

                    // Define categories for each organization
                    var categories = {
                        'csc': {
                            'president': 'President',
                            'vice president': 'Vice President',
                            'secretary': 'Secretary',
                            'treasurer': 'Treasurer',
                            'auditor': 'Auditor',
                            'p.r.o': 'P.R.O',
                            'businessManager': 'Business Manager',
                            'beedRep': 'BEED Representative',
                            'bsedRep': 'BSED Representative',
                            'bshmRep': 'BSHM Representative',
                            'bsoadRep': 'BSOAD Representative',
                            'bs crimRep': 'BS Crim Representative',
                            'bsitRep': 'BSIT Representative'
                        },
                        'jpcs': {
                            'jpcsPresident': 'President',
                            'jpcsVicePresident': 'Vice President',
                            'jpcsSecretary': 'Secretary',
                            'jpcsTreasurer': 'Treasurer',
                            'jpcsRep': 'Representative'
                        },
                        'ymf': {
                            'ymfPresident': 'President',
                            'ymfVicePresident': 'Vice President',
                            'ymfSecretary': 'Secretary',
                            'ymfTreasurer': 'Treasurer',
                            'ymfRep': 'Representative'
                        },
                        'pasoa': {
                            'pasoaPresident': 'President',
                            'pasoaVicePresident': 'Vice President',
                            'pasoaSecretary': 'Secretary',
                            'pasoaTreasurer': 'Treasurer',
                            'pasoaRep': 'Representative'
                        },
                        'code-tg': {
                            'codePresident': 'President',
                            'codeVicePresident': 'Vice President',
                            'codeSecretary': 'Secretary',
                            'codeTreasurer': 'Treasurer',
                            'codeRep': 'Representative'
                        },
                        'hmso': {
                            'hmsoPresident': 'President',
                            'hmsoVicePresident': 'Vice President',
                            'hmsoSecretary': 'Secretary',
                            'hmsoTreasurer': 'Treasurer',
                            'hmsoRep': 'Representative'
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

                            // Generate the bar graph for the category
                            generateBarGraph(response[category], category + 'Graph', category + 'Image');
                        }
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function () {
            // Fetch and generate graphs for the default organization (CSC) initially
            fetchAndGenerateGraphs('csc');

            // Handle form submission
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
