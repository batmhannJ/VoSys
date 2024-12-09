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
<div class="modal fade" id="platform" tabindex="-1" aria-labelledby="platformModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
            <div class="modal-header custom-header">
                <h5 class="modal-title custom-title"><b><span class="candidate">Candidate Platform</span></b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body custom-body">
                <p id="plat_view"></p>
            </div>
            <div class="modal-footer custom-footer">
                <button type="button" class="btn btn-primary btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<style>

/* Custom Modal Styles */
.custom-modal {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #f0f4f7, #e8ebef);
}

.custom-header {
    background-color: #2c3e50;
    color: #fff;
    border-bottom: 2px solid #2980b9;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.custom-title {
    font-size: 1.2rem;
    font-weight: bold;
    text-transform: uppercase;
}

.custom-body {
    font-size: 1rem;
    color: #34495e;
    padding: 20px;
}

.custom-footer {
    border-top: 1px solid #ddd;
    padding: 15px;
    display: flex;
    justify-content: center;
}

.custom-footer .btn {
    background-color: #2980b9;
    color: #fff;
    transition: background-color 0.3s ease;
}

.custom-footer .btn:hover {
    background-color: #3498db;
}

.custom-footer .btn:focus {
    outline: none;
    box-shadow: none;
}

.close {
    color: #ecf0f1;
    font-size: 1.5rem;
    opacity: 0.7;
}

.close:hover {
    opacity: 1;
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
