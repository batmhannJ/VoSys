<!-- Preview -->
<div class="modal fade" id="preview_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>
              <h4 class="modal-title">Vote Preview</h4>
            </div>
            <div class="modal-body">
              <div id="preview_body"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!--<script>
  $('#submit').click(function() {
        // Serialize the form data
        var formData = $('#ballotForm').serialize();

        // Submit the form using AJAX
        $.ajax({
            type: 'POST',
            url: 'submit_ballot_code.php',
            data: formData,
            success: function(response) {
                // Handle the response from the server
                console.log(response); // Log the response to the console for debugging
                // Optionally, display a success message or redirect to another page
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText); // Log the error message to the console
            }
        });
    });
</script>-->

</div>
<!-- Platform Modal -->
<div class="modal fade" id="platform" tabindex="-1" aria-labelledby="platformLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title w-100 text-center" id="platformLabel"><b><span class="candidate"></span></b></h5>
            </div>
            <div class="modal-body px-4 py-3">
                <p id="plat_view" class="text-muted"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .modal-content {
        background-color: #f1f3f5;  /* Light gray background */
        border-radius: 15px;         /* Rounded corners */
        border: none;
    }

    .modal-header {
        background-color: #343a40;  /* Dark background */
        color: white;
        padding: 20px;
    }

    .modal-title {
        font-family: 'Arial', sans-serif;
        font-weight: 600;
    }

    .modal-body {
        font-family: 'Verdana', sans-serif;
        font-size: 1.1rem;
        color: #495057;  /* Dark gray text */
        padding: 15px;
    }

    .modal-footer {
        padding: 15px;
        background-color: transparent;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1.5rem;
    }

    .btn-outline-dark {
        background-color: transparent;
        border: 2px solid #343a40;
        color: #343a40;
        border-radius: 30px;
        padding: 8px 20px;
        font-size: 1rem;
    }

    .btn-outline-dark:hover {
        background-color: #343a40;
        color: white;
    }

    /* Animation for modal */
    .modal.fade .modal-dialog {
        transform: translate(0, -50px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translate(0, 0);
    }
</style>


<!-- View Ballot -->
<div class="modal fade" id="view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>
              <h4 class="modal-title">Your Votes</h4>
            </div>
            <div class="modal-body">
              <?php
                $id = $voter['id'];
                $sql = "SELECT *, candidates.firstname AS canfirst, candidates.lastname AS canlast FROM votes LEFT JOIN candidates ON candidates.id=votes.candidate_id LEFT JOIN categories ON categories.id=votes.category_id WHERE voters_id = '$id' ORDER BY categories.priority ASC";
                $query = $conn->query($sql);
                while($row = $query->fetch_assoc()){
                  echo "
                    <div class='row votelist'>
                      <span class='col-sm-5'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
                      <span class='col-sm-7'>".$row['canfirst']." ".$row['canlast']."</span>
                    </div>
                  ";
                }
              ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>
