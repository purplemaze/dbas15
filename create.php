<?php
// Start the session
session_start();

	//define variable and set to empty values
	$nameErr = "";
	$error = false;
	$name = "";
	$created_acc = false;

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	if($_SERVER["REQUEST_METHOD"] == "POST") {

		if(empty($_POST["name"])) {
			$nameErr = "A valid email is required";
			$error = true;
		} else {
			$name = test_input($_POST["name"]);

			//connect to DB
			include 'connectToDB.php';

			$query = "INSERT INTO person(email, balance) VALUES ('$name', 0);";

			$result = pg_query($query) or die('Query failed: ' . pg_last_error());

			// Free resultset
			pg_free_result($result);


			// Closing connection
			pg_close($dbconn);
			$created_acc = true;
		}
	}
	// redirect if we succesfully created an account
	if($created_acc) {
		$header = "Location: login.php?";
		header($header);
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

<body>

<?php 
	if(!empty($_SESSION["_username"])) {
		include 'header_loggedin.php';
	}else {
		include 'header.php';
	}
?>

<section class="Content" id="content"> 
		<div id="form">
		<h2> Create account </h2>
		<p><span class="error">* required field.</span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Supply a valid email address: 
			<input type="text" name="name">
			<span class="error">* <?php echo $nameErr; ?></span>
			<br>
			<input type="submit" value="Create">
		</form>
		
		<?php 
			echo $login_result;
		?>
		</div>
</section>

<script src="js/banner.js"></script>
</body>
</html>