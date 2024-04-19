<?php
  include 'includes/session.php';
  ?>

  <?php include 'includes/header.php'; ?>
  <body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Announcement</h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Announcement</li>
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
        <!-- Organization Filter -->
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border" style="font-size: 20px;"><i class="fa fa-bullhorn"></i> Announcement Form</div>
              <div class="box-body">
                  <div class="row">
                    <div class="col">
                      <form method="POST" action="create_announce.php">
                       <textarea style="width: 95%; margin-left: 20px;" name="announcement" class="form-control" rows="6" placeholder="Enter Message Here"></textarea>
                    </div>
                  </div>
                  <?php
                  // Set the timezone to Asia/Singapore
                  date_default_timezone_set('Asia/Singapore');
                  // Get the current date in the desired format
                  $cdate = date('Y-m-d H:i:s'); // Adjust the format as needed
                  ?>
                  <div class="row">
                    <div class="col">
                      <input type="hidden" name="startdate" value="<?= $cdate ?>">
                      <input type="hidden" name="addedby" value="<?= $user['lastname'] ?>, <?= $user['firstname'] ?>">
                      <button type="submit" class="btn btn-success" style="margin-left: 89%; font-size: 15px;">Announce</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border" style="font-size: 20px;">List of Announcements</div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead style="background-color: #800000; color: #fff;">
                    <tr>
                      <th>Actions</th>
                      <th>Announcement No.</th>
                      <th>Announcement</th>
                      <th>Date Posted</th>
                      <th>Added By</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *, announcement.id_announcement AS id, 
                            announcement.announcement AS announcement, 
                            announcement.startdate AS start, 
                            announcement.addedby AS added
                            FROM announcement
                            GROUP BY announcement.id_announcement
                            ORDER BY announcement.startdate ASC";
                    $query = $conn->query($sql);
                    $counter = 1;
                    while ($row = $query->fetch_assoc()) {
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>
                              <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Edit</button>
                              <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          <td>" . ($counter++) . "</td>
                          <td>" . $row['announcement'] . "</td>
                          <td>" . $row['startdate'] . "</td>
                          <td>" . $row['addedby'] . "</td>
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
        <br><br>
      </section>  
      <!-- /.content -->
    </div>
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/announcement_modal.php'; ?>
    <!-- /.content-wrapper -->
  </div>
  <!-- ./wrapper -->  
  <?php include 'includes/scripts.php'; ?>

  <script>
  $(function(){
    $(document).on('click', '.editAnnouncement', function(e){
      e.preventDefault();
      $('#editAnnouncement').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });

    $(document).on('click', '.deleteAnnouncement', function(e){
      e.preventDefault();
      $('#deleteAnnouncement').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });

  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'announcement_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('.id').val(response.id);
        $('#edit_ann.no').val(response.id);
        $('#edit_announcement').val(response.announcement);
        $('#edit_startdate').val(response.startdate);
        $('#edit_addedby').val(response.addedby);
      }
    });
  }
  });
  </script>
  </body>
  </html>
