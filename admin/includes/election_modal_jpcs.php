<!-- Add Election Modal -->
<div class="modal fade" id="addElection" tabindex="-1" role="dialog" aria-labelledby="addElectionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addElectionLabel">Add New Election</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addElectionForm" method="POST" action="election_add_jpcs.php">
        <div class="modal-body">
          <div class="form-group">
            <label for="electionTitle">Title</label>
            <input type="text" class="form-control" id="electionTitle" name="title" placeholder="Enter Election Title" required>
          </div>
          <div class="form-group">
            <label for="academicYear">Academic Year</label>
            <select class="form-control" id="academicYear" name="academic_yr" required>
              <option value="" disabled selected>Select Academic Year</option>
              <?php
              // Generate year range starting from current year
              $currentYear = date("Y");
              for ($i = 0; $i < 20; $i++) {
                  $startYear = $currentYear + $i;
                  $endYear = $startYear + 1;
                  echo "<option value='{$startYear} - {$endYear}'>{$startYear} - {$endYear}</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Election Modal -->
<div class="modal fade" id="editElection" tabindex="-1" role="dialog" aria-labelledby="editElectionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editElectionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editElectionLabel">Edit Election</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_title">Election Title</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                        <input type="hidden" id="edit_id" name="id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editSubmit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
