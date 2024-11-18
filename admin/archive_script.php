<script>
$(function(){
  // Individual restore and delete event handlers...
  $(document).on('click', '.restore', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#submitBtn').attr('data-id', id);
  });

  $(document).on('click', '.restore-admin', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#adminSubmitBtn').attr('data-id', id);
  });

  $(document).on('click', '.restore-all', function(e){
    e.preventDefault();
    $('#restoreAllModal').modal('show');
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

    $('#confirmRestoreElection').on('click', function() {
      $.ajax({
        type: "POST",
        url: "restore_election.php",
        data: { id: electionId },
        success: function(response) {
          location.reload();
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });

  function deleteItem(id, user) {
    $('#deleteConfirmationModal').modal('show');

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

  // Batch Restore and Delete Handlers
  $('#batchRestoreBtn').click(function() {
    var selectedIds = [];
    var type = getArchiveType(); // Get the selected archive type (voters, election, or candidates)
    $('.selectItem:checked').each(function() {
      selectedIds.push($(this).val());
    });

    if (selectedIds.length > 0) {
      $('#batchRestoreModal').modal('show');
    } else {
      alert('Please select items to restore.');
    }
  });

  $('#confirmBatchRestore').click(function() {
    var selectedIds = [];
    var type = getArchiveType(); // Get the selected archive type (voters, election, or candidates)
    $('.selectItem:checked').each(function() {
      selectedIds.push($(this).val());
    });

    batchRestore(selectedIds, type);
  });

  $('#batchDeleteBtn').click(function() {
    var selectedIds = [];
    var type = getArchiveType(); // Get the selected archive type (voters, election, or candidates)
    $('.selectItem:checked').each(function() {
      selectedIds.push($(this).val());
    });

    if (selectedIds.length > 0) {
      $('#batchDeleteModal').modal('show');
    } else {
      alert('Please select items to delete.');
    }
  });

  $('#confirmBatchDelete').click(function() {
    var selectedIds = [];
    var type = getArchiveType(); // Get the selected archive type (voters, election, or candidates)
    $('.selectItem:checked').each(function() {
      selectedIds.push($(this).val());
    });

    batchDelete(selectedIds, type);
  });

  function getArchiveType() {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('type') || 'voters'; // Default to 'voters' if no type is specified
  }

  function batchRestore(ids, type) {
    $.ajax({
      type: "POST",
      url: "batch_restore.php?type=" + type, // Dynamically use the selected archive type
      data: { ids: ids },
      success: function(response) {
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  function batchDelete(ids, type) {
    $.ajax({
      type: "POST",
      url: "batch_delete.php?type=" + type, // Dynamically use the selected archive type
      data: { ids: ids },
      success: function(response) {
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
});
</script>