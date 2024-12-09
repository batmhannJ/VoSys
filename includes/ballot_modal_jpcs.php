<!-- Preview Modal -->
<div class="modal fade" id="preview_modal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-formal-preview-modal">
            <div class="modal-header custom-formal-preview-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title custom-formal-preview-title">Vote Preview</h5>
            </div>
            <div class="modal-body custom-formal-preview-body">
                <div id="preview_body"></div>
            </div>
            <div class="modal-footer custom-formal-preview-footer">
                <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>


<style>
/* Formal and Responsive Design for Preview Modal */
.custom-formal-preview-modal {
    background-color: #f9f9f9;  /* Light gray background for a professional feel */
    border-radius: 8px;  /* Slight rounding for a modern, but formal look */
    border: 1px solid #ddd;  /* Light border for definition */
    overflow: hidden;  /* Prevents overflow of content */
}

.custom-formal-preview-header {
    background-color: #4e5b6e;  /* Subtle dark blue-gray background */
    color: #fff;
    padding: 20px 25px;
    border-bottom: 1px solid #ccc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-formal-preview-title {
    font-size: 1.3rem;
    font-weight: 600;
    letter-spacing: 1px;
}

.custom-formal-preview-body {
    padding: 25px;
    font-size: 1rem;
    color: #333;
    line-height: 1.6;
    text-align: left;
    max-height: 400px; /* Ensures the body is scrollable if the content is too long */
    overflow-y: auto; /* Adds scrolling if content exceeds max height */
}

.custom-formal-preview-footer {
    background-color: #f1f1f1;
    padding: 15px 25px;
    display: flex;
    justify-content: center;
    border-top: 1px solid #ddd;
}

.custom-formal-preview-footer .btn {
    background-color: #4e5b6e;  /* Matching dark blue-gray */
    color: #fff;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    text-transform: uppercase;
    box-shadow: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.custom-formal-preview-footer .btn:hover {
    background-color: #37414c;  /* Slightly darker shade on hover */
    transform: scale(1.05);
}

.custom-formal-preview-footer .btn:focus {
    outline: none;
    box-shadow: none;
}

.close {
    color: #fff;
    font-size: 1.5rem;
    font-weight: 700;
    background: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.3s ease;
}

.close:hover {
    opacity: 0.7;
}

/* Responsive Design for Modal */
@media (max-width: 768px) {
    .custom-formal-preview-title {
        font-size: 1.1rem;  /* Slightly smaller title on mobile */
    }

    .modal-body {
        padding: 15px;  /* Reduces padding for smaller screens */
    }

    .custom-formal-preview-footer .btn {
        padding: 8px 16px;  /* Smaller button size for mobile */
        font-size: 0.9rem;  /* Smaller font size for the button */
    }

    .modal-dialog {
        max-width: 100%;  /* Ensures modal takes up full width on smaller screens */
        margin: 10px;  /* Adds margin to prevent modal from touching the edges */
    }
}

@media (max-width: 480px) {
    .custom-formal-preview-header {
        padding: 15px 20px;  /* Smaller padding for mobile */
    }

    .modal-body {
        padding: 10px;  /* Further reduces padding on very small screens */
    }

    .custom-formal-preview-footer {
        padding: 10px 15px;  /* Further reduces footer padding on mobile */
    }
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
