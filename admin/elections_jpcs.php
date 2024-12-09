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

<!-- Modal for Election Activation (for Not Active elections) -->
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
                <form id="activationForm" action="change_status.php">
                    <div class="form-group">
                        <label for="starttime">Start Time</label>
                        <input type="datetime-local" class="form-control" id="starttime" name="starttime" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                      <label for="endtime">End Time</label>
                      <input type="datetime-local" class="form-control" id="endtime" name="endtime" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitActivationBtn">Activate Election</button>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/scripts.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(function () {
    // Show edit modal and fetch data
    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#edit').modal('show');
        getRow(id);
    });

    // Show delete modal and fetch data
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#delete').modal('show');
        getRow(id);
    });

    $(document).on('click', '.election-status', function (e) {
    e.preventDefault();
    const electionId = $(this).data('id');
    const currentStatus = $(this).data('status'); // 1 for activate, 0 for deactivate
    $('#submitActivationBtn').data('id', electionId); // Bind the ID to the modal button
    $('#activationModal').modal('show');
});

    // Show archive modal
    $(document).on('click', '.archive', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#submitBtn').attr('data-id', id); // Pass the ID to the button
        $('#confirmationModal').modal('show');
    });

    // Archive election
    $('#submitBtn').on('click', function () {
        const id = $(this).data('id'); // Retrieve ID from button
        archiveElection(id);
    });

    // Activate election
    $('#submitActivationBtn').on('click', function () {
        const electionId = $(this).data('id');
        const startTime = $('#starttime').val();
        const endTime = $('#endtime').val();

        // Validate input fields
        if (startTime && endTime) {
            $.ajax({
                type: 'POST',
                url: 'change_status.php',
                data: {
                    election_id: electionId,
                    start_time: startTime,
                    end_time: endTime
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success('Election activated successfully!');
                        location.reload();
                    } else {
                        toastr.error(response.error || 'Failed to activate election.');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.error('An error occurred while activating the election.');
                }
            });
        } else {
            toastr.error('Please fill in both start and end times.');
        }
    });

    // Initialize datetime pickers
    $('#starttime, #endtime').datetimepicker();

    // Fetch row data
    function getRow(id) {
        $.ajax({
            type: 'POST',
            url: 'election_row.php',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response) {
                    $('.id').val(response.id);
                    $('#edit_title').val(response.title);
                    $('#edit_voters').val(response.voters);
                    $('#edit_starttime').val(response.starttime);
                    $('#edit_endtime').val(response.endtime);
                    $('#edit_status').val(response.status);
                    $('.fullname').text(response.title);
                } else {
                    toastr.error('Failed to fetch election data.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('An error occurred while fetching election data.');
            }
        });
    }

    // Archive election
    function archiveElection(id) {
        $.ajax({
            type: "POST",
            url: "archive_election.php",
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    toastr.success('Election archived successfully!');
                    location.reload();
                } else {
                    toastr.error(response.error || 'Failed to archive election.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('An error occurred while archiving the election.');
            }
        });
    }
});

</script>

</body>