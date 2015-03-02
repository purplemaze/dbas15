<?php
// Start the session
session_start();
if(empty($_SESSION["_username"])) {
	header("Location: login.php");
	exit;
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<title>crowdSales.com</title>
  	<meta name="description" content="Sales by Crowd">
  	<meta name="author" content="purplemaze">

  	<link rel="stylesheet" href="css/main.css">
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>