<?php
// Start the session
session_start();
if(empty($_SESSION["_username"])) {
	header("Location: login.php");
	exit;
}

//connect to DB
include 'connectToDB.php';

//input tester
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


$itemId = $_GET["itemid"];
$buyerId = $_GET["buyerid"];
$contract_completed = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {

	// Get the item price
	$result = $dbPDO->prepare("SELECT price FROM item WHERE id = :itemid;");
	$result->execute(["itemid" => $itemId]);
	// Store the price in $price
	$price = $result->fetch()["price"];

	// Set up a new contract
	$result = $dbPDO->prepare("INSERT INTO contract(priceNet, sellerId, buyerId, openedAt, opened) 
								VALUES (:price, :sellerId, :buyerId, NOW(), TRUE) RETURNING contractid;");
	$result->execute(["price" => $price, "sellerId" => $_SESSION["_username"], "buyerId" => $buyerId]);
	$contractId = $result->fetch()["contractid"];
	// prepare statement
	$result = $dbPDO->prepare("INSERT INTO package(contractid, length, width, weight, height) 
						VALUES (:cid, :l, :wd, :we, :h);");	
	// Create packages
	foreach ($_POST["length"] as $i => $length) {
		$width = $_POST["width"][$i];
		$height = $_POST["height"][$i];
		$weight = $_POST["weight"][$i];

		// Creat a new package
		$result->execute(["cid" => $contractId,  "l" => $length, "wd" => $width, "we" => $weight, "h" => $height]);
	}

		$result = $dbPDO->prepare("UPDATE item SET sold = TRUE WHERE id = :itemid;");
		$result->execute(["itemid" => $itemId]);

	$contract_completed = true;
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
	include 'header_loggedin.php';
	if (!$contract_completed) {
?>
	<section class="Content" id="content">
		<h3> Devide item into packages </h3>
		<div id="input">
			<div>
				length: <input type="number" name="length[]">
				width: <input type="number" name="width[]">
				height: <input type="number" name="height[]">
				weight: <input type="number" name="weight[]">
			</div>
		</div>
		<form method="post" action="?itemid=<?=$itemId?>&buyerid=<?=$buyerId?>">
			<div id="packageForm">
			</div>
			<input type="button" value="more packages" onclick="addPackage();return false">
			<input type="submit" value="submit">
		</form>
<?php 
	} else {
		echo "Setup complete!";
	}
?>
</section>
<script src="js/banner.js"></script>
<script>
function addPackage() {
	//	var newel = $("<div>")
	$("#input").children().clone().appendTo("#packageForm")
	//newel.prependTo("#packageForm")
}
addPackage();
</script>
</body>
</html>