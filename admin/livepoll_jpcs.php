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
        }

        .candidate-images {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .candidate-image img {
            width: 75px;
            height: 75px;
            margin: 0 10px;
            border-radius: 50%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .candidate-image img {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .candidate-image img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <script>
        function generateBarGraph(dataPoints, containerId) {
            const ctx = document.getElementById(containerId).getContext('2d');
            const totalVotes = dataPoints.reduce((acc, dp) => acc + dp.y, 0);
            const backgroundColors = dataPoints.map(() => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataPoints.map(dp => dp.label),
                    datasets: [{
                        label: 'Votes',
                        data: dataPoints.map(dp => dp.y),
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const percent = ((context.raw / totalVotes) * 100).toFixed(2);
                                    return `${context.label}: ${context.raw} votes (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: Math.ceil(totalVotes / 10)
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeOutBounce'
                    }
                }
            });
        }

        function generatePieChart(dataPoints, containerId) {
            const ctx = document.getElementById(containerId).getContext('2d');
            const totalVotes = dataPoints.reduce((acc, dp) => acc + dp.y, 0);
            const backgroundColors = dataPoints.map(() => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: dataPoints.map(dp => dp.label),
                    datasets: [{
                        data: dataPoints.map(dp => dp.y),
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.7', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const percent = ((context.raw / totalVotes) * 100).toFixed(2);
                                    return `${context.label}: ${context.raw} votes (${percent}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutCubic'
                    }
                }
            });
        }

        function fetchAndGenerateGraphs(organization, graphType) {
            $.ajax({
                url: 'update_jpcs_data.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear previous results
                    $('#results-container').empty();

                    // Define categories for each organization
                    const categories = {
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

                    const selectedCategories = categories[organization];

                    Object.keys(selectedCategories).forEach(function(category) {
                        if (response[category]) {
                            const containerHtml = `
                                <div class='col-md-12'>
                                    <div class='box'>
                                        <div class='box-header with-border'>
                                            <h3 class='box-title'><b>${selectedCategories[category]}</b></h3>
                                        </div>
                                        <div class='box-body'>
                                            <div class='chart-container'>
                                                <canvas id='${category}Graph'></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                            $('#results-container').append(containerHtml);

                            if (graphType === 'bar') {
                                generateBarGraph(response[category], `${category}Graph`);
                            } else if (graphType === 'pie') {
                                generatePieChart(response[category], `${category}Graph`);
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", status, error);
                }
            });
        }

        $(document).ready(function() {
            fetchAndGenerateGraphs('jpcs', 'bar');

            $('#organization-form').submit(function(event) {
                event.preventDefault();
                const selectedOrganization = $('#organization-select').val();
                const selectedGraphType = $('#graph-select').val();
                fetchAndGenerateGraphs(selectedOrganization, selectedGraphType);
            });

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
