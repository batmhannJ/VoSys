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
          } elseif(isset($_GET['type']) && $_GET['type'] === 'election'){
            echo "Election Archived";
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
            } elseif(isset($_GET['type']) && $_GET['type'] === 'election'){
              echo "Election Archived";
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
                    <li><a href="?type=election" class="archive-type">Election Archived</a></li>
                </ul>
            </div>
          </div>
          <div class="box">
            <div class="box-header">
              <button id="batch-restore" class="btn btn-success btn-sm"><i class="fa fa-reply"></i> Batch Restore</button>
              <button id="batch-delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Batch Delete</button>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="select-all"></th>
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
                    <?php elseif(isset($_GET['type']) && $_GET['type'] === 'election'): ?>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Voters</th>
                    <?php endif; ?>
                    <th>Tools</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                      $sql = "SELECT * FROM voters WHERE archived = TRUE";
                    } elseif(isset($_GET['type']) && $_GET['type'] === 'admin') {
                      $sql = "SELECT * FROM admin WHERE archived = TRUE";
                    } elseif(isset($_GET['type']) && $_GET['type'] === 'election') {
                      $sql = "SELECT * FROM election WHERE archived = TRUE";
                    }
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      echo "<tr>";
                      echo "<td><input type='checkbox' class='record-checkbox' value='".$row['id']."'></td>";
                      if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                        // Voters table columns
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        echo "<td>".$row['lastname']."</td>";
                        echo "<td>".$row['firstname']."</td>";
                        echo "<td><img src='".$image."' width='30px' height='30px'><a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'><span class='fa fa-edit'></span></a></td>";
                        echo "<td>".$row['voters_id']."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['yearLvl']."</td>";
                        echo "<td>".$row['organization']."</td>";
                      } elseif(isset($_GET['type']) && $_GET['type'] === 'admin') {
                        // Admin table columns
                        $adminImage = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['organization']."</td>";
                        echo "<td>".$row['lastname']."</td>";
                        echo "<td>".$row['firstname']."</td>";
                        echo "<td><img src='".$adminImage."' width='30px' height='30px'></td>";
                        echo "<td>".$row['username']."</td>";
                        echo "<td>".$row['email']."</td>";
                      } elseif(isset($_GET['type']) && $_GET['type'] === 'election') {
                        // Election table columns
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['title']."</td>";
                        echo "<td>".$row['voters']."</td>";
                      }
                      echo "<td>
                        <button class='btn btn-success btn-sm restore' data-id='".$row['id']."'><i class='fa fa-reply'></i> Restore</button>
                        <button class='btn btn-danger btn-sm delete' data-id='".$row['id']."'><i class='fa fa-trash'></i> Delete</button>
                      </td>";
                      echo "</tr>";
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

  <!-- Modal for Batch Actions -->
  <div class="modal fade" id="batchActionModal" tabindex="-1" role="dialog" aria-labelledby="batchActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="batchActionModalLabel">Confirm Batch Action</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to perform this action on the selected records?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmBatchAction">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/voters_modal.php'; ?>
  <?php include 'includes/restore_modal.php'; ?>
  <?php include 'includes/restore_admin_modal.php'; ?>

<?php include 'includes/scripts.php'; ?>