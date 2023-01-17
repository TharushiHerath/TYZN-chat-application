<?php 
	$con = mysqli_connect("localhost","root","","tysnchat");
    $connect = new PDO("mysql:host=localhost;dbname=tysnchat;charset=utf8mb4", "root", "");

    function fetch_user_last_activity($user_id, $connect)
	{
	 	$query = "
		SELECT * FROM login_details 
		WHERE user_id = '$user_id' 
		ORDER BY last_activity DESC 
		LIMIT 1
	 	";
	 	$statement = $connect->prepare($query);
	 	$statement->execute();
	 	$result = $statement->fetchAll();
	 	foreach($result as $row)
	 	{
	  		return $row['last_activity'];
	 	}
	}

	function totalActiveUser($connect)
	{
	 	$query = "SELECT user_id,last_activity FROM login_details GROUP BY user_id ORDER BY last_activity ASC";
	 	$statement = $connect->prepare($query);
	 	$statement->execute();

		$count = $statement->rowCount();

	  	return $count;
	 	
	}

	function Get_user_image($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = '".$user_id."' ";

		$statement = $connect->prepare($query);

		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["profile_image"];
		}
	}

	function Get_user_fname($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = '".$user_id."' ";

		$statement = $connect->prepare($query);

		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["first_name"];
		}
	}
	function Get_user_lname($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = '".$user_id."' ";

		$statement = $connect->prepare($query);

		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["last_name"];
		}
	}

	function get_to_refresh($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = ".$user_id."";	
		$statement = $connect->prepare($query);
		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["to_refresh"];
		}
	}

	function get_group_refresh($connect, $user_id)
	{
		$query = "SELECT * FROM chat_group WHERE group_id = ".$user_id."";	
		$statement = $connect->prepare($query);
		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["refresh"];
		}
	}

	function get_room_refresh($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = ".$user_id."";	
		$statement = $connect->prepare($query);
		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["to_refresh_room"];
		}
	}

	function get_sst($connect, $user_id)
	{
		$query = "SELECT * FROM users WHERE id = ".$user_id."";	
		$statement = $connect->prepare($query);
		$statement->execute();

		$result = $statement->fetchAll();

		foreach($result as $row)
		{
			return $row["sound_status"];
		}
	}
?>