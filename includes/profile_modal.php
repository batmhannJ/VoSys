<!-- Add -->
<div class="modal fade" id="profile">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title"><b>User Profile</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="profile_update.php?return=<?php echo basename($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                    <!-- Firstname -->
                    <div class="form-group">
                        <label for="firstname" class="col-sm-3 control-label">Firstname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="firstname" name="firstname" oninput="validateFirstName(this)" value="<?php echo $voter['firstname']; ?>">
                        </div>
                    </div>
                    <script>
                        function validateFirstName(input) {
                            var lettersAndSpaces = /^[A-Za-z\s]+$/;
                            if (!input.value.match(lettersAndSpaces)) {
                                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
                            }
                        }
                    </script>

                    <!-- Lastname -->
                    <div class="form-group">
                        <label for="lastname" class="col-sm-3 control-label">Lastname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="lastname" name="lastname" oninput="validateLastName(this)" value="<?php echo $voter['lastname']; ?>">
                        </div>
                    </div>
                    <script>
                        function validateLastName(input) {
                            var lettersAndSpaces = /^[A-Za-z\s]+$/;
                            if (!input.value.match(lettersAndSpaces)) {
                                input.value = input.value.replace(/[^A-Za-z\s]/g, '');
                            }
                        }
                    </script>

                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="curr_password" class="col-sm-3 control-label">Current Password:</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="curr_password" name="curr_password" placeholder="Enter Current Password" required>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo:</label>
                        <div class="col-sm-9">
                            <input type="file" id="photo" name="photo" accept=".png, .jpg, .jpeg" onchange="validateFileType(this)">
                        </div>
                    </div>
                    <script>
                        function validateFileType(input) {
                            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                            const filePath = input.value;
                            if (!allowedExtensions.exec(filePath)) {
                                alert('Please upload a file with a .png, .jpg, or .jpeg extension.');
                                input.value = '';
                                return false;
                            }
                            return true;
                        }
                    </script>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                <button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-check-square-o"></i> Save</button>
                <!-- New Button for Registering Face -->
                <!--<button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#registerFaceModal">
                    <i class="fa fa-camera"></i> Register Face
                </button>-->
            </div>
        </div>
    </div>
</div>

<!-- New Modal for Face Registration -->
<div class="modal fade" id="registerFaceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title"><b>Register Face Reference</b></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="register_face.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="face_reference" class="control-label">Upload Face Reference:</label>
                        <input type="file" id="face_reference" name="face_reference" accept=".png, .jpg, .jpeg" class="form-control" onchange="validateFileType(this)">
                    </div>
                    <script>
                        function validateFileType(input) {
                            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                            const filePath = input.value;
                            if (!allowedExtensions.exec(filePath)) {
                                alert('Please upload a valid .png, .jpg, or .jpeg file.');
                                input.value = '';
                                return false;
                            }
                            return true;
                        }
                    </script>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>
