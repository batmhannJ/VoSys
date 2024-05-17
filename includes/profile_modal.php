<!-- Add -->
<div class="modal fade" id="profile">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>User Profile</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="profile_update.php?return=<?php echo basename($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname" class="col-sm-3 control-label">Firstname</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="firstname" name="firstname" oninput="validateFirstName(this)" value="<?php echo $voter['firstname']; ?>">
                    </div>
                </div>
                <script>
                    function validateFirstName(input) {
                        // Regular expression to match only letters
                        var letters = /^[A-Za-z]+$/;
                        // Check if the input value matches the regular expression
                        if (!input.value.match(letters)) {
                            // If it doesn't match, clear the input value
                            input.value = input.value.replace(/[^A-Za-z]/g, '');
                        }
                    }
                </script>
                <div class="form-group">
                    <label for="lastname" class="col-sm-3 control-label">Lastname</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="lastname" name="lastname" oninput="validateLastName(this)" value="<?php echo $voter['lastname']; ?>">
                    </div>
                </div>
                <script>
                    function validateLastName(input) {
                    // Regular expression to match only letters
                        var letters = /^[A-Za-z]+$/;
                        // Check if the input value matches the regular expression
                        if (!input.value.match(letters)) {
                            // If it doesn't match, clear the input value
                            input.value = input.value.replace(/[^A-Za-z]/g, '');
                        }
                    }
                </script>
                <div class="form-group">
                    <label for="curr_password" class="col-sm-3 control-label">Current Password:</label>

                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="curr_password" name="curr_password" placeholder="Enter Current Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo:</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="password" class="col-sm-3 control-label">New Password</label>

                    <div class="col-sm-9"> 
                      <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password">
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            	<button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-check-square-o"></i> Save</button>
            	</form>
          	</div>
        </div>
    </div>
</div>