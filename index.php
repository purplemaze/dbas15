<?php include 'head.php'; ?>
<body>
<?php 
	if(!empty($_SESSION["_username"])) {
		include 'header_loggedin.php';
	}else {
		include 'header.php';
	}
?>
<section class="Content" id="content"> 
	<div class="createCast">
	</div>
	<?php 
		//connect to DB
		include 'connectToDB.php';
		$query = "SELECT * FROM item;";
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
			echo '<form method="post" action="buy.php?';
			echo "id=" . $line['id'];
			echo '">';
			echo '<input type="submit" value="Buy">'
				, '</form>';
			echo '</div>';

			/*
			echo " " . $line['price'];
			echo " " . $line['description'];
			if(empty($line['sellerId'])) {
				echo " Uknown Seller";
			} else {
				echo " " . $line['sellerId'];
			}
			echo " " . date("Y-m-d H:i:s");
			echo '<br>';
			echo json_encode($line);
			//echo $line['id']); //remove the index of the item
		    echo "\t<tr>\n";
		    foreach ($line as $col_value) {
		        echo "\t\t<td>$col_value</td>\n";
		    }
		    echo "\t</tr>\n";
		    */
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