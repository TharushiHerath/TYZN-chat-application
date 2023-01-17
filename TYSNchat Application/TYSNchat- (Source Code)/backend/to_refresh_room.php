<?php 
	include('database_connection.php');

	session_start();

	echo "<script>to_const_room=".get_room_refresh($connect, $_SESSION['user_id'])."</script>";
	
 ?>