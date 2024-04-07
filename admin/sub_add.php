<?php
	include 'includes/session.php';

	if(isset($_POST['save'])){
		$username = $_POST['username'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$cpassword = password_hash($_POST['createpassword'], PASSWORD_DEFAULT);
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		//generate voters id

		$admin_pass = $_POST['password'];

		$sql_admin = "SELECT password FROM admin WHERE username = 'OSAadmin'";
    	$result_admin = $conn->query($sql_admin);

    	if ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        $admin_password_hash = $row['password'];


        if (password_verify($admin_pass, $admin_password_hash)) {
            // If admin password is correct proceed to insert sub-admin data
            $sql = "INSERT INTO sub_admin (username, password, firstname, lastname, email, photo) VALUES ('$username', '$cpassword', '$firstname', '$lastname', '$email', '$filename')";
            if ($conn->query($sql)) {
                $_SESSION['success'] = 'Sub-Admin added successfully';
            } else {
                $_SESSION['error'] = $conn->error;
            }
        } else {
            $_SESSION['error'] = 'Admin password is incorrect';
        }
    } else {
        $_SESSION['error'] = 'Admin username not found';
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
}
	header('location: sub_admin.php');
?>