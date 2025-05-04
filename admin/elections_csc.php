<?php include 'includes/session.php'; ?>
<?php include 'includes/header_csc.php'; ?>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_csc.php'; ?>
  <?php include 'includes/menubar_csc.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Election Configuration
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Election Lists</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }

        // Function to display election status
        function displayElectionStatus($row) {
            $data_attributes = $row['status'] === 1 ? ' data-endtime="' . htmlspecialchars($row['endtime']) . '" data-starttime="' . htmlspecialchars($row['starttime']) . '"' : '';
            if ($row['status'] === 0) {
                return '<td><a href="#" name="status" class="btn badge rounded-pill btn-secondary election-status" data-id="' . $row['id'] . '" data-status="1" data-name="Activate">Not Active</a></td>';
            } else {
                return '<td><a href="#" name="status" class="btn badge rounded-pill btn-success election-status"' . $data_attributes . ' data-id="' . $row['id'] . '" data-status="0" data-name="Deactivate">Active</a></td>';
            }
        }

        // Function to update election status based on end time
        function updateElectionStatusBasedOnTime($conn) {
            try {
                $currentTime = date('Y-m-d H:i:s');
                error_log("Running updateElectionStatusBasedOnTime at $currentTime");

                // Check active elections before updating
                $checkSql = "SELECT id, endtime, status FROM election WHERE status = 1 AND endtime <= ? AND organization = 'CSC' AND archived = FALSE";
                $checkStmt = $conn->prepare($checkSql);
                if (!$checkStmt) {
                    throw new Exception("Check prepare failed: " . $conn->error);
                }
                $checkStmt->bind_param('s', $currentTime);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $electionsToUpdate = [];
                while ($row = $result->fetch_assoc()) {
                    $electionsToUpdate[] = $row['id'] . ' (endtime: ' . $row['endtime'] . ', status: ' . $row['status'] . ')';
                }
                $electionsToUpdateCount = count($electionsToUpdate);
                error_log("Found $electionsToUpdateCount elections to deactivate: " . implode(', ', $electionsToUpdate) . " at $currentTime");
                $checkStmt->close();

                // Deactivate expired elections
                $sql = "UPDATE election SET status = 0 WHERE status = 1 AND endtime <= ? AND organization = 'CSC' AND archived = FALSE";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param('s', $currentTime);
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                $affected_rows = $stmt->affected_rows;
                $stmt->close();

                error_log("Updated $affected_rows elections to Not Active at $currentTime");
                return $affected_rows;
            } catch (Exception $e) {
                error_log("Error updating election status: " . $e->getMessage());
                return false;
            }
        }

        // Call the function to update statuses before displaying the table
        $result = updateElectionStatusBasedOnTime($conn);
        if ($result === false) {
            error_log("Failed to run updateElectionStatusBasedOnTime before rendering table on " . date('Y-m-d H:i:s'));
        } else {
            error_log("Successfully ran updateElectionStatusBasedOnTime, updated $result rows on " . date('Y-m-d H:i:s'));
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-hover">
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
                      $election = $conn->prepare("SELECT * FROM election WHERE organization = 'CSC' AND archived = FALSE");
                      $election->execute();
                      $result = $election->get_result();
                      while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <th scope="row">' . $i++ . '</th>
                                <td>'.$row['id'].'</td>
                                <td>'.$row['title'].'</td>
                                <td>' . $row['voters'] . '</td>';
                        echo displayElectionStatus($row);
                        echo '<td class="text-center">
                                <a href="#" class="btn btn-primary btn-sm edit btn-flat" data-bs-toggle="modal" data-bs-target="#editElection" data-id="' . $row['id'] . '">Edit</a>
                                <a href="#" class="btn btn-warning btn-sm archive btn-flat" data-bs-toggle="modal" data-bs-target="#confirmationModal" data-id="' . $row['id'] . '" data-name="' . $row['title'] . '">Archive</a>
                              </td>
                            </tr>';
                      }
                    ?>
                  </tbody>
                </table><!-- End Election lists Table -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/election_modal_csc.php'; ?>

  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
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
                        <span aria-hidden="true">×</span>
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
          <button type="button" class="close" data-dismiss="modal">×</button>
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

  $(document).on('click', '.archive', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#submitBtn').attr('data-id', id);
    $('#confirmationModal').modal('show');
  });

  $('#submitBtn').on('click', function() {
    var id = $(this).data('id');
    archiveElection(id);
  });

  $(document).on('click', '.election-status', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var status = $(this).data('status');

    if (status === 1) {
        $('#activation_id').val(id);
        $('#activationModal').modal('show');
    } else {
        $('#deactivationModal').modal('show');
        $('#deactivationSubmit').attr('data-id', id);
    }
  });

  $('#activationForm').on('submit', function(e) {
    e.preventDefault();
    var id = $('#activation_id').val();
    var starttime = $('#start_time').val();
    var endtime = $('#end_time').val();

    if (new Date(endtime) <= new Date(starttime)) {
        toastr.error('End time must be after start time.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'change_status.php',
        data: { id: id, status: 1, starttime: starttime, endtime: endtime },
        dataType: 'json',
        beforeSend: function() {
            if (typeof showLoadingOverlay === 'function') showLoadingOverlay();
        },
        success: function(response) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            if (response && response.status === 'success') {
                toastr.success(response.message);
                $('#activationModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response && response.message ? response.message : 'Failed to activate election.');
            }
        },
        error: function(xhr, status, error) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            console.error('AJAX Error (Activation):', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            toastr.error('An error occurred during activation. Check console for details.');
        }
    });
  });

  $('#deactivationSubmit').on('click', function() {
    var id = $(this).data('id');

    $.ajax({
        type: 'POST',
        url: 'change_status.php',
        data: { id: id, status: 0, starttime: null, endtime: null },
        dataType: 'json',
        beforeSend: function() {
            if (typeof showLoadingOverlay === 'function') showLoadingOverlay();
        },
        success: function(response) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            if (response && response.status === 'success') {
                toastr.success(response.message);
                $('#deactivationModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response && response.message ? response.message : 'Failed to deactivate election.');
            }
        },
        error: function(xhr, status, error) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            console.error('AJAX Error (Deactivation):', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            toastr.error('An error occurred during deactivation. Check console for details.');
        }
    });
  });

  function checkElectionEndTimes() {
      const activeElections = $('.election-status[data-status="0"]');
      let earliestEndTime = null;
      let earliestEndTimeStr = null;

      activeElections.each(function() {
          const endTimeStr = $(this).data('endtime');
          if (endTimeStr) {
              const endTime = new Date(endTimeStr);
              if (!isNaN(endTime.getTime()) && (earliestEndTime === null || endTime < earliestEndTime)) {
                  earliestEndTime = endTime;
                  earliestEndTimeStr = endTimeStr;
              }
          }
      });

      if (earliestEndTime && earliestEndTime > new Date()) {
          const timeUntilEnd = earliestEndTime - new Date();
          console.log(`Scheduling status update and reload for ${earliestEndTimeStr} in ${timeUntilEnd / 1000} seconds`);
          
          setTimeout(function() {
              console.log('Election endtime reached, updating statuses...');
              $.ajax({
                  type: 'POST',
                  url: 'update_expired_elections.php',
                  dataType: 'json',
                  success: function(response) {
                      if (response && response.status === 'success') {
                          console.log(response.message);
                          toastr.info('An election has ended, reloading page...');
                          setTimeout(function() {
                              location.reload();
                          }, 2000);
                      } else {
                          console.error('Failed to update election statuses:', response.message);
                          toastr.error(response.message || 'Failed to update election statuses.');
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('AJAX Error (Status Update):', status, error, xhr.responseText);
                      toastr.error('An error occurred while updating election statuses.');
                  }
              });
          }, timeUntilEnd + 5000); // 5-second buffer
      } else {
          console.log('No valid future endtimes found or all endtimes passed');
      }

      // Check again after 30 seconds
      setTimeout(checkElectionEndTimes, 30000);
  }

  // Start checking end times on page load
  checkElectionEndTimes();
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
    },
    error: function(xhr, status, error) {
      console.error('Error fetching row:', status, error);
    }
  });
}

function archiveElection(id) {
    $.ajax({
        type: "POST",
        url: "archive_election.php",
        data: { id: id },
        dataType: 'json',
        beforeSend: function() {
            if (typeof showLoadingOverlay === 'function') showLoadingOverlay();
        },
        success: function(response) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            if (response && response.status === 'success') {
                toastr.success(response.message || 'Election archived successfully.');
                $('#confirmationModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(response && response.message ? response.message : 'Failed to archive election.');
            }
        },
        error: function(xhr, status, error) {
            if (typeof hideLoadingOverlay === 'function') hideLoadingOverlay();
            console.error('Archive Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            toastr.error('An error occurred while archiving. Check console for details.');
        }
    });
}
</script>
</body>
</html>