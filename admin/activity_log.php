<?php include 'includes/session.php'; ?>
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
        Activity Log
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Activity Log</li>
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
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#reset" data-toggle="modal" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-refresh"></i> Reset</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>No.</th>
                  <th>Voters ID</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Activity</th>
                  <th> Date and Time</th>
                </thead>
                <tbody>
                <?php
                  $sql = "SELECT 
                              activity_log.voters_id AS voters_id, 
                              activity_log.email AS email, 
                              activity_log.activity_type AS activity_type,
                              activity_log.activity_time AS activity_time,
                              voters.firstname AS votfirst, 
                              voters.lastname AS votlast
                          FROM activity_log 
                          LEFT JOIN voters ON voters.voters_id = activity_log.voters_id 
                          ORDER BY activity_log.id ASC";
                          
                  $query = $conn->query($sql);
                  $i = 1;
                  while ($row = $query->fetch_assoc()) {
                      echo "
                          <tr>
                              <td class='hidden'></td>
                              <td>".$i++."</td>
                              <td>".$row['voters_id']."</td>
                              <td>".$row['email']."</td>
                              <td>".$row['votfirst'].' '.$row['votlast']."</td>
                              <td>".$row['activity_type']."</td>
                              <td>".$row['activity_time']."</td>
                          </tr>
                      ";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
