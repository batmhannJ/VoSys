<?php
include 'includes/conn.php';
include 'includes/session.php';
include 'includes/header.php';

$row = $conn->prepare("SELECT * FROM election ORDER BY id DESC");
$row->execute();
$result = $row->get_result();
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Categories Configuration
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Categories</li>
      </ol>
    </section>

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
<section class="content">
    <div>
        <select class="form-select" name="election" id="election">
          <option value="" hidden>Select Election</option>
          <?php
          foreach ($result as $key => $value) {
              echo '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
          }
          ?>
      </select>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addcat" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
        <!--<span class="small d-inline-block d-md-none" data-toggle="tooltip" data-placement="left" title="Scroll horizontally to view more content">
            <i class="bi bi-arrows-expand"></i> Scroll Horizontally
        </span>-->
        <div class="box-body">
            <table id="categoriesTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category</th>
                        <th class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="election" id="categoriesTableBody">
                    <?php
                    if (isset($_GET['election_id']) && is_numeric($_GET['election_id'])) {
                        $i = 1;
                        $election = $conn->prepare("SELECT * FROM categories WHERE election_id = ? ORDER BY created_by ASC");
                        $election_id = $_GET['election_id'];
                        $election->bind_param('i', $election_id);
                        $election->execute();
                        $result = $election->get_result();
                        var_dump($result);

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                  <th scope="row">' . $i++ . '</th>
                                  <td>' . $row['name'] . '</td>
                                  <td class="text-center">
                                      <a href="#" class="btn btn-success btn-sm edit-category" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                                          <i class="bi bi-pencil"></i> Edit
                                      </a>
                                      <a href="#" class="btn btn-danger btn-sm category-delete" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '">
                                          <i class="bi bi-trash"></i> Delete
                                      </a>
                                  </td>
                              </tr>';
                        }
                    } else {
                        echo '<tr>
                              <td colspan="3" class="text-center text-muted py-5 border-bottom-0">Select an Election to add and view categories.</td>
                          </tr>';
                    }
                    ?>
                </tbody>
            </table><!-- End Categories lists Table -->

            <!-- Add Category Modal 
            <div class="modal fade" id="addCategory" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                </div>
            </div>-->
            <!-- End Add Category Modal Dialog -->

            <!-- The Modal -->
            <!--<div class="modal fade" id="editCategory" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                        </div>
                    </div>
                </div>
            </div>
            End Edit Category Modal Dialog -->

        </div>
    </div>
    </div>
    </div>
</section>
</div>
<?php include 'includes/footer.php'; ?>
<?php include 'includes/cat_modal.php'; ?>
</div>


<!-- yourpage.php -->
<!-- ... (previous code) ... -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  function showLoadingOverlay() {
    var loadingOverlay = $("<div id='loadingOverlay' class='d-flex justify-content-center align-items-center' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #ffffff82; z-index: 9999;'>" +
      "<div class='spinner-border' role='status' style='width: 4rem; height: 4rem; color: #012970;'>" +
      "<span class='sr-only'>Loading...</span>" +
      "</div>" +
      "</div>");

    $(document.body).append(loadingOverlay);
  }

  function hideLoadingOverlay() {
    $('#loadingOverlay').remove();
  }

  // fetch and display categories based on the selected election_id
  function fetchCategories(electionId) {
    $.ajax({
      url: 'fecth_categories.php',
      type: 'GET',
      data: {
        election_id: electionId
      },
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      complete: function() {
        hideLoadingOverlay();
      },
      success: function(data) {
        console.log('Response from server:', data);

  // Clear the existing rows
  $('#categoriesTableBody').empty();

        $.each(data, function(index, category) {
          var row = '<tr>' +
            '<td>' + (index + 1) + '</td>' +
            '<td>' + category.name + '</td>' +
            '<td class="text-center">' +
            '<a href="#" class="btn btn-primary btn-sm edit-category" data-id="' + category.id + '" data-name="' + category.name + '">' +
            '<i class="bi bi-pencil"></i> Edit' +
            '</a>' +
            '<a href="#" class="btn btn-danger btn-sm ms-3 category-delete" data-id="' + category.id + '" data-name="' + category.name + '">' +
            '<i class="bi bi-trash"></i> Delete' +
            '</a>' +
            '</td>' +
            '</tr>';

          $('#categoriesTableBody').append(row);
        });

        var btn = '<div>' +
          '<a href="controllers/export_excel.php?action=export_categories&election_id=' + electionId + '" class="btn btn-primary col-4 offset-4">EXPORT EXCEL</a>' +
          '</div>';

        // $('.section').append(btn);

        // Show the table or hide it
        if (data.length > 0) {
          $('#categoriesTable').show();
        } else {
          $('#categoriesTable').hide();
        }
      },
      error: function(xhr, status, error) {
        console.log('Error: ' + error);
      }
    });
  }

  $('#election').on('change', function () {
        var selectedElectionId = $(this).val();
        if (selectedElectionId !== '') {
            fetchCategories(selectedElectionId);
        } else {
            $('#categoriesTable').hide();
        }
    });

  $('#election').on('change', function () {
        $('#categoriesTableBody').empty();
    });

</script>
</body>



<!-- ... (remaining code) ... -->


