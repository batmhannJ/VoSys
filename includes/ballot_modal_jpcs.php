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
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                <h4 class="modal-title w-100 text-center" id="platformLabel"><b><span class="candidate"></span></b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-5">
                <p id="plat_view" class="lead text-dark"></p>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .modal-content {
        background-color: #ffffff;  /* White background */
        border-radius: 15px;         /* Rounded corners */
        border: none;
    }

    .modal-header {
        background: linear-gradient(90deg, #4e73df, #1cc88a);  /* Gradient background */
        color: white;
    }

    .modal-title {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
    }

    .modal-body {
        font-family: 'Arial', sans-serif;
        font-size: 1.1rem;
        color: #333;  /* Dark text */
    }

    .modal-footer {
        background-color: #f1f1f1; /* Light gray footer */
        border-top: none;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1.5rem;
    }

    .btn-danger {
        background-color: #e74a3b;  /* Red button */
        color: white;
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 1rem;
    }

    .btn-danger:hover {
        background-color: #c0392b;  /* Darker red on hover */
    }

    /* Modal transition effect */
    .modal.fade .modal-dialog {
        transform: translate(0, -50px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translate(0, 0);
    }

    .modal-content {
        transition: box-shadow 0.3s ease-in-out;
    }

    .modal-content:hover {
        box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.1); /* Shadow on hover */
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
