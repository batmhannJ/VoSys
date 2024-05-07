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
});
</script>