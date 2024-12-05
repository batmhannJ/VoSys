<!-- Batch Restore Confirmation Modal -->
<div class="modal fade" id="batchRestoreModal" tabindex="-1" role="dialog" aria-labelledby="batchRestoreModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="batchRestoreModalLabel">Batch Restore Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to restore the selected voters?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmBatchRestore">Restore</button>
      </div>
    </div>
  </div>
</div>

<!-- Batch Delete Confirmation Modal -->
<div class="modal fade" id="batchDeleteModal" tabindex="-1" role="dialog" aria-labelledby="batchDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="batchDeleteModalLabel">Batch Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the selected voters? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmBatchDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Batch Archive Confirmation Modal -->
<div class="modal fade" id="batchArchiveModal" tabindex="-1" role="dialog" aria-labelledby="batchArchiveModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="batchArchiveModalLabel">Batch Archive Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to archive the selected voters?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning" id="confirmBatchArchive">Archive</button>
      </div>
    </div>
  </div>
</div>
