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
        Voter Archive List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Archived</li>
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
              <a href="#restoreAllModal" data-toggle="modal" class="btn btn-primary btn-sm btn-flat restore-all"><i class="fa fa-reply"></i> Restore All</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Photo</th>
                  <th>Voters ID</th>
                  <th>Email</th>
                  <th>Year Level</th>
                  <th>Organization</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM voters WHERE archived = TRUE";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                      echo "
                        <tr>
                          <td>".$row['lastname']."</td>
                          <td>".$row['firstname']."</td>
                          <td>
                            <img src='".$image."' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'></a>
                          </td>
                          <td>".$row['voters_id']."</td>
                          <td>".$row['email']."</td>
                          <td>".$row['yearLvl']."</td>
                          <td>".$row['organization']."</td>
                          <td>
                            <button class='btn btn-success btn-sm restore btn-flat' data-id='".$row['id']."'><i class='fa fa-reply'></i> Restore</button>
                          </td>
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
  <?php include 'includes/voters_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<!-- Restore All Modal -->
<div class="modal fade" id="restoreAllModal" tabindex="-1" role="dialog" aria-labelledby="restoreAllModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreAllModalLabel">Restore All Voters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore all voters?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmRestoreAll">Yes, Restore All</button>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
  $(document).on('click', '.restore', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#confirmationModal').modal('show'); // Show the confirmation modal
    $('#submitBtn').on('click', function() {
        restoreVoter(id);
    });
  });

  $(document).on('click', '.restore-all', function(e){
    e.preventDefault();
    $('#restoreAllModal').modal('show'); // Show the "Restore All" modal
  });

  $(document).on('click', '#confirmRestoreAll', function(e){
    e.preventDefault();
    restoreAllVoters();
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
