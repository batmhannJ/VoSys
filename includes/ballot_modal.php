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
<!-- Platform -->
<div class="modal fade" id="platform">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  </button>
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
                $sql = "SELECT *, candidates.firstname AS canfirst, candidates.lastname AS canlast FROM votes_csc LEFT JOIN candidates ON candidates.id=votes_csc.candidate_id LEFT JOIN categories ON categories.id=votes_csc.category_id WHERE voters_id = '$id' ORDER BY categories.priority ASC";
                $query = $conn->query($sql);
                while($row = $query->fetch_assoc()){
                  echo "
                    <div class='row votelist'>
                      <span class='col-sm-4'><span class='pull-right'><b>".$row['name']." :</b></span></span> 
                      <span class='col-sm-8'>".$row['canfirst']." ".$row['canlast']."</span>
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
<script>
$(document).ready(function() {
    // Handle platform button click
    $(document).on('click', '.platform', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent triggering candidate selection
        
        // Get platform data and candidate name
        var platform = $(this).data('platform');
        var fullname = $(this).data('fullname');
        
        // Update modal with candidate info
        $('.candidate').text(fullname + "'s Platform");
        $('#plat_view').html(platform);
        
        // Show the modal
        $('#platform').modal('show');
    });
});
</script>
<style>
/* Additional styling for platform modal */
#platform .modal-body {
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
}

#plat_view {
    white-space: pre-wrap;
    word-wrap: break-word;
    font-family: Arial, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.platform {
    margin-top: 10px;
    padding: 8px 15px;
    font-size: 14px;
    font-weight: bold;
    background-color: #000000;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.platform:hover {
    background-color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Fix for modal on mobile devices */
@media (max-width: 768px) {
    #platform .modal-dialog {
        width: 95%;
        margin: 20px auto;
    }
    
    #plat_view {
        font-size: 14px;
        padding: 10px;
    }
}
</style>