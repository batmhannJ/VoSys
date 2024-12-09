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
                <button type="button" class="btn btn-dark btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<style>
 /* Alternative Modern Design for Preview Modal */
.custom-preview-modal {
    background: linear-gradient(135deg, #ff7e5f, #feb47b);  /* Gradient background for the modal */
    border-radius: 20px;
    border: 2px solid #fff;
    overflow: hidden;
}

.custom-preview-header {
    background-color: transparent;
    color: #fff;
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-preview-title {
    font-size: 1.5rem;
    font-weight: bold;
    letter-spacing: 1px;
}

.custom-preview-body {
    padding: 30px;
    font-size: 1.2rem;
    color: #fff;
    line-height: 1.6;
    text-align: center;
}

.custom-preview-footer {
    background-color: transparent;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.custom-preview-footer .btn {
    background-color: #333;
    color: #fff;
    padding: 12px 20px;
    font-size: 1rem;
    border-radius: 30px;
    text-transform: uppercase;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.custom-preview-footer .btn:hover {
    background-color: #555;
    transform: scale(1.05);
}

.custom-preview-footer .btn:focus {
    outline: none;
    box-shadow: none;
}

.close {
    color: #fff;
    font-size: 1.5rem;
    font-weight: bold;
    background: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.close:hover {
    opacity: 0.7;
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
