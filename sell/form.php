<section class="Content" id="content"> 
		<div id="form">
		<h2> Sell an Item </h2>
		<p><span class="error">* required field.</span></p>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			Name: 
			<input type="text" name="name">
			<span class="error"> * <?php echo $nameErr; ?></span>
			<br>
			Price: 
			<input type="number" name="price">
			<span class="error"> * <?php echo $priceErr; ?></span>
			<br>
			Description: <br>
			<textarea name="description" rows="5" cols="40"></textarea><br>
			<input type="submit" value="Sell item">
		</form>
		</div>
</section>