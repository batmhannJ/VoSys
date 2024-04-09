<?php
	session_start();
	session_destroy();

	header('location: voters_login.php');
?>