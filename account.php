<?php
// Start the session
session_start();
if(empty($_SESSION["_username"])) {
	header("Location: login.php");
	exit;
}

//input tester
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$email = $_SESSION["_username"];

//connect to DB
include 'connectToDB.php';


if(isset($_GET["sign"])) {
	$result = $dbPDO->prepare("UPDATE contract SET signed = TRUE, signedat = NOW() WHERE contractid = :cid;");
	$result->execute(["cid" => $_GET["sign"]]);
}else if (isset($_GET["pay"])) {
	$result = $dbPDO->prepare("UPDATE contract SET payed = TRUE, payedat = NOW() WHERE contractid = :cid;");
	$result->execute(["cid" => $_GET["pay"]]);
}else if (isset($_GET["pickup"])) {
	$result = $dbPDO->prepare("UPDATE package SET pickedup = TRUE, pickedupat = NOW() WHERE packageid = :pid;");
	$result->execute(["pid" => $_GET["pickup"]]);
}else if (isset($_GET["drop"])) {
	$result = $dbPDO->prepare("UPDATE package SET dropped = TRUE, droppedofat = NOW() WHERE packageid = :pid;");
	$result->execute(["pid" => $_GET["drop"]]);
}else if (isset($_GET["confirm"])) {
	$result = $dbPDO->prepare("UPDATE package SET confirmed = TRUE, confirmedat = NOW() WHERE packageid = :pid;");
	$result->execute(["pid" => $_GET["confirm"]]);
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
	<section class="Content" id="accountContent">
	 <div>
	 	<h3> My Proposals </h3> 
	 	<?php 
	 		$result = $dbPDO->prepare("SELECT * FROM proposal NATURAL JOIN item INNER JOIN buyer 
	 			ON buyerId = email WHERE sellerId=:sid AND sold = FALSE");
			$result->execute(["sid" => $email]);
			while($row = $result->fetch()) {
			?>
				<div class="itembox">
					<?=$row["name"]?>
					<?=$row["email"]?>
					<a href="contract.php?itemid=<?=$row["id"]?>&buyerid=<?=$row["buyerid"]?>">accecpt</a>
					<br>
					<?=$row["deliveryaddress"]?>
				</div>
			<?php
			}

	 	?>
	 </div>
	 <div>
	 	<h3> My Contracts </h3>
	 	<?php 
	 		$result = $dbPDO->prepare("SELECT contractid, buyerid, opened, signed, payed, taken, completed FROM contract 
	 			WHERE sellerid = :sid;");
			$result->execute(["sid" => $email]);
				while($row = $result->fetch()) {
			?>
				<div class="itembox">
					<?=$row["contractid"]?>
					<?=$row["buyerid"]?>
					<?php 
						if($row["signed"] == false) {
							echo '<a href="?sign=' . $row["contractid"] . '"> sign </a>';
						}else if($row['payed'] == false) {
							echo '<br> waiting for payment';
						}else if($row['taken'] == false) {
							echo '<br> waiting for pick-up';
						}else if($row['completed'] == false) {
							echo '<br> waiting for delivery';
						}else {
							echo '<br> contract completed';
						}
					?>
				</div>
			<?php
			}
	 	?>

	 </div>
	 <div>
	 	<h3> My Bids </h3>
	 	<?php 
	 		$result = $dbPDO->prepare("SELECT contractid, opened, signed, payed, taken, completed 
	 			FROM contract WHERE buyerid = :bid;");
			$result->execute(["bid" => $email]);
				while($row = $result->fetch()) {
			?>
				<div class="itembox">
					<?=$row["contractid"]?>
					<?php 
						if($row["signed"] == false) {
							echo 'waiting for seller to sign';
						}else if($row['payed'] == false) {
							echo '<a href="?pay=' . $row["contractid"] . '" onclick="return prompt(\'enter cardnumber: \')"> pay </a>';
						}else if($row['taken'] == false) {
							echo '<br> waiting for pick-up';
						}else if($row['completed'] == false) {
							echo '<br> waiting for delivery';
						}else {
							echo '<br> contract completed';
						}
					?>
				</div>
			<?php
			}
	 	?>
	 </div>
	 <div>
	 	<h3> My Deliveries </h3>
	 	<?php 
	 		$result = $dbPDO->prepare("SELECT packageid, contractid, (length*width*height)/1000000 AS price, 
	 			deliveryaddress, dropped, pickedup, confirmed, buyerid, driverid
	 			FROM package NATURAL JOIN contract INNER JOIN buyer ON email = buyerid 
	 			WHERE (driverid = :email OR buyerid = :email2) AND payed = TRUE;");
			$result->execute(["email" => $email, "email2" => $email]);
				while($row = $result->fetch()) {
					$isdriver = $row["driverid"] == $email;
			?>
				<div class="itembox">
					cid: <?=$row["contractid"]?>
					pid: <?=$row["packageid"]?>
					price: <?=$row["price"]?><br>
					address: <?=$row["deliveryaddress"]?>

					<?php 
						if($isdriver) {
							if($row["pickedup"] == false) {
								echo '<a href="?pickup=' . $row["packageid"] . '"> pick up </a>';
							}else if($row['dropped'] == false) {
								echo '<a href="?drop=' . $row["packageid"] . '"> drop off </a>';
							}else if($row['confirmed'] == false) {
								echo '<br> waiting for confirmation';
							}else {
								echo '<br> package completed';
							}
						}else {
							if($row["pickedup"] == false) {
								echo 'Waiting for driver';
							}else if($row['dropped'] == false) {
								echo 'Being delivered';
							}else if($row['confirmed'] == false) {
								echo '<a href="?confirm=' . $row["packageid"] . '"> confirm delivery </a>';
							}else {
								echo '<br> package completed';
							}
						}
					?>
				</div>
			<?php
			}
	 	?>
	 </div> 
</section>
<script src="js/banner.js"></script>
</body>
</html>