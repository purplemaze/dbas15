<?php include 'head.php'; 
	//connect to DB
	include 'connectToDB.php';

	$email = $_SESSION["_username"];
	$newdriver = false;

	if (isset($_POST["bankrountingnumber"])) {
		$result = $dbPDO->prepare("INSERT INTO driver (driverid, bankrountingnumber, bankaccountnumber) 
									VALUES (:did, :brn, :ban)");
		$result->execute([
			"did" => $email,
			"brn" => $_POST["bankrountingnumber"],
			"ban" => $_POST["bankaccountnumber"],
		]);

	}

	if(isset($_GET["deliver"])) {

		//check if user is a driver
		$result = $dbPDO->prepare("SELECT * FROM driver WHERE driverid = :email;");
		$result->execute(["email" => $email]);

		if ($result->rowCount() == 1) {
			$result = $dbPDO->prepare("UPDATE contract SET taken = TRUE, takenat = NOW(), driverid = :email WHERE contractid = :cid;");
			$result->execute(["cid" => $_GET["deliver"], "email" => $email]);
		} else {
			$newdriver = true;
		}
	}
?>
<body>
<?php 
	include 'header_loggedin.php';
?>
<section class="Content" id="content"> 
	<div class="createCast">
	<?php
	if ($newdriver) {
		?>
		<form method="post" action="?deliver=<?=$_GET["deliver"]?>">
			Bank routing number:
			<input type="number" name="bankrountingnumber"><br>
			Bank Account Number: 
			<input type="number" name="bankaccountnumber"><br><br>
			<input type="submit" value="Submit">
		</form>
		<?php
	}
	else {
		$result = $dbPDO->prepare("SELECT * FROM contract INNER JOIN buyer 
	 			ON buyerid = email WHERE taken = FALSE AND payed = TRUE;");
		$result->execute();
		while($row = $result->fetch()) {
		?>
		<div class="itembox">
			<?=$row["contractid"]?>
			<?=$row["deliveryaddress"]?>
			<?=$row["sellerid"]?><br>
			<a href="?deliver=<?=$row["contractid"]?>">deliver</a>
		</div>
		<?php
			
		}
	}
	?>
	</div>	
</section>

<script src="js/banner.js"></script>
</body>
</html>