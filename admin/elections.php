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
        Election Configuration
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Election Lists</li>
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
              <a href="#addElection" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
      </div>
      <!-- Table with hoverable rows -->
      <div class="box-body">
      <table id="example1" class="table table-bordered">
        <thead>
            <th scope="col">#</th>
            <th scope="col">Id</th>
            <th scope="col">Title</th>
            <th scope="col">Voters</th>
            <th scope="col">Status</th>
            <th class="text-center" scope="col">Action</th>
        </thead>
        <tbody class="election">
          <?php
          $i = 1;
          $election = $conn->prepare("SELECT * FROM election ORDER BY id DESC");
          $election->execute();
          $result = $election->get_result();
          while ($row = $result->fetch_assoc()) {

            echo '<tr>
                    <th scope="row">' . $i++ . '</th>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['title'].'</td>
                    <td>' . $row['voters'];
            '</td>';
            if ($row['status'] === 0) {
              echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-secondary election-status" data-id="' . $row['id'] . '" data-status="1" data-name="Activate">Not active</a></td>';
            } else {
              echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-success election-status" data-id="' . $row['id'] . '" data-status="0" data-name="Deactivate">Active</a></td>';
            }
            echo '<td class="text-center">
                        <a href="#" class="btn btn-primary btn-sm edit btn-flat" data-bs-toggle="modal" data-bs-target="#editElection" data-id="' . $row['id'] . '">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm delete btn-flat" data-bs-toggle="modal" data-bs-target="#deleteElection" data-id="' . $row['id'] . '" data-name="' . $row['title'] . '">Delete</a></td>
                  </tr>';
          } ?>
        </tbody>
      </table><!-- End Election lists Table -->

    </div>
  </div>
</div>
</section>
</div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/election_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });


});
function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'election_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id);
      $('#edit_title').val(response.title);
      $('#edit_voters').val(response.voters);
      $('#edit_starttime').val(response.starttime);
      $('#edit_endtime').val(response.endtime);
      $('#edit_status').val(response.status);
      $('.fullname').html(response.title);
    }
  });
}
    $(function () {
        $('#starttime').datetimepicker();
        $('#endtime').datetimepicker();
    });


  $(document).on('click', '.election-status', function(e) {
    e.preventDefault();

    var electionId = $(this).data('id');
    var status = $(this).data('status');
    var statusName = $(this).data('name'); // Corrected attribute name

    var confirmed = confirm('Are you sure you want to ' + statusName + ' this Election?');

    // If the user confirms, proceed with change election status
    if (confirmed) {
        $.ajax({
            type: 'POST',
            url: 'http://localhost/votesystem/admin/controllers/app.php?action=election_status',
            data: {
                election_id: electionId,
                status: status
            },
            dataType: 'json',
            beforeSend: function() {
                showLoadingOverlay();
            },
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Handle AJAX errors, if any
                console.error(error);
                hideLoadingOverlay();
                console.error('An error occurred during the request.', status, error);
            }
        });
    } else {
        // User canceled to change status
        toastr.info('Status change canceled.');
    }
});

</script>
</body>