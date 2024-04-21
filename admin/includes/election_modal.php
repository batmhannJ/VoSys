<!-- Add -->
<div class="modal fade" id="addElection">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Add New Election</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="election_add.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="col-sm-3 control-label" >Title</label>

                    <div class="col-sm-9">
                      <select id="title" name="title" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Title of Election</option>
                      <option>CSC - College of Student Council Election</option>
                      <option>JPCS - Junior Philippine Computer Society Election</option>
                      <option>YMF - Young Mentor of the Future Election</option>
                      <option>CODE-TG - Coalition of Disciplined Future Enforcers of Today's Generation Election</option>
                      <option>PASOA - Philippine Association of Students in Office Administration Election</option>
                      <option>HMSO - Hospitality Management Students' Organization Election</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="voters" class="col-sm-3 control-label">Voters</label>

                    <div class="col-sm-9">
                      <select id="voters" name="voters" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Choose...</option>>
                      <option>All Students</option>
                      <option>JPCS Students</option>
                      <option>YMF Students</option>
                      <option>CODE-TG Students</option>
                      <option>PASOA Students</option>
                      <option>HMSO Students</option>
                    </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="starttime" class="col-sm-3 control-label">Start Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="starttime" name="starttime" required>
                        </div>
                    </div>

<!-- Add end time input -->
                    <div class="form-group">
                        <label for="endtime" class="col-sm-3 control-label">End Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="endtime" name="endtime" required>
                        </div>
                    </div>



                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label">Status</label>

                    <div class="col-sm-9">
                      <select id="status" name="status" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Choose...</option>>
                      <option value="Active">Active</option>
                      <option value="Not Active">Not Active</option>
                    </select>
                    </div>
                </div>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="save"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Edit Election</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="election_edit.php">
                <input type="hidden" class="id" name="id">
                <div class="form-group">
                    <label for="edit_title" class="col-sm-3 control-label">Title</label>

                    <div class="col-sm-9">
                      <select id="edit_title" name="title" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Title of Election</option>
                      <option>CSC - College of Student Council Election</option>
                      <option>JPCS - Junior Philippine Computer Society Election</option>
                      <option>YMF - Young Mentor of the Future Election</option>
                      <option>CODE-TG - Coalition of Disciplined Future Enforcers of Today's Generation Election</option>
                      <option>PASOA - Philippine Association of Students in Office Administration Election</option>
                      <option>HMSO - Hospitality Management Students' Organization Election</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit_voters" class="col-sm-3 control-label">Voters</label>

                    <div class="col-sm-9">
                      <select id="edit_voters" name="voters" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Choose...</option>>
                      <option>All Students</option>
                      <option>JPCS Students</option>
                      <option>YMF Students</option>
                      <option>CODE-TG Students</option>
                      <option>PASOA Students</option>
                      <option>HMSO Students</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                        <label for="edit_starttime" class="col-sm-3 control-label">Start Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="edit_starttime" name="starttime" required>
                        </div>
                    </div>
                <!-- Add end time input -->
                    <div class="form-group">
                        <label for="edit_endtime" class="col-sm-3 control-label">End Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="edit_endtime" name="endtime" required>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="edit_status" class="col-sm-3 control-label">Status</label>

                    <div class="col-sm-9">
                      <select id="edit_status" name="status" class="form-control" value="Choose below" required>
                      <option value="" selected hidden>Choose...</option>>
                      <option value="Active">Active</option>
                      <option value="Not Active">Not Active</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Deleting...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="election_delete.php">
                <input type="hidden" class="id" name="id">
                <div class="text-center">
                    <p>DELETE ELECTION</p>
                    <h2 class="bold fullname"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="fullname"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="voters_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="id" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div>


     