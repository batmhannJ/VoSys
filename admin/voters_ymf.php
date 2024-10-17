<?php include 'includes/session.php'; ?>
<?php include 'includes/header_ymf.php'; ?>
<body class="hold-transition skin-darkblue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar_ymf.php'; ?>
  <?php include 'includes/menubar_ymf.php'; ?>

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
                <table id="example1" class="table table-bordered">
                <thead>
                  <th>#</th>
                  <th>Lastname</th>
                  <th>Firstname</th>
                  <th>Photo</th>
                  <th>Voters ID</th>
                  <th>Email</th>
                  <th>Year Level</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM voters WHERE archived = FALSE AND organization = 'JPCS'";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                      echo "
                        <tr>
                          <td><input type='checkbox' class='selectItem' value='".$row['id']."'></td>
                          <td>".$row['lastname']."</td>
                          <td>".$row['firstname']."</td>
                          <td>
                            <img src='".$image."' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'></a>
                          </td>
                          <td>".$row['voters_id']."</td>
                          <td>".$row['email']."</td>
                          <td>".$row['yearLvl']."</td>
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
            <form action="upload_jpcs.php" method="POST" enctype="multipart/form-data">
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
  <?php include 'includes/voters_jpcs_modal.php'; ?>
  <?php include 'includes/batch_modal.php'; ?>
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

  $('#batchArchiveBtn').click(function() {
    var selected = [];
    $('.selectItem:checked').each(function() {
      selected.push($(this).val());
    });

    if(selected.length > 0) {
      $('#batchConfirmationModal').modal('show');
      $('#submitBatchBtn').on('click', function() {
        $.ajax({
          type: 'POST',
          url: 'batch_archive_voter.php',
          data: { ids: selected },
          success: function(response) {
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      });
    } else {
      alert('No voters selected.');
    }
  });

  $('#selectAll').click(function() {
    if (this.checked) {
      $('.selectItem').each(function() {
        this.checked = true;
      });
    } else {
      $('.selectItem').each(function() {
        this.checked = false;
      });
    }
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
        // Refresh the page or update the table as needed
        location.reload();
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
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_email').val(response.email);
      $('#edit_yearlvl').val(response.yearLvl);
      $('#edit_password').val(response.password);
      $('.fullname').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>
</body>
</html>
