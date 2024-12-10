<?php include 'includes/session.php'; ?>
<?php include 'includes/header_jpcs.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_jpcs.php'; ?>
  <?php include 'includes/menubar_jpcs.php'; ?>

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
            <th scope="col">Title</th>
            <th scope="col">Academic Year</th>
            <th scope="col">Status</th>
            <th class="text-center" scope="col">Action</th>
        </thead>
        <tbody class="election">
          <?php
          $i = 1;
          $election = $conn->prepare("SELECT * FROM election WHERE archived = FALSE AND organization = 'JPCS'");
          $election->execute();
          $result = $election->get_result();
          while ($row = $result->fetch_assoc()) {

            echo '<tr>
                    <th scope="row">' . $i++ . '</th>
                    <td>'.$row['title'].'</td>
                    <td>' . $row['academic_yr'];
            '</td>';
            if ($row['status'] === 0) {
              echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-secondary election-status" data-id="' . $row['id'] . '" data-status="1" data-name="Activate">Not active</a></td>';
            } else {
              echo '<td><a href="#" name="status" class="btn badge rounded-pill btn-success election-status" data-id="' . $row['id'] . '" data-status="0" data-name="Deactivate">Active</a></td>';
            }
            echo '<td class="text-center">
                        <a href="#" class="btn btn-primary btn-sm edit btn-flat" data-bs-toggle="modal" data-bs-target="#editElection" data-id="' . $row['id'] . '">Edit</a>
                        <a href="#" class="btn btn-warning btn-sm archive btn-flat" data-bs-toggle="modal" data-bs-target="#confirmationModal" data-id="' . $row['id'] . '" data-name="' . $row['title'] . '">Archive</a></td>
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
  <?php include 'includes/election_modal_jpcs.php'; ?>

  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 

            <div class="modal-body">
                <p>Are you sure you want to archive this Election?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitBtn">Yes, Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activationModalLabel">Activate Election</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="starttime">Start Time:</label>
                <input type="text" id="starttime" class="form-control">
                <label for="endtime">End Time:</label>
                <input type="text" id="endtime" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="activateBtn">Activate</button>
            </div>
        </div>
    </div>
</div>


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

    $(document).on('click', '.archive', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#submitBtn').attr('data-id', id); // Set data-id attribute to the button
    $('#confirmationModal').modal('show'); // Show the confirmation modal
  });

  // Event handler for modal submit button
  $('#submitBtn').on('click', function() {
    var id = $(this).data('id'); // Get the id from data-id attribute
    archiveElection(id);
  });


    $(document).on('click', '.election-status', function(e) {
    e.preventDefault();

    var electionId = $(this).data('id');
    var status = $(this).data('status');
    var statusName = $(this).data('name'); // Corrected attribute name

    var confirmed = confirm('Are you sure you want to ' + statusName + ' this Election?');

    if (confirmed) {
        $.ajax({
            type: 'POST',
            url: 'change_status.php',
            data: {
                election_id: electionId,
                status: status
            },
            dataType: 'json',
            beforeSend: function() {
                showLoadingOverlay();
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload page after successful update
                } else {
                    toastr.error('Failed to update status.');
                }
                hideLoadingOverlay();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                toastr.error('An error occurred. Please try again.');
                hideLoadingOverlay();
            }
        });
    } else {
        toastr.info('Status change canceled.');
    }
});

function archiveElection(id) {
    $.ajax({
        type: "POST",
        url: "archive_election.php",
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
</script>

</body>