<?php
	include 'includes/session.php';
	<?php

// Google API configuration
define('GOOGLE_CLIENT_ID', '521331514441-7th1b1gp40k3ueueosk160thurbugqfn.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URL', 'https://vosys.org/voters_login.php');

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId(GOOGLE_CLIENT_ID);

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret(GOOGLE_CLIENT_SECRET);

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri(GOOGLE_REDIRECT_URL);

$google_client->addScope('email');
$google_client->addScope('profile');

//start session on web page
session_start();
?>

	$return = 'home.php';
	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}

	if(isset($_POST['save'])){
		$title = $_POST['title'];

		$file = 'config.ini';
		$content = 'election_title = '.$title;

		file_put_contents($file, $content);

		$_SESSION['success'] = 'Election title updated successfully';
		
	}
	else{
		$_SESSION['error'] = "Fill up config form first";
	}

	header('location: '.$return);

?>