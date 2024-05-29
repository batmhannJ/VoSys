<!-- Edit -->
<div class="modal fade" id="editAnnouncement">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Edit Announcement</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="announce_edit.php">
                    <input type="hidden" class="id_announcement" name="id_announcement">
                    <div class="form-group">
                        <label for="edit_announcement" class="col-sm-3 control-label">Announcement</label>
                        <div class="col-sm-9">
                            <textarea id="edit_announcement" name="announcement" class="form-control" rows="6" placeholder="Enter Message Here"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="editAnnouncement"><i class="fa fa-check-square-o"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="deleteAnnouncement">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Deleting...</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="announce_delete.php">
                    <input type="hidden" class="id_announcement" name="id_announcement">
                    <div class="text-center">
                        <p>DELETE ANNOUNCEMENT</p>
                        <h2 class="bold fullname"></h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-danger btn-flat" name="deleteAnnouncement"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
