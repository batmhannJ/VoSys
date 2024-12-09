<!-- Preview Modal -->
<div class="modal fade" id="preview_modal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-preview-modal">
            <div class="modal-header custom-preview-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title custom-preview-title">Vote Preview</h5>
            </div>
            <div class="modal-body custom-preview-body">
                <div id="preview_body"></div>
            </div>
            <div class="modal-footer custom-preview-footer">
                <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<style>
  /* Custom Styles for Preview Modal */
.custom-preview-modal {
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.custom-preview-header {
    background-color: #1abc9c;
    color: #fff;
    padding: 15px 20px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.custom-preview-title {
    font-size: 1.3rem;
    font-weight: 600;
    text-transform: uppercase;
}

.custom-preview-body {
    padding: 20px;
    font-size: 1.1rem;
    color: #34495e;
}

.custom-preview-footer {
    background-color: #f4f4f4;
    padding: 15px;
    display: flex;
    justify-content: center;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

.custom-preview-footer .btn {
    background-color: #e74c3c;
    color: #fff;
    transition: background-color 0.3s ease;
}

.custom-preview-footer .btn:hover {
    background-color: #c0392b;
}

.custom-preview-footer .btn:focus {
    outline: none;
    box-shadow: none;
}

.close {
    color: #fff;
    font-size: 1.5rem;
    opacity: 0.7;
}

.close:hover {
    opacity: 1;
}

</style>

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
<!-- Platform -->
<div class="modal fade" id="platform">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b><span class="candidate"></b></h4>
            </div>
            <div class="modal-body">
              <p id="plat_view"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

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
