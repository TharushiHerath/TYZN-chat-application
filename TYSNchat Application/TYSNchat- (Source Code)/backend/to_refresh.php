<?php 
	include('database_connection.php');

	session_start();

	echo "<script>to_const_send=".get_to_refresh($connect, $_SESSION['user_id'])."</script>";
	
 ?>