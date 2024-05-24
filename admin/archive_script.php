<script>
$(function(){
  $(document).on('click', '.restore', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#submitBtn').attr('data-id', id); // Set the data-id attribute for the "Restore" button
  });

  $(document).on('click', '.restore-admin', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#adminSubmitBtn').attr('data-id', id); // Set the data-id attribute for the "Restore Admin" button
  });

  $(document).on('click', '.restore-all', function(e){
    e.preventDefault();
    $('#restoreAllModal').modal('show'); // Show the "Restore All" modal
  });

  $(document).on('click', '#confirmRestoreAll', function(e){
    e.preventDefault();
    restoreAllVoters();
  });

  $(document).on('click', '#submitBtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    restoreVoter(id);
  });

  $(document).on('click', '#adminSubmitBtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    restoreAdmin(id);
  });

  $(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var user = $(this).data('user');
    deleteItem(id, user);
  });

  $(document).on('click', '.restore-election', function() {
    var electionId = $(this).data('id');
    $('#electionConfirmationModal').modal('show');

    // Event handler for confirm restore button
    $('#confirmRestoreElection').on('click', function() {
        $.ajax({
            type: "POST",
            url: "restore_election.php",
            data: { id: electionId },
            success: function(response) {
                // Refresh the page or update the table as needed
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
  });

  function deleteItem(id, user) {
    $('#deleteConfirmationModal').modal('show'); // Show confirmation modal

    // When the "Delete" button inside the modal is clicked, perform deletion
    $('#confirmDelete').on('click', function() {
        $.ajax({
            type: "POST",
            url: "delete_item.php",
            data: { id: id, user: user },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
  }

  function restoreVoter(id) {
    $.ajax({
      type: "POST",
      url: "restore_voter.php",
      data: { id: id },
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  function restoreAdmin(id) {
    $.ajax({
      type: "POST",
      url: "restore_admin.php",
      data: { id: id },
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  function restoreAllVoters() {
    $.ajax({
      type: "POST",
      url: "restore_all_voters.php",
      success: function(response) {
        // Refresh the page or update the table as needed
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  $(document).on('click', '.restore-candidate', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#candidateConfirmationModal').modal('show');

    $('#confirmRestoreCandidate').on('click', function() {
        $.ajax({
            type: "POST",
            url: "restore_candidate.php",
            data: { id: id },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
  });

  // Batch action modals
  let actionType = '';

  // Select all checkboxes
  document.getElementById('select-all').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.record-checkbox');
    for (var checkbox of checkboxes) {
      checkbox.checked = this.checked;
    }
  });

  // Show modal for batch restore
  document.getElementById('batch-restore').addEventListener('click', function() {
    actionType = 'restore';
    $('#batchActionModal').modal('show');
  });

  // Show modal for batch delete
  document.getElementById('batch-delete').addEventListener('click', function() {
    actionType = 'delete';
    $('#batchActionModal').modal('show');
  });

  // Confirm batch action
  document.getElementById('confirmBatchAction').addEventListener('click', function() {
    var ids = [];
    var checkboxes = document.querySelectorAll('.record-checkbox:checked');
    checkboxes.forEach(function(checkbox) {
      ids.push(checkbox.value);
    });
    if (ids.length > 0) {
      // Send AJAX request for batch action
      fetch('batch_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: actionType, ids: ids })
      }).then(response => response.json()).then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Error: ' + data.message);
        }
      });
      $('#batchActionModal').modal('hide');
    } else {
      alert('Please select records to proceed.');
    }
  });
});
</script>
