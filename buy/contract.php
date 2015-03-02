<?php
// Start the session
session_start();
if(empty($_SESSION["_username"])) {
	header("Location: login.php");
	exit;
}
$buyer = false;
$email = $_SESSION["_username"];
$itemId = $_GET["id"];
$proposal_created = false;

//connect to DB
include 'connectToDB.php';
// check if the user is a buyer
$result = $dbPDO->prepare("SELECT * FROM buyer WHERE email = ?;");
$result->execute([$email]);

if($result->rowCount() == 1) {
	$buyer = true;
}

//input tester
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$dAddress = $_POST["dAddress"];
	$result = $dbPDO->prepare("INSERT INTO buyer(email, deliveryaddress) VALUES (?, ?);");
	$result->execute([$email, $dAddress]);
	$buyer = true;
}


if($buyer) {
	$query = "INSERT INTO proposal(id, buyerId) VALUES (?, ?);";
	$result = $dbPDO->prepare($query);
	$result->execute([$itemId, $email]);
	$proposal_created = true;
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
<?php include 'header_loggedin.php';?>
	<section class="Content" id="content">
<?php
	if($proposal_created) {
		echo 'Proposal sent to seller <br>'; 
	}else {
	?>
	<br>
	<form method="post" action="?id=<?=$itemId?>">
		Deliviery addrss: <input type ="text" name="dAddress"> 
		<input type="submit">
	<?php
	}
?>
</section>
<script src="js/banner.js"></script>
</body>
</html>