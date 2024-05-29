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
        Votes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Votes</li>
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
              <a href="#reset" data-toggle="modal" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-refresh"></i> Reset</a>
            </div>
            <div class="box-body">
            <div class="table-responsive" style="overflow-x:auto;">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>No. </th>
                  <th>ID</th>
                  <th>Position</th>
                  <th>Candidate</th>
                  <th>Voter</th>
                  <th>Organization</th>
                </thead>
                <tbody>
                <?php
                      $sql = "SELECT *,
                      candidates.firstname AS canfirst, 
                      candidates.lastname AS canlast, 
                      voters1.firstname AS votfirst, 
                      voters1.lastname AS votlast, 
                      voters1.organization AS org 
                      FROM votes_csc 
                      LEFT JOIN categories ON categories.id=votes_csc.category_id 
                      LEFT JOIN candidates ON candidates.id=votes_csc.candidate_id 
                      LEFT JOIN voters AS voters1 ON voters1.id=votes_csc.voters_id 
                      LEFT JOIN voters AS voters2 ON voters2.organization=votes_csc.organization 
                      GROUP BY votes_csc.id
                      ORDER BY categories.priority ASC";
                      $query = $conn->query($sql);
                      $i = 1;
                      while($row = $query->fetch_assoc()){
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".$i++."</td>
                            <td>".$row['name']."</td>
                            <td>".$row['canfirst'].' '.$row['canlast']."</td>
                            <td>".$row['votfirst'].' '.$row['votlast']."</td>
                            <td>".$row['org']."</td>
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
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/votes_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
