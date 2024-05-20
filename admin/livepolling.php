<!DOCTYPE HTML>
<html>
<head>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            // Initialize an array to store candidate charts
            var candidateCharts = [];

            // Function to generate a bar graph for a candidate with its own axis
            function generateCandidateChart(candidateName, containerId, dataPoints) {
                var chart = new CanvasJS.Chart(containerId, {
                    animationEnabled: true,
                    title:{
                        text: "Vote Counts for " + candidateName
                    },
                    axisY: {
                        title: "Vote Count"
                    },
                    data: [{
                        type: "column",
                        dataPoints: dataPoints
                    }]
                });
                chart.render();
                return chart;
            }

            <?php
            // Fetch candidate data and generate charts
            $candidateData = [
                ["name" => "Candidate 1", "dataPoints" => [
                    ["label" => "Organization 1", "y" => 50],
                    ["label" => "Organization 2", "y" => 70],
                    ["label" => "Organization 3", "y" => 85],
                ]],
                ["name" => "Candidate 2", "dataPoints" => [
                    ["label" => "Organization 1", "y" => 60],
                    ["label" => "Organization 2", "y" => 80],
                    ["label" => "Organization 3", "y" => 95],
                ]],
                // Add more candidate data as needed
            ];

            foreach ($candidateData as $candidate) {
                echo "candidateCharts.push(generateCandidateChart('" . $candidate['name'] . "', '" . str_replace(' ', '', $candidate['name']) . "Graph', " . json_encode($candidate['dataPoints']) . "));\n";
            }
            ?>

            // Function to update data for a candidate's chart
            function updateCandidateChart(candidateIndex, newDataPoints) {
                candidateCharts[candidateIndex].options.data[0].dataPoints = newDataPoints;
                candidateCharts[candidateIndex].render();
            }

            // Example: Update data for Candidate 1 every 5 seconds
            setInterval(function() {
                var newDataPoints = [
                    { label: "Organization 1", y: Math.floor(Math.random() * 100) },
                    { label: "Organization 2", y: Math.floor(Math.random() * 100) },
                    { label: "Organization 3", y: Math.floor(Math.random() * 100) },
                ];
                updateCandidateChart(0, newDataPoints); // Update the first candidate's chart
            }, 5000);
        }
    </script>
</head>
<body>
<div class="wrapper">
    <!-- Your HTML content here -->
</div>
</body>
</html>
