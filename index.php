<?php include 'head.php'; ?>
<body>
<?php 
	include 'header_loggedin.php';
?>
<section class="Content" id="content"> 
	<div class="createCast">
	</div>
	<?php 
		//connect to DB
		include 'connectToDB.php';
		$query = "SELECT * FROM item WHERE sold = FALSE;";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		// Printing results in HTML
		while ($line = pg_fetch_assoc($result)) {
			echo '<div class="itembox">';
			echo $line['name']; 
			echo " ";
			echo " " . $line['price'] . ":-";
			echo " " . date("Y-m-d H:i:s");
			echo '<br>';
			if(empty($line['sellerid'])) {
				echo " Uknown Seller";
			} else {
				echo "Seller: " . $line['sellerid'];
			}
			echo '<br>';
			echo "description: " . $line['description'];
			echo '<br><br>';
			echo '<a href="buy.php?id=' . $line['id'] . '"> buy </a>';
			echo '</div>';
		}
		// Free resultset
		pg_free_result($result);
		// Closing connection
		pg_close($dbconn);
	 ?>  	
</section>

<script src="js/banner.js"></script>
</body>
</html>