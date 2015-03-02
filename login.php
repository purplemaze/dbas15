<?php
// Start the session
session_start();
//define variable and set to empty values
$nameErr = "";
$loggedin = false;
$error = false;
$name = $login_result= "";

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

	if(empty($_POST["name"])) {
		$nameErr = "Username is required";
		$error = true;
	} else {
		$name = test_input($_POST["name"]);
		//connect to DB
		include 'connectToDB.php';
		$query = "SELECT COUNT(*) FROM person WHERE email = '$name';";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		$row = pg_fetch_array($result);
		if($row[0] == 1) {
			$_SESSION["_username"] = $name;
			$loggedin = true;
		}else {
			$login_result = "wrong Username/e-mail";
		}
		// Free resultset
		pg_free_result($result);
		// Closing connection
		pg_close($dbconn);
	}
}
// redirect if we logged in
if($loggedin) {
	$header = "Location: index.php?". htmlspecialchars($_SESSION["_username"]);
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
		<h2> Login </h2>
		<p><span class="error">* required field.</span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Username/e-mail: 
			<input type="text" name="name">
			<span class="error">* <?php echo $nameErr; ?></span>
			<br>
			<input type="submit" value="Log in">
		</form>
		
		<?php 
			echo $login_result;
		?>
		</div>
</section>

<script src="js/banner.js"></script>
</body>
</html>