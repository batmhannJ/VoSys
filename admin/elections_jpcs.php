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

<!-- Activation Modal -->
<div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="activationForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="activationModalLabel">Set Activation Duration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="activation_id" name="id">
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="datetime-local" id="start_time" name="starttime" class="form-control" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="datetime-local" id="end_time" name="endtime" class="form-control" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="activationSubmit">Activate</button>
                </div>
            </form>
        </div>
    </div>
</div> 

<!-- Deactivation Modal -->
<div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Deactivate Election</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to deactivate this election?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="deactivationSubmit">Deactivate</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Election Modal -->
<div class="modal fade" id="editElection" tabindex="-1" role="dialog" aria-labelledby="editElectionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editElectionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editElectionLabel">Edit Election</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_title">Election Title</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                        <input type="hidden" id="edit_id" name="id">
                    </div>
                </div>
                <div class="form-group">
                      <label for="academicYear">Academic Year</label>
                      <select class="form-control" id="academicYear" name="academic_yr" required>
                          <option value="" disabled selected>Select Academic Year</option>
                          <?php
                          // Generate year range starting from current year
                          $currentYear = date("Y");
                          for ($i = 0; $i < 20; $i++) {
                              $startYear = $currentYear + $i;
                              $endYear = $startYear + 1;
                              echo "<option value='{$startYear} - {$endYear}'>{$startYear} - {$endYear}</option>";
                          }
                          ?>
                      </select>
                  </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editSubmit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<?php include 'includes/scripts.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function() {
  $(document).on('click', '.edit', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  function getRow(id) {
    $.ajax({
      type: 'POST',
      url: 'election_row.php',
      data: {id: id},
      dataType: 'json',
      success: function(response) {
        $('#edit_title').val(response.title);
        $('#edit_id').val(response.id);
        $('#academicYear').val(response.academic_yr); // Set the academic year value in the dropdown
        $('#editElection').modal('show');
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  // Handle form submission
  $('#editElectionForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#edit_id').val();
    var title = $('#edit_title').val();
    var academicYear = $('#academicYear').val();

    $.ajax({
      type: 'POST',
      url: 'election_edit_jpcs.php',
      data: {id: id, title: title, academic_yr: academicYear},
      success: function(response) {
        $('#editElection').modal('hide');
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
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
    var id = $(this).data('id');
    var status = $(this).data('status'); // 1 = Activate, 0 = Deactivate

    if (status === 1) { // Activate
        $('#activationModal').modal('show');
        $('#activationSubmit').attr('data-id', id);
    } else { // Deactivate
        $('#deactivationModal').modal('show');
        $('#deactivationSubmit').attr('data-id', id);
    }
});

// Event handler for activation modal submit button
$('#activationSubmit').on('click', function() {
    var id = $(this).data('id');
    var starttime = $('#start_time').val(); // Corrected to use the correct input ID
    var endtime = $('#end_time').val(); // Corrected to use the correct input ID

    $.ajax({
        type: 'POST',
        url: 'change_status.php',
        data: { id: id, status: 1, starttime: starttime, endtime: endtime },
        success: function(response) {
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

// Event handler for deactivation modal submit button
$('#deactivationSubmit').on('click', function() {
    var id = $(this).data('id');

    $.ajax({
        type: 'POST',
        url: 'change_status.php',
        data: { id: id, status: 0 },
        success: function(response) {
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
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