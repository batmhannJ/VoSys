<head>
  <!-- Include Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <!-- Your existing code -->

    <section class="content">
      <!-- Your existing PHP code -->

      <!-- Display the results based on the selected organization -->
      <div>
        <canvas id="candidatesChart" width="400" height="200"></canvas>
      </div>

      <script>
        // Assuming your PHP variable $candidatesData contains the data for the chart
        var candidatesData = <?php echo json_encode($candidatesData); ?>;

        // Prepare data for Chart.js
        var labels = [];
        var data = [];

        <?php foreach ($candidatesData as $row) { ?>
          labels.push("<?php echo $row['organization'] . ' - ' . $row['position']; ?>");
          data.push(<?php echo $row['leading_votes']; ?>);
        <?php } ?>

        // Create a bar chart
        var ctx = document.getElementById('candidatesChart').getContext('2d');
        var candidatesChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Leading Votes',
              data: data,
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/votes_modal.php'; ?>
  <?php include 'includes/scripts.php'; ?>
</body>
</html>
