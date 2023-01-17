<?php  
	include('database_connection.php');
	session_start();

	$time = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

	//********************************** User Registration **********************************

	if(isset($_POST['action'])){
		if($_POST['action'] == 'create_new_account'){
			$message = '';

            $f_name = $_POST["f_name"];
			$l_name = $_POST["l_name"];
			$email = trim($_POST["email"]);
			$password = trim($_POST["pass"]);
			
		    $rand = rand(1, 3);

			if($rand == 1)
				$profile_pic = "head_red.png";
			else if($rand == 2)
				$profile_pic = "head_sun_flower.png";
			else if($rand == 3)
				$profile_pic = "head_turqoise.png";
			

			$check_query = "SELECT * FROM users WHERE email = :email";
			$statement = $connect->prepare($check_query);
			$check_data = array(
				':email'		=>	$email
			);
			if($statement->execute($check_data))
			{
				if($statement->rowCount() > 0)
				{
					$message .= '<div class="alert alert-warning" role="alert">Email Already Registered</div><input type="number" id="signup_st" style="display: none;" value="1">';
				}
				else
				{
	                $uniqid = uniqid().time();

					$data = array(
						':email'		=>	$email,
						':password'		=>	password_hash($password, PASSWORD_DEFAULT),
						':f_name'	    =>	$f_name,
						':l_name'	    =>	$l_name,
						':profile_pic'	=>	$profile_pic,
						':uniqid'	    =>	$uniqid
					);

					$query = "INSERT INTO users (first_name, last_name, email, password, profile_image, uniqid, joined) VALUES (:f_name, :l_name, :email, :password, :profile_pic, :uniqid, NOW())";

					$statement = $connect->prepare($query);

					if($statement->execute($data)){
                        $query = "SELECT * FROM users WHERE email = '".$_POST['email']."'";
                        $statement = $connect->prepare($query);
                        $statement->execute();
                        $result = $statement->fetchAll();
                        foreach($result as $row)
                        {
                            $_SESSION['user_id'] = $row['id'];
		                    $_SESSION['email'] = $row['email'];
		                    $_SESSION['uniqid'] = $row['uniqid'];

							$sub_query = "INSERT INTO login_details(user_id, last_activity) VALUES ('".$row['id']."', '$time')";
							$statement = $connect->prepare($sub_query);
							$statement->execute();
							$_SESSION['login_details_id'] = $connect->lastInsertId();

		                    echo "<script>window.open('messages.php','_self')</script>";
                        }

                        $message .= '<div class="alert alert-success" role="alert">Successfully Signed Up</div><input type="number" id="signup_st" style="display: none;" value="0">';
                    }
					
				}
			}

			echo $message;
		}

		//********************************** User Login **********************************


		if($_POST['action'] == 'login_to_account'){
			sleep(1);
			$error_msg = '';

			$query = "SELECT * FROM users WHERE email = '".$_POST['email']."'";
			$statement = $connect->prepare($query);
			$statement->execute();

			$count = $statement->rowCount();
			if($count > 0)
			{
				$result = $statement->fetchAll();
				foreach($result as $row)
				{
					if(password_verify($_POST['pass'], $row['password']))
					{
                        
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['uniqid'] = $row['uniqid'];
						
						$sub_query = "INSERT INTO login_details(user_id, last_activity) VALUES ('".$row['id']."', '$time')";
						$statement = $connect->prepare($sub_query);
						$statement->execute();
						$_SESSION['login_details_id'] = $connect->lastInsertId();

                        echo "<script>window.open('messages.php','_self')</script>";
		                
					}
					else
					{
						$error_msg = '<div class="alert alert-danger" role="alert" style="text-align: center;">Wrong Password</div><input type="number" id="login_st" style="display: none;" value="1">';
					}
				}
			}
			else
			{
				$error_msg = '<div class="alert alert-warning" role="alert" style="text-align: center;">No user found for this Email</div><input type="number" id="login_st" style="display: none;" value="1">';
			}

			echo $error_msg;
		}
	}
?>