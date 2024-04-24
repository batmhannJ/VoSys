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
        <?php
          if(isset($_GET['type']) && $_GET['type'] === 'voters'){
            echo "Voter Archived";
          } elseif(isset($_GET['type']) && $_GET['type'] === 'admin'){
            echo "Admin Archived";
          } else {
            echo "Archived";
          }
        ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">
          <?php
            if(isset($_GET['type']) && $_GET['type'] === 'voters'){
              echo "Voter Archived";
            } elseif(isset($_GET['type']) && $_GET['type'] === 'admin'){
              echo "Admin Archived";
            } else {
              echo "Archived";
            }
          ?>
        </li>
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
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="archiveDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Select Archive Type
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="archiveDropdown">
                    <li><a href="?type=voters" class="archive-type">Voters Archived</a></li>
                    <li><a href="?type=admin" class="archive-type">Admin Archived</a></li>
                </ul>
            </div>
          </div>
          <div class="box">
            <div class="box-header with-border">
              <a href="#restoreAllModal" data-toggle="modal" class="btn btn-primary btn-sm btn-flat restore-all"><i class="fa fa-reply"></i> Restore All</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <?php if(isset($_GET['type']) && $_GET['type'] === 'voters'): ?>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Photo</th>
                  <th>Voters ID</th>
                  <th>Email</th>
                  <th>Year Level</th>
                  <th>Organization</th>
                  <?php elseif(isset($_GET['type']) && $_GET['type'] === 'admin'): ?>
                  <th>ID Number</th>
                  <th>Organization</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Photo</th>
                  <th>Username</th>
                  <th>Email</th>
                  <?php endif; ?>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                      $sql = "SELECT * FROM voters WHERE archived = TRUE";
                    } elseif(isset($_GET['type']) && $_GET['type'] === 'admin') {
                      $sql = "SELECT * FROM admin WHERE archived = TRUE";
                    }
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                        // For voters table
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        echo "
                          <tr>
                            <td>".$row['lastname']."</td>
                            <td>".$row['firstname']."</td>
                            <td>
                            <img src='".$image."' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'><span class='fa fa-edit'></span></a>
                          </td>
                            <td>".$row['voters_id']."</td>
                            <td>".$row['email']."</td>
                            <td>".$row['yearLvl']."</td>
                            <td>".$row['organization']."</td>
                            <td>
                              <button class='btn btn-success btn-sm restore btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#confirmationModal'><i class='fa fa-reply'></i> Restore</button>
                            </td>
                          </tr>
                        ";
                      } elseif(isset($_GET['type']) && $_GET['type'] === 'admin') {
                          // For admin table
                          $adminImage = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                          echo "
                              <tr>
                                  <td>".$row['id']."</td>
                                  <td>".$row['organization']."</td>
                                  <td>".$row['lastname']."</td>
                                  <td>".$row['firstname']."</td>
                                  <td>
                                      <img src='".$adminImage."' width='30px' height='30px'>
                                  </td>
                                  <td>".$row['username']."</td>
                                  <td>".$row['email']."</td>
                                  <td>
                                      <button class='btn btn-success btn-sm restore-admin btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#adminConfirmationModal'><i class='fa fa-reply'></i> Restore</button>
                                  </td>
                              </tr>
                          ";
                      }

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
  <?php include 'includes/voters_modal.php'; ?>
  <?php include 'includes/restore_modal.php'; ?>
  <?php include 'includes/restore_admin_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.restore', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#submitBtn').attr('data-id', id); // Set the data-id attribute for the "Restore" button
  });

  $(document).on('click', '.restore-admin', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#adminSubmitBtn').attr('data-id', id); // Set the data-id attribute for the "Restore Admin" button
  });

  $(document).on('click', '.restore-all', function(e){
    e.preventDefault();
    $('#restoreAllModal').modal('show'); // Show the "Restore All" modal
  });

  $(document).on('click', '#confirmRestoreAll', function(e){
    e.preventDefault();
    restoreAllVoters();
  });

  $(document).on('click', '#submitBtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    restoreVoter(id);
  });

  $(document).on('click', '#adminSubmitBtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    restoreAdmin(id);
  });

  function restoreVoter(id) {
    $.ajax({
      type: "POST",
      url: "restore_voter.php",
      data: { id: id },
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  function restoreAdmin(id) {
    $.ajax({
      type: "POST",
      url: "restore_admin.php",
      data: { id: id },
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  function restoreAllVoters() {
    $.ajax({
      type: "POST",
      url: "restore_all_voters.php",
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
});
</script>
</body>
</html>
