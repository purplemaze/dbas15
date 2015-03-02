 	<?php

    $host = "localhost";
    $user = "postgres";
    $pass = "dbas15"; 
    $db	  = "lab2";	

	// Connecting, selecting database
	$dbconn = pg_connect("host=$host dbname=$db user=$user password=$pass")
	    or die('Could not connect: ' . pg_last_error());

	$dbPDO = new PDO("pgsql:host=$host;dbname=$db;user=$user;password=$pass");

	?>