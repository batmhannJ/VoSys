<?php
include 'includes/session.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'home.php';
}

if (isset($_POST['save'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $photo = $_FILES['photo']['name'];
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    // Check if the entered OTP matches the stored OTP
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $otp) {
        // OTP matched, proceed with saving the updated information
        // Your saving logic goes here
        if(password_verify($curr_password, $user['password'])){
			if(!empty($photo)){
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);
				$filename = $photo;	
			}
			else{
				$filename = $user['photo'];
			}

			if($password == $user['password']){
				$password = $user['password'];
			}
			else{
				$password = password_hash($password, PASSWORD_DEFAULT);
			}

			$sql = "UPDATE admin SET email = '$email', username = '$username', password = '$password', firstname = '$firstname', lastname = '$lastname', photo = '$filename' WHERE id = '".$user['id']."'";
			if($conn->query($sql)){
				$_SESSION['success'] = 'Admin profile updated successfully';
			}
			else{
				$_SESSION['error'] = $conn->error;
			}
			
		}
		else{
			$_SESSION['error'] = 'Incorrect password';
		}
        // Clear the OTP from session
        unset($_SESSION['otp']);
        
        $_SESSION['success'] = 'Profile updated successfully';
    } else {
        // OTP verification failed
        $_SESSION['error'] = 'OTP verification failed';
        header('Location: '.$return);
        exit;
    }
} else {
    $_SESSION['error'] = 'Fill up required details first';
}

header('Location: '.$return);
?>
