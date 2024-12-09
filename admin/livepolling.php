    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php include 'includes/session.php'; ?>
        <?php include 'includes/header.php'; ?>
        <style>
    .modern-wrapper {
        font-family: 'Arial', sans-serif;
        padding: 20px;
        background-color: #f9f9f9;
    }

    .modern-header h1 {
        color: #333;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .breadcrumb {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .breadcrumb li {
        margin: 0 5px;
        color: #007bff;
    }

    .breadcrumb li a {
        text-decoration: none;
        color: #007bff;
    }

    .form-container {
        display: flex;
        justify-content: center;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .modern-form {
        width: 100%;
        max-width: 500px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: bold;
    }

    .modern-select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 16px;
        color: #333;
    }

    .modern-button {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        text-align: center;
    }

    .modern-button:hover {
        background-color: #0056b3;
    }

    #results-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 20px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .modern-form {
            padding: 10px;
        }

        .modern-select,
        .modern-button {
            font-size: 14px;
        }
    }
</style>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <?php include 'includes/navbar.php'; ?>
            <?php include 'includes/menubar.php'; ?>

            <div class="content-wrapper modern-wrapper">
    <section class="content-header modern-header">
        <h1>Election Results</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Results</li>
        </ol>
    </section>

    <section class="content">
        <!-- Organization Selection Form -->
        <div class="form-container">
            <form id="organization-form" class="modern-form">
                <div class="form-group">
                    <label for="organization-select">Select Organization:</label>
                    <select id="organization-select" name="organization" class="modern-select">
                        <option value="csc">CSC</option>
                        <option value="jpcs">JPCS</option>
                        <option value="ymf">YMF</option>
                        <option value="pasoa">PASOA</option>
                        <option value="code-tg">CODE-TG</option>
                        <option value="hmso">HMSO</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="graph-type">Select Graph Type:</label>
                    <select id="graph-type" class="modern-select">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                    </select>
                </div>

                <button type="submit" class="modern-button">Show Results</button>
            </form>
        </div>

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
                dataPoints.forEach(dataPoint => {
                    var candidateDiv = document.createElement('div');
                    candidateDiv.className = 'candidate-image';
                    candidateDiv.innerHTML = `<img src="${dataPoint.image}" alt="${dataPoint.label}" title="${dataPoint.label}">`;
                    imageContainer.appendChild(candidateDiv);
                });

                var chart = new CanvasJS.Chart(containerId, {
                    animationEnabled: true,
                    title: { text: "Vote Counts" },
                    data: [{
                        type: graphType,
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

                if (graphType === 'bar') {
                    chart.options.axisX = {
                        title: "",
                        includeZero: true,
                        interval: 1,
                        labelFormatter: function () {
                            return " ";
                        }
                    };
                    chart.options.axisY = {
                        title: "",
                        interval: Math.ceil(totalVotes / 10)
                    };
                }

                chart.render();
            }

            function fetchAndGenerateGraphs(organization) {
                const graphType = $('#graph-type').val();

                $.ajax({
                    url: 'update_data.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        $('#results-container').empty();

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
                            }
                        };

                        var selectedCategories = categories[organization];

                        Object.keys(selectedCategories).forEach(function (category) {
                            if (response[category]) {
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
                fetchAndGenerateGraphs('csc');

                $('#organization-form').submit(function (event) {
                    event.preventDefault();
                    fetchAndGenerateGraphs($('#organization-select').val());
                });

                $('#graph-type').change(function () {
                    fetchAndGenerateGraphs($('#organization-select').val());
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
