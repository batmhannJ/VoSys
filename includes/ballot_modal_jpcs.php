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
        <div class="modal-content rounded-4" style="background-color: #f5f5f5;">
            <div class="modal-header" style="background-color: #1e1e2f; color: white; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title w-100 text-center" id="platformLabel"><b><span class="candidate"></span></b></h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="plat_view" style="font-size: 1.1rem; color: #333333;"></p>
            </div>
            <div class="modal-footer justify-content-center" style="background-color: #e2e2e2; border-radius: 0 0 8px 8px;">
            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .modal-content {
        background-color: #f5f5f5; /* Light gray background */
        border-radius: 8px; /* Smooth rounded corners */
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }

    .modal-header {
        background-color: #1e1e2f; /* Dark color for the header */
        color: white;
        font-family: 'Helvetica Neue', sans-serif;
        font-weight: 700;
        text-align: center;
        padding: 20px;
        border-radius: 8px 8px 0 0;
    }

    .modal-title {
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 20px 30px;
        font-family: 'Arial', sans-serif;
        color: #333;
        line-height: 1.6;
        font-size: 1rem;
        text-align: left;
    }

    .modal-footer {
        padding: 15px 0;
        background-color: #e2e2e2;
        border-radius: 0 0 8px 8px;
    }

    /* Custom close button design */
    .btn {
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1.6rem;
        padding: 5px 10px;
        cursor: pointer;
    }

    /* Close Button Styling */
    .btn-danger {
        background-color: #ff6f61;  /* Warm coral color */
        border: none;
        color: white;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: bold;
        padding: 12px 25px;
        transition: background-color 0.3s ease-in-out;
    }

    .btn-danger:hover {
        background-color: #e25849;  /* Darker coral on hover */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); /* Shadow effect on hover */
    }

    /* Modal transition */
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
