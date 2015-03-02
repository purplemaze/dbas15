<?php include 'head.php'; ?>

<body>

<?php 
	$_SESSION["_username"] = "";
	if(!empty($_SESSION["_username"])) {
		include 'header_loggedin.php';
	}else {
		include 'header.php';
	}
?>

<section class="Content" id="content"> 
		<div id="logged_out"> You have now logged out </div>
</section>

<script src="js/banner.js"></script>
</body>
</html>