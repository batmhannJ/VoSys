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
          <?php
            if (isset($_GET['type']) && $_GET['type'] === 'voters') {
              echo "Voter Archived";
            } elseif (isset($_GET['type']) && $_GET['type'] === 'election') {
              echo "Election Archived";
            } elseif (isset($_GET['type']) && $_GET['type'] === 'candidates') {
              echo "Candidate Archived";
            } else {
              echo "Archived";
            }
          ?>
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">
            <?php
              if (isset($_GET['type']) && $_GET['type'] === 'voters') {
                echo "Voter Archived";
              } elseif (isset($_GET['type']) && $_GET['type'] === 'election') {
                echo "Election Archived";
              } elseif (isset($_GET['type']) && $_GET['type'] === 'candidates') {
                echo "Candidate Archived";
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
                      <li><a href="?type=election" class="archive-type">Election Archived</a></li>
                      <li><a href="?type=candidates" class="archive-type">Candidates Archived</a></li>
                  </ul>
              </div>
            </div>
            <div class="box">
              <!--<div class="box-header with-border">
                <a href="#restoreAllModal" data-toggle="modal" class="btn btn-primary btn-sm btn-flat restore-all"><i class="fa fa-reply"></i> Restore All</a>
              </div> -->
              <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                    <?php if(isset($_GET['type']) && $_GET['type'] === 'voters'): ?>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Photo</th>
                    <th>Voters ID</th>
                    <th>Email</th>
                    <th>Year Level</th>
                    <th>Organization</th>
                    <?php elseif(isset($_GET['type']) && $_GET['type'] === 'election'): ?>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Voters</th>
                    <?php elseif(isset($_GET['type']) && $_GET['type'] === 'candidates'): ?>
                    <th class="hidden"></th>
                    <th>Position</th>
                    <th>Photo</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Platform</th>
                    <?php endif; ?>
                    <th>Tools</th>
                </thead>
                <tbody>
                    <?php
                        if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                            $sql = "SELECT * FROM voters WHERE archived = TRUE";
                        } elseif(isset($_GET['type']) && $_GET['type'] === 'election') {
                            $sql = "SELECT * FROM election WHERE archived = TRUE";
                        } elseif(isset($_GET['type']) && $_GET['type'] === 'candidates') {
                            $sql = "SELECT candidates.*, categories.name AS position 
                                    FROM candidates 
                                    LEFT JOIN categories ON categories.id = candidates.category_id 
                                    WHERE candidates.archived = TRUE";
                        }

                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                            if(isset($_GET['type']) && $_GET['type'] === 'voters') {
                                // For voters table
                                $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                                echo "
                                    <tr>
                                        <td>".$row['lastname']."</td>
                                        <td>".$row['firstname']."</td>
                                        <td>
                                            <img src='".$image."' width='30px' height='30px'>
                                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'><span class='fa fa-edit'></span></a>
                                        </td>
                                        <td>".$row['voters_id']."</td>
                                        <td>".$row['email']."</td>
                                        <td>".$row['yearLvl']."</td>
                                        <td>".$row['organization']."</td>
                                        <td>
                                            <button class='btn btn-success btn-sm restore btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#confirmationModal'><i class='fa fa-reply'></i> Restore</button>
                                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."' data-user='voters'><i class='fa fa-trash'></i> Delete</button>
                                        </td>
                                    </tr>
                                ";
                            } elseif(isset($_GET['type']) && $_GET['type'] === 'election') {
                                // For election table
                                echo "
                                    <tr>
                                        <td>".$row['id']."</td>
                                        <td>".$row['title']."</td>
                                        <td>".$row['voters']."</td>
                                        <td>
                                            <button class='btn btn-success btn-sm restore-election btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#electionConfirmationModal'><i class='fa fa-reply'></i> Restore</button>
                                            <button class='btn btn-danger btn-sm delete-election btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#deleteConfirmationModal'><i class='fa fa-trash'></i> Delete</button>
                                        </td>
                                    </tr>
                                ";
                            } elseif(isset($_GET['type']) && $_GET['type'] === 'candidates') {
                                // For candidates table
                                $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                                echo "
                                    <tr>
                                        <td class='hidden'></td>
                                        <td>".$row['position']."</td>
                                        <td>
                                            <img src='".$image."' width='30px' height='30px'>
                                        </td>
                                        <td>".$row['firstname']."</td>
                                        <td>".$row['lastname']."</td>
                                        <td>".$row['platform']."</td>
                                        <td>
                                            <button class='btn btn-success btn-sm restore-candidate btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#candidateConfirmationModal'><i class='fa fa-reply'></i> Restore</button>
                                            <button class='btn btn-danger btn-sm delete-candidate btn-flat' data-id='".$row['id']."' data-toggle='modal' data-target='#deleteConfirmationModal'><i class='fa fa-trash'></i> Delete</button>
                                        </td>
                                    </tr>
                                ";
                            }
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
    <?php include 'includes/restore_modal.php'; ?>
    <?php include 'includes/delete_modal.php'; ?>

<!-- Confirmation Modal for Delete -->
<!-- <div id="deleteConfirmationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this item?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div> -->


<?php include 'includes/scripts.php'; ?>
<?php include 'archive_script.php'; ?>
</body>
</html>
