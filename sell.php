<?php
// Start the session
session_start();
if(empty($_SESSION["_username"])) {
	header("Location: login.php");
	exit;
}
$seller = false;
$email = $_SESSION["_username"];
//connect to DB
include 'connectToDB.php';
$query = "SELECT COUNT(*) FROM seller WHERE email = '$email';";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
if($row[0] == 1) {
	$seller = true;
}
// Free resultset
pg_free_result($result);

//define variable and set to empty values
$nameErr = $priceErr = "";
$error = false;
$name = $description = $paddress = "";
$price;
$brnumber;
$banumber;
$item_created = false;

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(empty($_POST["name"])) {
		$nameErr = "Name is required";
		$error = true;
	} else {
		$name = test_input($_POST["name"]);
	}
	if(empty($_POST["price"])) {
		$priceErr = "Price is required";
		$error = true;
	} else {
		$price = test_input($_POST["price"]);
	}
	$description = test_input($_POST["description"]);
	$username = $_SESSION["_username"];
	// if not a seller
	if((!$seller) && (!$error)) {
		$paddress = test_input($_POST["pickupaddress"]);
		$brnumber = test_input($_POST["bankrountingnumber"]);
		$banumber = test_input($_POST["bankaccountnumber"]);
		/*
		 email              | text     | not null
		 pickupaddress      | text     | not null
		 bankrountingnumber | smallint | not null
		 bankaccountnumber  | bigint   | not null
		*/
		// add person to seller in the DB
		$query = "INSERT INTO seller(email, pickupaddress, bankrountingnumber, bankaccountnumber)
				 VALUES ('$username', '$paddress', '$brnumber', '$banumber');";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		// Free resultset
		pg_free_result($result);
		/*
		 id          | integer                     | not null default nextval('item_id_seq'::regclass)
		 price       | integer                     | not null
		 description | text                        | 
		 name        | text                        | not null
		 sellerid    | text                        | 
		 createdat   | timestamp without time zone |
		*/
		$query = "INSERT INTO item (name, price, description, sellerid) 
				 VALUES ('$name', '$price', '$description', '$username');";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		$item_created = true;
		// Free resultset
		pg_free_result($result);
		// Closing connection
		pg_close($dbconn);


	}else if(!$error) {
		$query = "INSERT INTO item (name, price, description, sellerid) 
				 VALUES ('$name', '$price', '$description', '$username');";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		$item_created = true;
		// Free resultset
		pg_free_result($result);
		// Closing connection
		pg_close($dbconn);
	}
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
<body>
<?php 
	if(!empty($_SESSION["_username"])) {  // is now redudant remove plix
		include 'header_loggedin.php';
	}else {
		include 'header.php';
	}
	if($item_created) {
		include 'sell/sold.php';
	}else if(!$seller) {
		include 'sell/form_not_seller.php';
	}else {
		include 'sell/form.php';
	}
?>
<script src="js/banner.js"></script>
</body>
</html>