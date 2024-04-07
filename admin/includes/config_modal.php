<!-- Config -->
<div class="modal fade" id="config">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Configure</b></h4>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <?php
                  $parse = parse_ini_file('config.ini', FALSE, INI_SCANNER_RAW);
                  $title = $parse['election_title'];
                ?>
                <form class="form-horizontal" method="POST" action="config_save.php?return=<?php echo basename($_SERVER['PHP_SELF']); ?>">
                  <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">Title</label>

                    <!--<div class="col-sm-9">
                      <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
                    </div>-->
                    <div class="col-sm-9">
                    <select id="title" name="title" class="form-control" value="Choose below">
                      <option value="" selected hidden>Choose...</option>
                      <option>CSC - College Student Council Elections</option>
                      <option>JPCS - Junior Phillipine Computer Society Elections</option>
                      <option>YMF - Young Mentors of the Future Elections</option>
                      <option>CODE-TG - Coalition of Discipline Enforcer of Today's Generation Elections</option>
                      <option>PASOA - Philippine Association of Students in Office Administration Elections</option>
                      <option>HMSO - Hospitality Management Students' Organization Elections</option>
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="voters" class="col-sm-3 control-label">Voters</label>
                    <div class="col-sm-9">
                    <select id="voters" name="voters" class="form-control" value="Choose...">
                      <option value="" selected hidden>Choose...</option>
                      <option>BSIT Students</option>
                      <option>BSHM Students</option>
                      <option>BSEd Students</option>
                      <option>BSCrim Students</option>
                      <option>BSOAd Students</option>
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">Start Date</label>

                    <div class="col-sm-9">
                      <input type="datetime-local" name="starttime" id="starttime" value="Start at" class="form-control" placeholder="Start at" data-error-message="Start date is required">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">End Date</label>

                    <div class="col-sm-9">
                      <input type="datetime-local" name="endtime" id="endtime" value="End at" class="form-control" placeholder="End at" data-error-message="End date is required">
                    </div>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>