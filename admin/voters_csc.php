<?php include 'includes/session.php'; ?>
<?php include 'includes/header_csc.php'; ?>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_csc.php'; ?>
  <?php include 'includes/menubar_csc.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Voters List
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Voters</li>
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
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
              <button class="btn btn-warning btn-sm btn-flat" id="batchArchiveBtn"><i class="fa fa-archive"></i> Batch Archive</button>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <th><input type="checkbox" id="selectAll"></th> <!-- Select All Checkbox -->
                <th>Full Name</th>
                <th>Photo</th>
                <th>Voters ID</th>
                <th>Email</th>
                <th>Year Level</th>
                <th>Organization</th>
                <th>Tools</th>
              </thead>
              <tbody>
                <?php
                  $sql = "SELECT * FROM voters WHERE archived = FALSE";
                  $query = $conn->query($sql);
                  $i = 1;
                  while($row = $query->fetch_assoc()){
                    $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                    $fullname = $row['lastname'] . ', ' . $row['firstname'];
                    echo "
                      <tr>
                        <td><input type='checkbox' class='selectItem' value='".$row['id']."'></td> <!-- Individual Checkbox -->
                        <td>".$fullname."</td>
                        <td>
                          <img src='".$image."' width='30px' height='30px'>
                          <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'></a>
                        </td>
                        <td>".$row['voters_id']."</td>
                        <td>".$row['email']."</td>
                        <td>".$row['yearLvl']."</td>
                        <td>".$row['organization']."</td>
                        <td>
                          <button class='btn btn-primary btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                          <button class='btn btn-warning btn-sm archive btn-flat' data-id='".$row['id']."'><i class='fa fa-archive'></i> Archive</button>
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
      </div>
      <div class="row">
        <div class="col-xs-12">
          <?php
                if(isset($_SESSION['message']))
                {
                  echo "<div class='alert alert-danger alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-warning'></i> Error!</h4>
                    ".$_SESSION['message']."
                  </div>
                ";
                    unset($_SESSION['message']);
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
          <div class="box">
            <div class="box-header with-border">
              <h4>Upload Voters</h4>
            <form action="upload_csc.php" method="POST" enctype="multipart/form-data">
            <input type="file" accept=".xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="import_file" class="form-control" />
            <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>
          </form>
          </div>
        </div>
        </div>
      </div>

    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/voters_csc_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.archive', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    archiveVoter(id);
  });

  // Batch Archive Button Click
  $('#batchArchiveBtn').click(function() {
    var selected = [];
    $('.selectItem:checked').each(function() {
      selected.push($(this).val());
    });

    if(selected.length > 0) {
      $('#batchArchiveModal').modal('show'); // Show the batch archive confirmation modal
    } else {
      alert('No voters selected.');
    }
  });

  // Confirm Batch Archive
  $('#confirmBatchArchive').on('click', function() {
    var selected = [];
    $('.selectItem:checked').each(function() {
      selected.push($(this).val());
    });

    $.ajax({
      type: 'POST',
      url: 'batch_archive_voter.php',
      data: { ids: selected },
      success: function(response) {
        console.log('Batch archive successful:', response);
        location.reload(); // Reload the page after successful archive
      },
      error: function(xhr, status, error) {
        console.error('Batch archive error:', xhr.responseText);
      }
    });
  });

  // Select all checkboxes
  $('#selectAll').click(function() {
    $('.selectItem').prop('checked', this.checked);
  });
});

function archiveVoter(id) {
  $('#confirmationModal').modal('show'); // Show the confirmation modal

  $('#submitBtn').on('click', function() {
    $.ajax({
      type: "POST",
      url: "archive_voter.php",
      data: { id: id },
      success: function(response) {
        location.reload(); // Refresh the page after archiving
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });
}

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'voters_row.php',
    data: { id: id },
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_email').val(response.email);
      $('#edit_yearlvl').val(response.yearLvl);
      $('#edit_password').val(response.password);
      $('.fullname').html(response.firstname + ' ' + response.lastname);
    },
    error: function(xhr, status, error) {
      console.error('Get row error:', xhr.responseText);
    }
  });
}

// Hide success message after 3 seconds
setTimeout(function() {
  var successAlert = document.querySelector('.alert-success');
  if (successAlert) {
    successAlert.style.display = 'none';
  }
}, 3000);
</script>

</body>
</html>
