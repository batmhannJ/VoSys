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

<style>
  /* Modal Container */
.modal.fade {
    display: block;
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
}

/* Modal Dialog */
.modal-dialog {
    max-width: 600px;
    margin: 1.75rem auto;
    border-radius: 10px;
}

/* Modal Content */
.modal-content {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Modal Header */
.modal-header {
    background-color: #f7f7f7;
    border-bottom: 1px solid #ddd;
    padding: 15px 20px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.modal-header h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

/* Close Button */
.modal-header .close {
    font-size: 1.5rem;
    color: #333;
    opacity: 0.5;
}

.modal-header .close:hover {
    opacity: 1;
}

/* Modal Body */
.modal-body {
    padding: 20px;
    font-size: 1rem;
    line-height: 1.6;
    color: #555;
}

/* Modal Footer */
.modal-footer {
    background-color: #f7f7f7;
    border-top: 1px solid #ddd;
    padding: 10px 20px;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    text-align: right;
}

/* Button */
.modal-footer .btn {
    font-size: 1rem;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 5px;
}

.modal-footer .btn-default {
    background-color: #007bff;
    color: white;
    border: none;
}

.modal-footer .btn-default:hover {
    background-color: #0056b3;
    cursor: pointer;
}

/* Hover effect for button */
.modal-footer .btn-default:focus,
.modal-footer .btn-default:active {
    box-shadow: none;
}

/* Responsive Styles */
@media (max-width: 767px) {
    .modal-dialog {
        max-width: 90%;
        margin: 1rem auto;
    }

    .modal-header h4 {
        font-size: 1.1rem;
    }

    .modal-body {
        padding: 15px;
        font-size: 0.9rem;
    }

    .modal-footer .btn {
        font-size: 0.9rem;
        padding: 6px 15px;
    }
}

@media (max-width: 576px) {
    .modal-header h4 {
        font-size: 1rem;
    }

    .modal-body {
        font-size: 0.875rem;
    }

    .modal-footer .btn {
        font-size: 0.875rem;
        padding: 5px 12px;
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
