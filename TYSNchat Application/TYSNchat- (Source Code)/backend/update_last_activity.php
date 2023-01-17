<?php

//update_last_activity.php

	include('database_connection.php');

	session_start();

	$time = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));


	$query = "UPDATE login_details SET last_activity = '$time' WHERE login_details_id = '".$_SESSION["login_details_id"]."'";

	$statement = $connect->prepare($query);

	$statement->execute();

?>