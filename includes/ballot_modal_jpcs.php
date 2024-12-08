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
        <div class="modal-content rounded-5" style="background-color: #ffffff;">
            <div class="modal-header" style="background-color: #FF7043; color: white; border-radius: 5px 5px 0 0;">
                <h5 class="modal-title w-100 text-center" id="platformLabel"><b><span class="candidate"></span></b></h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p id="plat_view" class="text-dark" style="font-size: 1.1rem;"></p>
            </div>
            <div class="modal-footer" style="border: none; justify-content: center;">
            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    /* Overall Modal Content */
    .modal-content {
        background-color: #ffffff;
        border-radius: 8px; /* Rounded corners for a soft appearance */
        border: none;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    }

    .modal-header {
        background-color: #FF7043;  /* Bold coral color */
        color: white;
        font-family: 'Helvetica Neue', sans-serif;
        font-weight: 600;
        padding: 20px 30px;
        text-align: center;
        border-radius: 8px 8px 0 0;
    }

    .modal-title {
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 30px;
        font-family: 'Arial', sans-serif;
        color: #333;  /* Dark text */
        line-height: 1.6;
        font-size: 1rem;
        text-align: left;
    }

    .modal-footer {
        background-color: #f7f7f7;
        border-radius: 0 0 8px 8px;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1.5rem;
    }

    .btn-danger {
        background-color: #FF7043; /* Same as header color */
        border: none;
        color: white;
        border-radius: 25px;
        font-size: 1rem;
        padding: 10px 30px;
    }

    .btn-danger:hover {
        background-color: #FF5722;  /* Slightly darker on hover */
        color: white;
    }

    /* Modal Animation */
    .modal.fade .modal-dialog {
        transform: translate(0, -30px);
        transition: transform 0.3s ease-in-out;
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
