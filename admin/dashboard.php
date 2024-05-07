<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }

$query = "
    SELECT
        (SELECT COUNT(*) FROM election) AS total_elections,
        IFNULL((SELECT COUNT(*) FROM categories c WHERE c.election_id = e.id AND e.status = 1), 0) AS total_active_categories,
        (SELECT COUNT(*) FROM users WHERE type = 1) AS total_voters,
        COUNT(DISTINCT v.voter_id) AS total_voted,
        e.title AS election_title
    FROM
        election e
    LEFT JOIN
        users u ON u.type = 1
    LEFT JOIN
        votes v ON e.id = v.election_id
    WHERE
        e.status = 1;
";


$result = $conn->query($query);
?>
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            
            
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">
        <?php if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); ?>
            <div class="col-12">
                <div class="row">
                    <!-- Elections Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Elections <span>| All</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_elections"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Elections</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Elections Card -->

                    <!-- Categories Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Categories <span>| On Active Election</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-bookmark"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_active_categories"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Categories</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->
                </div>
                <div class="row">
                    <!-- Voters Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Voters <span>| Total</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_voters"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">All System Voters</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Voters Card -->

                    <!-- Voted Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Voted <span>| On Active Election</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo $row["total_voted"]; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">Total Voted</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Voted Card -->
                </div>
            </div>
        <?php } ?>
    </div>