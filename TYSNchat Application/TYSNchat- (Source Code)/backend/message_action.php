<?php  
	include('database_connection.php');
	include('time_difference.php');
	session_start();

	$time = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));


    //Set profile image
    if(isset($_POST["image"]))
    {
        sleep(1);
        $data = $_POST["image"];

        $image_array_1 = explode(";", $data);

        $image_array_2 = explode(",", $image_array_1[1]);

        $data = base64_decode($image_array_2[1]);

        $imageName = rand(100000, 999999).time() . '.png';

        file_put_contents(__DIR__ . '/../profile_images/'.$imageName, $data);

        echo '<img src="profile_images/'.$imageName.'" class="img-circle" style="margin-top: 5px;" />';

        $update = "UPDATE user SET profile_image='$imageName' WHERE id=".$_SESSION['user_id']."";
        mysqli_query($con, $update);
        
    }


	if(isset($_POST['action'])){
		if($_POST['action'] == 'fetch_all_users'){
			$condition = '';

            if(!empty($_POST["query"]))
            {
                $search_query = preg_replace('#[^a-z 0-9?!]#i', '', $_POST["query"]);

                $search_array = explode(" ", $search_query);

                $condition = ' AND (';

                foreach($search_array as $search)
                {
                    if(trim($search) != '')
                    {
                        $condition .= "users.first_name LIKE '%".$search."%' OR ";
                        $condition .= "users.last_name LIKE '%".$search."%' OR ";
                    }
                }

                $condition = substr($condition, 0, -4) . ") ";
            }

            $query = "SELECT users.first_name, users.last_name, users.uniqid, users.profile_image, chats.from_user_id, chats.user_status, chats.pinned, chats.chat_id, chats.to_user_id, chats.last_msg, chats.timestamp FROM users INNER JOIN chats ON chats.from_user_id = users.id OR chats.to_user_id = users.id WHERE ((chats.from_user_id = '".$_SESSION["user_id"]."' OR chats.to_user_id = '".$_SESSION["user_id"]."') AND chats.user_status = '0' AND chats.user_status = '0' AND chats.pinned='0') AND users.id != '".$_SESSION["user_id"]."' ".$condition." ORDER BY chats.timestamp DESC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $html = '';

            if($statement->rowCount() > 0)
            {
                $count = 0;

                foreach($statement->fetchAll() as $row)
                {
                    $temp_user_id = 0;

                    if($row["from_user_id"] == $_SESSION["user_id"])
                    {
                        $temp_user_id = $row["to_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                        
                    }
                    else
                    {
                        $temp_user_id = $row["from_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                    }

                    $notification = '';
                    $last_message = '';

                    $query = "SELECT * FROM messages WHERE from_user_id = ".$temp_user_id." AND to_user_id = ".$_SESSION['user_id']." AND status = '1' ";

                    $statement = $connect->prepare($query);
                    $statement->execute();
                    $count = $statement->rowCount();
                    $output = '';
                    if($count > 0){
                        $notification = '<i class="fa fa-circle" style="color: red; margin-left: 10px; font-size: 12px;"></i>';

                        $last_message = '<span style="float: left; margin-left: 10px; margin-right: 5px; font-size: 13px;"><strong>'.strip_tags(substr($row['last_msg'], 0, 17)).'</strong></span>';
                    }

                    else{
                        $last_message = ''.strip_tags(substr($row['last_msg'], 0, 17)).'';
                    }

                    $status = '';
                    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
                    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
                    $user_last_activity = fetch_user_last_activity($temp_user_id, $connect);

                    if($user_last_activity > $current_timestamp && $u_status=="active"){
                        $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #14BD05; font-size: 12px;"></i>';
                    }
                    else{
                        if($u_status=="busy"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #FFBF00; font-size: 12px;"></i>';
                        }

                        else if($u_status=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: red; font-size: 12px;"></i>';
                        }
                        
                    }

                    if($row['pinned'] == '1'){
                        $pin_btn = '<div id="unpin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle-fill" style="color: #FFF02A;" aria-hidden="true"></i></div>';
                    }
                    else{
                        $pin_btn = '<div id="pin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle"></i></div>';
                    }
                    
                    $html .= '
                        <a style="text-decoration: none;" href="messages.php?user='.$uid.'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" class="rounded-circle" style="float: left;">'.$status.'
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                        <div style="float: right; margin-right: 15px;">'.$notification.' '.$pin_btn.'</div>
                                    </div>
                                    <br>
                                    <div class="last_msg" style="font-size: 12px; margin-top: 1px;">
                                        <span style="margin-left: 10px;">'.$last_message.' &#X22C5; <span> '.time_diff_activity($row['timestamp']).'</span></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin-left: 80px; margin-right: 80px;"></div>
                    ';

                }
                
            }
            else
            {
                $html = '
                    <div class="text-center mt-3">No Recent Messages</div>
                ';
            }
            echo $html;
		}

        if($_POST['action'] == 'fetch_all_users_m'){
			$condition = '';

            if(!empty($_POST["query"]))
            {
                $search_query = preg_replace('#[^a-z 0-9?!]#i', '', $_POST["query"]);

                $search_array = explode(" ", $search_query);

                $condition = ' AND (';

                foreach($search_array as $search)
                {
                    if(trim($search) != '')
                    {
                        $condition .= "users.first_name LIKE '%".$search."%' OR ";
                        $condition .= "users.last_name LIKE '%".$search."%' OR ";
                    }
                }

                $condition = substr($condition, 0, -4) . ") ";
            }

            $query = "SELECT users.first_name, users.last_name, users.uniqid, users.profile_image, chats.from_user_id, chats.user_status, chats.to_user_id, chats.pinned, chats.chat_id, chats.last_msg, chats.timestamp FROM users INNER JOIN chats ON chats.from_user_id = users.id OR chats.to_user_id = users.id WHERE ((chats.from_user_id = '".$_SESSION["user_id"]."' OR chats.to_user_id = '".$_SESSION["user_id"]."') AND chats.user_status = '0' AND chats.user_status = '0' AND chats.pinned='0') AND users.id != '".$_SESSION["user_id"]."' ".$condition." ORDER BY chats.timestamp DESC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $html = '';

            if($statement->rowCount() > 0)
            {
                $count = 0;

                foreach($statement->fetchAll() as $row)
                {
                    $temp_user_id = 0;

                    if($row["from_user_id"] == $_SESSION["user_id"])
                    {
                        $temp_user_id = $row["to_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                        
                    }
                    else
                    {
                        $temp_user_id = $row["from_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                    }

                    $notification = '';
                    $last_message = '';

                    // $query = "SELECT * FROM chat_message WHERE from_user_id = ".$temp_user_id." AND to_user_id = ".$_SESSION['user_id']." AND status = '1' ";

                    // $statement = $connect->prepare($query);
                    // $statement->execute();
                    // $count = $statement->rowCount();
                    // $output = '';
                    // if($count > 0){
                    //     $notification = '<span class="badge badge-danger" style="margin-left: 10px; padding: 3px;">'.$count.'</span>';

                    //     $last_message = '<span style="float: left; color: #000000; margin-left: 10px; margin-right: 5px; font-size: 11px;"><strong>'.strip_tags(substr($row['last_msg'], 0, 17)).'</strong></span>';
                    // }

                    // else{
                        $last_message = ''.strip_tags(substr($row['last_msg'], 0, 17)).'';
                    // }

                    $status = '';
                    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
                    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
                    $user_last_activity = fetch_user_last_activity($temp_user_id, $connect);

                    if($user_last_activity > $current_timestamp && $u_status=="active"){
                        $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #14BD05; font-size: 12px;"></i>';
                    }
                    else{
                        if($u_status=="busy"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #FFBF00; font-size: 12px;"></i>';
                        }

                        else if($u_status=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: red; font-size: 12px;"></i>';
                        }
                            
                        
                    }

                    if($row['pinned'] == '1'){
                        $pin_btn = '<div id="unpin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle-fill" style="color: #FFF02A;" aria-hidden="true"></i></div>';
                    }
                    else{
                        $pin_btn = '<div id="pin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle"></i></div>';
                    }
                    
                    $html .= '
                        <a style="text-decoration: none;" href="user_messages.php?user='.$uid.'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" class="rounded-circle" style="float: left;">'.$status.'
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                        <div style="float: right; margin-right: 15px;">'.$pin_btn.'</div>
                                    </div>
                                    <br>
                                    <div class="last_msg" style="font-size: 12px; margin-top: 1px;">
                                        <span style="margin-left: 10px;">'.$last_message.' &#X22C5; <span> '.time_diff_activity($row['timestamp']).'</span></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin-left: 80px; margin-right: 80px;"></div>
                    ';

                }
                
            }
            else
            {
                $html = '
                    <div class="text-center mt-3">No Recent Messages Found</div>
                ';
            }
            echo $html;
		}

        if($_POST['action'] == 'fetch_all_users_pinned'){
			$condition = '';

            if(!empty($_POST["query"]))
            {
                $search_query = preg_replace('#[^a-z 0-9?!]#i', '', $_POST["query"]);

                $search_array = explode(" ", $search_query);

                $condition = ' AND (';

                foreach($search_array as $search)
                {
                    if(trim($search) != '')
                    {
                        $condition .= "users.first_name LIKE '%".$search."%' OR ";
                        $condition .= "users.last_name LIKE '%".$search."%' OR ";
                    }
                }

                $condition = substr($condition, 0, -4) . ") ";
            }

            $query = "SELECT users.first_name, users.last_name, users.uniqid, users.profile_image, chats.from_user_id, chats.user_status, chats.pinned, chats.chat_id, chats.to_user_id, chats.last_msg, chats.timestamp FROM users INNER JOIN chats ON chats.from_user_id = users.id OR chats.to_user_id = users.id WHERE ((chats.from_user_id = '".$_SESSION["user_id"]."' OR chats.to_user_id = '".$_SESSION["user_id"]."') AND chats.user_status = '0' AND chats.pinned='1') AND users.id != '".$_SESSION["user_id"]."' ".$condition." ORDER BY chats.timestamp DESC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $html = '';

            if($statement->rowCount() > 0)
            {
                $count = 0;

                foreach($statement->fetchAll() as $row)
                {
                    $temp_user_id = 0;

                    if($row["from_user_id"] == $_SESSION["user_id"])
                    {
                        $temp_user_id = $row["to_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                        
                    }
                    else
                    {
                        $temp_user_id = $row["from_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                    }

                    $notification = '';
                    $last_message = '';

                    // $query = "SELECT * FROM chat_message WHERE from_user_id = ".$temp_user_id." AND to_user_id = ".$_SESSION['user_id']." AND status = '1' ";

                    // $statement = $connect->prepare($query);
                    // $statement->execute();
                    // $count = $statement->rowCount();
                    // $output = '';
                    // if($count > 0){
                    //     $notification = '<span class="badge badge-danger" style="margin-left: 10px; padding: 3px;">'.$count.'</span>';

                    //     $last_message = '<span style="float: left; color: #000000; margin-left: 10px; margin-right: 5px; font-size: 11px;"><strong>'.strip_tags(substr($row['last_msg'], 0, 17)).'</strong></span>';
                    // }

                    // else{
                        $last_message = ''.strip_tags(substr($row['last_msg'], 0, 17)).'';
                    // }

                    $status = '';
                    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
                    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
                    $user_last_activity = fetch_user_last_activity($temp_user_id, $connect);

                    if($user_last_activity > $current_timestamp && $u_status=="active"){
                        $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #14BD05; font-size: 12px;"></i>';
                    }
                    else{
                        if($u_status=="busy"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #FFBF00; font-size: 12px;"></i>';
                        }

                        else if($u_status=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: red; font-size: 12px;"></i>';
                        }
                        
                    }

                    if($row['pinned'] == '1'){
                        $pin_btn = '<div id="unpin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle-fill" style="color: #FFF02A;" aria-hidden="true"></i></div>';
                    }
                    else{
                        $pin_btn = '<div id="pin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle"></i></div>';
                    }
                    
                    $html .= '
                        <a style="text-decoration: none;" href="messages.php?user='.$uid.'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" class="rounded-circle" style="float: left;">'.$status.'
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                        <div style="float: right; margin-right: 15px;">'.$pin_btn.'</div>
                                    </div>
                                    <br>
                                    <div class="last_msg" style="font-size: 12px; margin-top: 1px;">
                                        <span style="margin-left: 10px;">'.$last_message.' &#X22C5; <span> '.time_diff_activity($row['timestamp']).'</span></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin-left: 80px; margin-right: 80px;"></div>
                    ';

                }
                
            }
            echo $html;
		}

        if($_POST['action'] == 'fetch_all_users_m_pinned'){
			$condition = '';

            if(!empty($_POST["query"]))
            {
                $search_query = preg_replace('#[^a-z 0-9?!]#i', '', $_POST["query"]);

                $search_array = explode(" ", $search_query);

                $condition = ' AND (';

                foreach($search_array as $search)
                {
                    if(trim($search) != '')
                    {
                        $condition .= "users.first_name LIKE '%".$search."%' OR ";
                        $condition .= "users.last_name LIKE '%".$search."%' OR ";
                    }
                }

                $condition = substr($condition, 0, -4) . ") ";
            }

            $query = "SELECT users.first_name, users.last_name, users.uniqid, users.profile_image, chats.from_user_id, chats.user_status, chats.to_user_id, chats.pinned, chats.chat_id, chats.last_msg, chats.timestamp FROM users INNER JOIN chats ON chats.from_user_id = users.id OR chats.to_user_id = users.id WHERE ((chats.from_user_id = '".$_SESSION["user_id"]."' OR chats.to_user_id = '".$_SESSION["user_id"]."') AND (chats.user_status = '0' AND chats.pinned='1')) AND users.id != '".$_SESSION["user_id"]."' ".$condition." ORDER BY chats.timestamp DESC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $html = '';

            if($statement->rowCount() > 0)
            {
                $count = 0;

                foreach($statement->fetchAll() as $row)
                {
                    $temp_user_id = 0;

                    if($row["from_user_id"] == $_SESSION["user_id"])
                    {
                        $temp_user_id = $row["to_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                        
                    }
                    else
                    {
                        $temp_user_id = $row["from_user_id"];

                        $get_user = "SELECT * FROM users WHERE id=".$temp_user_id."";
                        $run_user = mysqli_query($con,$get_user);
                        $row1 = mysqli_fetch_array($run_user);

                        $uid = $row1['uniqid'];
                        $u_status = $row1['status'];
                    }

                    $notification = '';
                    $last_message = '';

                    // $query = "SELECT * FROM chat_message WHERE from_user_id = ".$temp_user_id." AND to_user_id = ".$_SESSION['user_id']." AND status = '1' ";

                    // $statement = $connect->prepare($query);
                    // $statement->execute();
                    // $count = $statement->rowCount();
                    // $output = '';
                    // if($count > 0){
                    //     $notification = '<span class="badge badge-danger" style="margin-left: 10px; padding: 3px;">'.$count.'</span>';

                    //     $last_message = '<span style="float: left; color: #000000; margin-left: 10px; margin-right: 5px; font-size: 11px;"><strong>'.strip_tags(substr($row['last_msg'], 0, 17)).'</strong></span>';
                    // }

                    // else{
                        $last_message = ''.strip_tags(substr($row['last_msg'], 0, 17)).'';
                    // }

                    $status = '';
                    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
                    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
                    $user_last_activity = fetch_user_last_activity($temp_user_id, $connect);

                    if($user_last_activity > $current_timestamp && $u_status=="active"){
                        $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #14BD05; font-size: 12px;"></i>';
                    }
                    else{
                        if($u_status=="busy"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: #FFBF00; font-size: 12px;"></i>';
                        }

                        else if($u_status=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-left: -11px; border: 1px solid white; margin-top: 25px; background: white; color: red; font-size: 12px;"></i>';
                        }
                        
                    }

                    if($row['pinned'] == '1'){
                        $pin_btn = '<div id="unpin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle-fill" style="color: #FFF02A;" aria-hidden="true"></i></div>';
                    }
                    else{
                        $pin_btn = '<div id="pin_chat" style="cursor: pointer; padding: 5px;" data-chat_id="'.$row['chat_id'].'"><i class="bi bi-pin-angle"></i></div>';
                    }
                    
                    $html .= '
                        <a style="text-decoration: none;" href="user_messages.php?user='.$uid.'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" class="rounded-circle" style="float: left;">'.$status.'
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                        <div style="float: right; margin-right: 15px;">'.$pin_btn.'</div>
                                    </div>
                                    <br>
                                    <div class="last_msg" style="font-size: 12px; margin-top: 1px;">
                                        <span style="margin-left: 10px;">'.$last_message.' &#X22C5; <span> '.time_diff_activity($row['timestamp']).'</span></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin-left: 80px; margin-right: 80px;"></div>
                    ';

                }
                
            }
            echo $html;
		}

        //Send Message Room
        if($_POST["action"] == "send_message_room"){
            $content = $_POST['message_text_room'];
            $content=preg_replace("#\n#", "<br>\n", $content);
            $content=preg_replace("#  #", "&nbsp;", $content);
    
            $data = array(
                 ':msg_from_id'  	    => $_SESSION['user_id'],
                 ':chat_message'  	    => ''.$content.'',
                 ':msg_status'   		=> 1
            );
    
            $query = "INSERT INTO room_messages (chat_message, msg_from_id, message_date, msg_status) VALUES (:chat_message, :msg_from_id, '$time', :msg_status)";
    
            $statement = $connect->prepare($query);

            if($statement->execute($data)){
                $query = "UPDATE users SET to_refresh_room='1' Where id != ".$_SESSION['user_id']." ";
                $statement = $connect->prepare($query);
                $statement->execute();
            }
        }

        //fetch_room_messages
        if($_POST["action"] == "fetch_room_messages"){
            $user_status= '';
            $query = "SELECT * FROM room_messages ORDER BY message_date ASC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $result = $statement->fetchAll();

            $output = '';

            if($statement->rowCount() > 0)
            {
                foreach($result as $row)
                {
                    $message = '';
                    $seen = '';
                    $dynamic_background = '';
                    $chat_message = '';

                    $time = time_diff_activity($row["message_date"]);

                    if($row["msg_from_id"] == $_SESSION['user_id']){
                        $message = '
                            <div class="row">
                                <div class="col">
                                    <div class="my_message border-0 text-right float-right text-white">
                                        '.$row["chat_message"].'
                                    </div>
                                    <div class="float-right text-center text-dark mr-1" style="font-size: 11px;">
                                        '.date('d M, Y', strtotime($row['message_date'])).', '.date('H:i A', strtotime($row['message_date'])).'
                                    </div>
                                </div>
                                <div style="float: right;">
                                    <img src="profile_images/'.Get_user_image($connect, $_SESSION['user_id']).'" class="rounded-circle" height="35px" width="35px" style="float: left; margin-left: 5px; margin-top: 5px;" />
                                </div>
                            </div>
                        ';
                    }
                    else{
                        $message = '
                        <div class="row">
                            <div style="float: left;">
                                <img src="profile_images/'.Get_user_image($connect, $row["msg_from_id"]).'" height="35px" width="35px" style="float: left; margin-right: 5px; margin-top: 5px;" class="rounded-circle" title="'.Get_user_fname($connect, $row["msg_from_id"]).' '.Get_user_lname($connect, $row["msg_from_id"]).'" />
                            </div>
                            <div class="col">
                                <div class="user_message border-0 text-left float-left text-dark">
                                    '.$row["chat_message"].'
                                </div>
                                <div class="float-left text-dark text-center ml-1" style="font-size: 11px;">
                                    '.date('d M, Y', strtotime($row['message_date'])).', '.date('H:i A', strtotime($row['message_date'])).'
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    $output .= ''.$message.'<br>';
                }
            }
            else{
                $output = '<div style="text-align: center; margin-top: 150px;"><h5>No Message Found</h5></div>';
            }

            $query = "UPDATE users SET to_refresh_room='0' Where id = ".$_SESSION['user_id']." ";
            $statement = $connect->prepare($query);
            $statement->execute();

            echo $output;
        }


        if($_POST["action"] == "send_message_messenger"){
            $content = $_POST['message_text'];
            $content=preg_replace("#\n#", "<br>\n", $content);
            $content=preg_replace("#  #", "&nbsp;", $content);
            $image_name = $_POST['image_name'];
            $file_ext = $_POST['file_ext'];

            if($file_ext == 'jpg' || $file_ext == 'png' || $file_ext == 'gif' || $file_ext == 'jpeg' || $file_ext == 'JPG' || $file_ext == 'PNG' || $file_ext == 'GIF' || $file_ext == 'JPEG'){
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><img src="uploads/'.$_POST['image_name'].'" data-toggle="modal" data-target="#view_full_size_image" id="preview_image" data-img_name="'.$_POST['image_name'].'" class="img-fluid" height="150px" />';
                }
                else if($content == '' && $_POST['image_name'] != ''){
                    $msg_text = '<img src="uploads/'.$_POST['image_name'].'" data-toggle="modal" data-target="#view_full_size_image" id="preview_image" data-img_name="'.$_POST['image_name'].'" class="img-fluid" height="150px" />';

                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
                
            }
            else if($file_ext == 'mp4' || $file_ext == 'mkv' || $file_ext == 'MP4' || $file_ext == 'MKV'){
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><div class="embed-responsive embed-responsive-16by9" style="height: 150px; width: 250px;"><video class="embed-responsive-item" controls="controls" src="uploads/'.$_POST['image_name'].'"></video></div>';
                }
                else if($content == '' && $_POST['image_name'] != '') {
                    $msg_text = '<div class="embed-responsive embed-responsive-16by9" style="height: 150px; width: 250px;"><video class="embed-responsive-item" controls="controls" src="uploads/'.$_POST['image_name'].'"></video></div>';

                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
                
            }
            else{
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><a href="file_download.php?name='.$_POST['image_name'].'" style="background: white; padding: 5px; border-radius: 3px; color: black; text-decoration: none;"><i>'.$_POST['image_name'].'</i></a>';
                }
                else if($content == '' && $_POST['image_name'] != ''){
                    $msg_text = '><a href="file_download.php?name='.$_POST['image_name'].'" style="background: white; padding: 5px; border-radius: 3px; color: black; text-decoration: none;"><i>'.$_POST['image_name'].'</i></a>';
                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
            }
    
            $data = array(
                 ':to_user_id'  		=> $_POST['to_user_id'],
                 ':from_user_id'  	    => $_SESSION['user_id'],
                 ':chat_message'  	    => $msg_text,
                 ':status'   		    => '1',
                 ':notification_status' => '1'
            );
    
            $query = "INSERT INTO messages (to_user_id, from_user_id, chat_message, status, notification_status, timestamp) VALUES (:to_user_id, :from_user_id, :chat_message, :status, :notification_status, '$time')";
    
            $statement = $connect->prepare($query);
    
            if($statement->execute($data)){
                $query_get_count = "SELECT * FROM top_messages WHERE (from_id='".$_SESSION['user_id']."' AND to_id='".$_POST['to_user_id']."') AND msg='$content'";
                $statement = $connect->prepare($query_get_count);
                $statement->execute();
                $count = $statement->rowCount();
                if($count > 0){
                    $query_get_count = "UPDATE top_messages SET repeat_count=repeat_count+1 WHERE (from_id='".$_SESSION['user_id']."' AND to_id='".$_POST['to_user_id']."') AND msg='$content'";
                    $statement = $connect->prepare($query_get_count);
                    $statement->execute();
                }
                else{
                    $query_get_count = "INSERT INTO top_messages(from_id, to_id, msg, repeat_count) VALUES('".$_SESSION['user_id']."', '".$_POST['to_user_id']."', '$content', 1)";
                    $statement = $connect->prepare($query_get_count);
                    $statement->execute();
                }

                $query = "UPDATE users SET to_refresh='1' WHERE id = ".$_POST['to_user_id']."";
                $statement = $connect->prepare($query);
                $statement->execute();
    
                $query_get = "SELECT * FROM chats WHERE (to_user_id=".$_POST['to_user_id']." AND from_user_id=".$_SESSION['user_id'].") OR (to_user_id=".$_SESSION['user_id']." AND from_user_id=".$_POST['to_user_id'].")";
                $statement = $connect->prepare($query_get);
                $statement->execute();

                $result = $statement->fetchAll();
                foreach($result as $row){
                    $from_id=$row['from_user_id'];
                    $to_id=$row['to_user_id'];
                }
    
                if (($from_id=$_SESSION['user_id'] && $to_id=$_POST['to_user_id']) || ($to_id=$_POST['to_user_id'] && $from_id=$_SESSION['user_id'])) {
                        
                    $query = "UPDATE chats SET last_msg='$content', timestamp='$time', status='1', notification_status='1' WHERE (to_user_id=".$_POST['to_user_id']." AND from_user_id=".$_SESSION['user_id'].") OR (to_user_id=".$_SESSION['user_id']." AND from_user_id=".$_POST['to_user_id'].")";
                    $statement = $connect->prepare($query);
                    $statement->execute();
    
                }
            }
        }


        //fetch_user_messages
        if($_POST["action"] == "fetch_user_messages"){
            $user_status= '';
            $query = "SELECT * FROM chats WHERE (from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."') OR (from_user_id = '".$_SESSION['user_id']."' AND to_user_id = '".$_POST['user_id']."')";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            foreach($result as $row)
            {
                $user_status = $row['user_status'];
            }

            if($user_status == 0){
                $query = "SELECT * FROM messages WHERE (from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."') OR (from_user_id = '".$_SESSION['user_id']."' AND to_user_id = '".$_POST['user_id']."') ORDER BY timestamp ASC";
                $statement = $connect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll();
                $output = '';
                if($statement->rowCount() > 0)
                {
                    foreach($result as $row)
                    {
                        $message = '';
                        $seen = '';
                        $dynamic_background = '';
                        $chat_message = '';
                        $time = time_diff_activity($row["timestamp"]);
                        if($row["from_user_id"] == $_SESSION['user_id']){
                            $message = '
                            <div class="row">
                                <div class="col">
                                    <div class="my_message border-0 text-right float-right text-white">
                                        '.$row["chat_message"].'
                                    </div>
                                    <div class="float-right text-center text-dark mr-1" style="font-size: 11px;">
                                        '.date('d M, Y', strtotime($row['timestamp'])).', '.date('H:i A', strtotime($row['timestamp'])).'
                                    </div>
                                </div>
                                <div style="float: right;">
                                    <img src="profile_images/'.Get_user_image($connect, $_SESSION['user_id']).'" class="rounded-circle" height="35px" width="35px" style="float: left; margin-left: 5px; margin-top: 5px;" />
                                </div>
                            </div>
                            ';
                        }
                        else{
                            $message = '
                            <div class="row">
                                <div style="float: left;">
                                    <img src="profile_images/'.Get_user_image($connect, $row["from_user_id"]).'" height="35px" width="35px" style="float: left; margin-right: 5px; margin-top: 5px;" class="rounded-circle" />
                                </div>
                                <div class="col">
                                    <div class="user_message border-0 text-left float-left text-dark">
                                        '.$row["chat_message"].'
                                    </div>
                                    <div class="float-left text-dark text-center ml-1" style="font-size: 11px;">
                                        '.date('d M, Y', strtotime($row['timestamp'])).', '.date('H:i A', strtotime($row['timestamp'])).'
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                        $output .= ''.$message.'<br>';
                    }
                }
                else{
                    $output = '<div style="text-align: center; margin-top: 150px;"><h5>No Message Found</h5></div>';
                }
                $query = "UPDATE messages SET status = '0' WHERE from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."' AND status = '1'";
                $statement = $connect->prepare($query);
                $statement->execute();

                $query1 = "UPDATE messages SET notification_status = '0' WHERE from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."' AND notification_status = '1'";
                $statement = $connect->prepare($query1);
                $statement->execute();

                $queryr = "UPDATE users SET to_refresh='0' WHERE to_refresh='1' AND id=".$_SESSION['user_id']."";	
                $statement = $connect->prepare($queryr);
                $statement->execute();

                // $query_delete = "UPDATE users SET to_refresh_delete='0' WHERE to_refresh_delete='1' AND user_id=".$_SESSION['user_id']."";	
                // $statement = $connect->prepare($query_delete);
                // $statement->execute();

                echo $output;
            }

            else{
                echo '<div class="text-center text-danger" style="margin-top: 100px;">Message Sharing Blocked</div>';
            }
            
            
        }

        if($_POST["action"] == "update_profile"){
            $data = array(
                 ':f_name'  		=> $_POST['f_name'],
                 ':l_name'  	    => $_POST['l_name'],
                 ':profile_image'  	=> $_POST['image_name']
            );
    
            $query = "UPDATE users SET first_name=:f_name, last_name=:l_name,profile_image=:profile_image WHERE id='".$_SESSION['user_id']."'";
    
            $statement = $connect->prepare($query);
            $statement->execute($data);
        }

        if($_POST["action"] == "fetch_user_head_status"){
            $query = "SELECT status FROM users WHERE id='".$_POST['user_id']."'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $output = '';
            foreach($result as $row)
            {
                $status = $row['status'];
            }

            $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
            $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);

            $user_last_activity = fetch_user_last_activity($_POST['user_id'], $connect);
            
            if($row['status'] == "active"){
                if($user_last_activity > $current_timestamp){
                    $output = '<i class="fa fa-circle" style="color: #14BD05;"></i> Active Now';
                }
                else{
                    $output = '<i class="fa fa-circle" style="color: black;"></i> Active '.time_diff_messenger_activity($user_last_activity).'';
                }
            }
            else{
                if($row['status']=="busy"){
                    $output = '<i class="fa fa-circle" style="color: #FFBF00;"></i> Busy';
                }
                else if($row['status']=="away"){
                    $output = '<i class="fa fa-circle" style="color: red;"></i> Away';
                }
            }

            echo $output;
        }
        if($_POST["action"] == "create_new_group"){
            $uniqid = uniqid().time();
            $query = "INSERT INTO chat_group(group_name, date_created, creator_id, group_uniqid) VALUES('".$_POST['group_name']."', now(),'".$_SESSION["user_id"]."', '$uniqid')";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $query_get = "SELECT * FROM chat_group WHERE group_uniqid = '$uniqid'";
                $statement = $connect->prepare($query_get);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                {
                    $group_id = $row['group_id'];
                }

                $query = "INSERT INTO group_member(group_id, user_id) VALUES($group_id, '".$_SESSION['user_id']."')";
                $statement = $connect->prepare($query);
                $statement->execute();
            }
        }

        if($_POST["action"] == "fetch_all_chatgroups"){
            $query = "SELECT * FROM chat_group INNER JOIN group_member ON group_member.group_id=chat_group.group_id WHERE group_member.user_id = '".$_SESSION['user_id']."' ORDER BY chat_group.group_name ASC";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $output = '';
            if($statement->rowCount() > 0){
                foreach($result as $row)
                {
                    $output = '
                        <a style="text-decoration: none;" href="groupchat.php?group_id='.$row['group_uniqid'].'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="images/groupchaticon.png" height="30px" width="30px" class="rounded-circle" style="float: left;">
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-top: 3px; margin-left: 10px; font-weight: 550;">'.$row['group_name'].'</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin: 0px 80px;"></div>
                    ';
                    
                    echo $output;
                }
            }
            else{
                echo '<div class="text-center mt-3">No Groups Found</div>';
            }
        }

        if($_POST["action"] == "fetch_all_chatgroups_m"){
            $query = "SELECT * FROM chat_group INNER JOIN group_member ON group_member.group_id=chat_group.group_id WHERE group_member.user_id = '".$_SESSION['user_id']."' ORDER BY chat_group.group_name ASC";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $output = '';
            if($statement->rowCount() > 0){
                foreach($result as $row)
                {
                    $output = '
                        <a style="text-decoration: none;" href="user_groupchat.php?group_id='.$row['group_uniqid'].'">
                            <div class="row chat_list" style="padding: 0px 10px 0px 25px; cursor: pointer; border-bottom: 2px solid white;">
                                <div class="col" style="padding: 6px;">
                                    <img src="images/groupchaticon.png" height="30px" width="30px" class="rounded-circle" style="float: left;">
                                    <div class="users_name" style="">
                                        <div style="float: left; margin-top: 3px; margin-left: 10px; font-weight: 550;">'.$row['group_name'].'</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider" style="margin: 0px 80px;"></div>
                    ';
                    
                    echo $output;
                }
            }
            else{
                echo '<div class="text-center mt-3">No Groups Found</div>';
            }
        }

        if($_POST["action"] == "send_group_message"){
            $content = ''.$_POST['message_text'].'';

            $image_name = $_POST['image_name'];
            $file_ext = $_POST['file_ext'];

            if($file_ext == 'jpg' || $file_ext == 'png' || $file_ext == 'gif' || $file_ext == 'jpeg' || $file_ext == 'JPG' || $file_ext == 'PNG' || $file_ext == 'GIF' || $file_ext == 'JPEG'){
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><img src="uploads/'.$_POST['image_name'].'" data-toggle="modal" data-target="#view_full_size_image" id="preview_image" data-img_name="'.$_POST['image_name'].'" class="img-fluid" height="150px" />';
                }
                else if($content == '' && $_POST['image_name'] != ''){
                    $msg_text = '<img src="uploads/'.$_POST['image_name'].'" data-toggle="modal" data-target="#view_full_size_image" id="preview_image" data-img_name="'.$_POST['image_name'].'" class="img-fluid" height="150px" />';

                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
                
            }
            else if($file_ext == 'mp4' || $file_ext == 'mkv' || $file_ext == 'MP4' || $file_ext == 'MKV'){
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><div class="embed-responsive embed-responsive-16by9" style="height: 150px; width: 250px;"><video class="embed-responsive-item" controls="controls" src="uploads/'.$_POST['image_name'].'"></video></div>';
                }
                else if($content == '' && $_POST['image_name'] != '') {
                    $msg_text = '<div class="embed-responsive embed-responsive-16by9" style="height: 150px; width: 250px;"><video class="embed-responsive-item" controls="controls" src="uploads/'.$_POST['image_name'].'"></video></div>';

                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
                
            }
            else{
                if($content != '' && $_POST['image_name'] != ''){
                    $msg_text = '<div style="margin-bottom: 5px;">'.$content.'</div><a href="javascript:void(0)" id="download_file" data-file_name="'.$_POST['image_name'].'" style="background: white; padding: 5px; border-radius: 3px; color: black; text-decoration: none;"><i>'.$_POST['image_name'].'</i></a>';
                }
                else if($content == '' && $_POST['image_name'] != ''){
                    $msg_text = '<a href="javascript:void(0)" id="download_file" data-file_name="'.$_POST['image_name'].'" style="background: white; padding: 5px; border-radius: 3px; color: black; text-decoration: none;"><i>'.$_POST['image_name'].'</i></a>';
                }
                else if($content != '' && $_POST['image_name'] == ''){
                    $msg_text = ''.$content.'';
                }
            }

            $query = "INSERT INTO group_messages(group_id, message, message_date, msg_from_id, msg_status) VALUES('".$_POST['group_id']."', '".$msg_text."', '$time','".$_SESSION["user_id"]."', 1)";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $query_get_count = "SELECT * FROM top_messages_group WHERE (from_id='".$_SESSION['user_id']."' AND group_id='".$_POST['group_id']."') AND msg='".$_POST['message_text']."'";
                $statement = $connect->prepare($query_get_count);
                $statement->execute();
                $count = $statement->rowCount();
                if($count > 0){
                    $query_get_count = "UPDATE top_messages_group SET repeat_count=repeat_count+1 WHERE (from_id='".$_SESSION['user_id']."' AND group_id='".$_POST['group_id']."') AND msg='".$_POST['message_text']."'";
                    $statement = $connect->prepare($query_get_count);
                    $statement->execute();
                }
                else{
                    $query_get_count = "INSERT INTO top_messages_group(from_id, group_id, msg, repeat_count) VALUES('".$_SESSION['user_id']."', '".$_POST['group_id']."', '".$_POST['message_text']."', 1)";
                    $statement = $connect->prepare($query_get_count);
                    $statement->execute();
                }

                $queryr = "UPDATE chat_group SET refresh='1' WHERE group_id=".$_POST['group_id']."";
                $statement = $connect->prepare($queryr);
                $statement->execute();
            }
        }


        //fetch_user_messages
        if($_POST["action"] == "fetch_group_messages"){
            
            $query = "SELECT * FROM group_messages WHERE group_id = '".$_POST['group_id']."' ORDER BY message_date ASC";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $output = '';
            if($statement->rowCount() > 0)
            {
                foreach($result as $row)
                {
                    
                    $message = '';
                    $seen = '';
                    $dynamic_background = '';
                    $chat_message = '';

                    if($row["msg_from_id"] == $_SESSION['user_id']){
                        $message = '
                        <div class="row">
                            <div class="col">
                                <div class="my_message border-0 text-right float-right text-white">
                                    '.$row["message"].'
                                </div>
                                <div class="float-right text-center text-dark mr-1" style="font-size: 11px;">
                                    '.date('d M, Y', strtotime($row['message_date'])).', '.date('H:i A', strtotime($row['message_date'])).'
                                </div>
                            </div>
                            <div style="float: right;">
                                <img src="profile_images/'.Get_user_image($connect, $_SESSION['user_id']).'" class="rounded-circle" height="35px" width="35px" style="float: left; margin-left: 5px; margin-top: 5px;" />
                            </div>
                        </div>
                        ';
                    }
                    else{
                        $message = '
                        <div class="row">
                            <div style="float: left;">
                                <img src="profile_images/'.Get_user_image($connect, $row["msg_from_id"]).'" height="35px" width="35px" style="float: left; margin-right: 5px; margin-top: 5px;" class="rounded-circle" />
                            </div>
                            <div class="col">
                                <div class="user_message border-0 text-left float-left text-dark">
                                    '.$row["message"].'
                                </div>
                                <div class="float-left text-center text-dark ml-1" style="font-size: 11px;">
                                    '.date('d M, Y', strtotime($row['message_date'])).'
                                    <br>
                                    at '.date('H:i A', strtotime($row['message_date'])).'
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    $output .= ''.$message.'<br>';
                }
            }
            else{
                $output = '<div style="text-align: center; margin-top: 150px;"><h5>No Message Found</h5></div>';
            }

            
            // $query = "UPDATE messages SET status = '0' WHERE from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."' AND status = '1'";
            // $statement = $connect->prepare($query);
            // $statement->execute();

            // $query1 = "UPDATE messages SET notification_status = '0' WHERE from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."' AND notification_status = '1'";
            // $statement = $connect->prepare($query1);
            // $statement->execute();

            // $queryr = "UPDATE users SET to_refresh='0' WHERE to_refresh='1' AND id=".$_SESSION['user_id']."";	
            // $statement = $connect->prepare($queryr);
            // $statement->execute();

            // $query_delete = "UPDATE users SET to_refresh_delete='0' WHERE to_refresh_delete='1' AND user_id=".$_SESSION['user_id']."";	
            // $statement = $connect->prepare($query_delete);
            // $statement->execute();

            echo $output;
        }

        if($_POST["action"] == "add_member_to_group"){
            sleep(1);
            $output = '';

            $query_get = "SELECT * FROM group_member WHERE group_id = '".$_POST['group_id']."' AND user_id = '".$_POST['user_id']."'";
            $statement = $connect->prepare($query_get);
            $statement->execute();
            if($statement->rowCount() > 0){
                $output = '<div class="alert alert-danger" role="alert" style="font-size: 13px;">Already a member of this group</div>';
            }
            else{
                $query = "INSERT INTO group_member(group_id, user_id) VALUES('".$_POST['group_id']."', '".$_POST['user_id']."')";
                $statement = $connect->prepare($query);
                if($statement->execute()){
                    $output = '<div class="alert alert-success" role="alert" style="font-size: 13px;">Member Added</div>';
                }

            }

            echo $output;
            
        }

        if($_POST["action"] == "remove_member_from_group"){
            sleep(1);
            $output = '';

            $query = "DELETE FROM group_member WHERE group_id = '".$_POST['group_id']."' AND user_id = '".$_POST['user_id']."'";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $output = '<div class="alert alert-success" role="alert" style="font-size: 13px;">Member Removed</div>';
            }

            echo $output;
            
        }

        if($_POST["action"] == "leave_from_group"){
            $output = '';

            $query = "DELETE FROM group_member WHERE group_id = '".$_POST['group_id']."' AND user_id = '".$_SESSION['user_id']."'";
            $statement = $connect->prepare($query);
            $statement->execute();
            
        }

        if($_POST["action"] == "change_group_name"){
            sleep(1);
            $output = '';

            $query = "UPDATE chat_group SET group_name='".$_POST['group_name']."' WHERE group_id = '".$_POST['group_id']."'";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $output = '<div class="alert alert-success text-center" role="alert" style="font-size: 13px;">Name Updated Successfully</div>';
            }

            echo $output;
            
        }

        if($_POST["action"] == "load_group_member_count"){
            $output = '';

            $query = "SELECT * FROM group_member WHERE group_id = '".$_POST['group_id']."'";
            $statement = $connect->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();

            if($count > 0){
                $output = ''.$count.' Members';
            }
            else{
                $output = '0 Members';
            }

            echo $output;
            
        }


        if($_POST["action"] == "block_user"){
            $output = '';

            $query = "UPDATE chats SET user_status = '1' WHERE (from_user_id = '".$_SESSION['user_id']."' AND to_user_id = '".$_POST['user_id']."') OR (from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."')";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $query = "UPDATE users SET to_refresh='1' WHERE id = ".$_POST['user_id']."";
                $statement = $connect->prepare($query);
                $statement->execute();
            }
        }

        if($_POST['action'] == 'fetch_blocked_users'){
			

            $query = "SELECT users.id, users.first_name, users.last_name, users.status, users.uniqid, users.profile_image, chats.from_user_id, chats.user_status, chats.to_user_id, chats.last_msg, chats.timestamp FROM users INNER JOIN chats ON chats.from_user_id = users.id OR chats.to_user_id = users.id WHERE ((chats.from_user_id = '".$_SESSION["user_id"]."' OR chats.to_user_id = '".$_SESSION["user_id"]."') AND chats.user_status = '1') AND users.id != '".$_SESSION["user_id"]."' ORDER BY chats.timestamp DESC";
            $statement = $connect->prepare($query);
            $statement->execute();

            $html = '';

            if($statement->rowCount() > 0)
            {
                $count = 0;

                foreach($statement->fetchAll() as $row)
                {
                    $temp_user_id = $row["id"];

                    $status = '';
                    $button = '';
                    $status_text = '';
                    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
                    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);

                    $user_last_activity = fetch_user_last_activity($temp_user_id, $connect);
                    
                    if($row['status'] == "active"){
                        if($user_last_activity > $current_timestamp){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: #14BD05; font-size: 10px;"></i>';
                            $status_text = 'Online';
                        }
                        else{
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Active '.time_diff_messenger_activity($user_last_activity).'';
                        }
                    }
                    else{
                        if($row['status']=="busy"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: #FFBF00; font-size: 10px;"></i>';
                            $status_text = 'Busy';
                        }
                        else if($row['status']=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: red; font-size: 10px;"></i>';
                            $status_text = 'Away';
                        }
                    }

                    $html .= '
                        <div class="row" style="padding: 0px 20px;">
                            <div class="col" style="padding: 5px 10px;">
                                <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" style="float: left;" class="rounded-circle" />
                                <div class="users_name" style="margin-top: 10px 5px;">
                                    <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    <div style="float: right; margin-right: 15px;">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary unblock_user" id="unblock_'.$row['id'].'" data-user_id="'.$row['id'].'" style="padding: 1px 10px; margin-top: 3px;"><i class="fa fa-times"></i> Unblock</a>
                                    </div>
                                </div>
                                <br>
                                <div class="last_msg" style="font-size: 12px;">
                                    <span style="margin-left: 10px;"><span>'.$status_text.'</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider" style="margin-left: 55px; margin-right: 80px;"></div>
                    ';

                }
            }
            else
            {
                $html = '<h6 align="center" style="color: red; margin-top: 30px;">No User Found</h6>';
            }
            echo $html;
		}

        if($_POST["action"] == "unblock_user"){
            $output = '';

            $query = "UPDATE chats SET user_status = '0' WHERE ((from_user_id = '".$_SESSION['user_id']."' AND to_user_id = '".$_POST['user_id']."') OR (from_user_id = '".$_POST['user_id']."' AND to_user_id = '".$_SESSION['user_id']."')) AND user_status = '1'";
            $statement = $connect->prepare($query);
            if($statement->execute()){
                $query = "UPDATE users SET to_refresh='1' WHERE id = ".$_POST['user_id']."";
                $statement = $connect->prepare($query);
                $statement->execute();

                $output = '<div class="alert alert-success" role="alert" style="font-size: 13px;">Unblocked</div>';
            }

            echo $output;
        }

        if($_POST["action"] == "get_group_refresh"){
            $output = '';

            $query = "SELECT * FROM chat_group WHERE group_id = ".$_POST['group_id']."";	
            $statement = $connect->prepare($query);
            $statement->execute();

            $result = $statement->fetchAll();

            foreach($result as $row)
            {
                $output = '<input type="text" value="'.$row['refresh'].'" id="group_refresh_val" />';
            }

            echo $output;
            
        }

        if($_POST["action"] == "reset_group_refresh"){
            $queryr = "UPDATE chat_group SET refresh='0' WHERE refresh='1' AND group_id=".$_POST['group_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }

        if($_POST["action"] == "pin_chat"){
            $queryr = "UPDATE chats SET pinned='1' WHERE chat_id=".$_POST['chat_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }

        if($_POST["action"] == "unpin_chat"){
            $queryr = "UPDATE chats SET pinned='0' WHERE pinned='1' AND chat_id=".$_POST['chat_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }
        
        if($_POST["action"] == "fetch_my_status"){
            $output = '';
            $queryr = "SELECT * FROM users WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute(); 
            $result = $statement->fetchAll();

            foreach($result as $row)
            {
                if($row['status'] == 'active'){
                    $output = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="color: #14BD05; font-size: 10px;"></i> Active';
                }
                else if($row['status'] == 'busy'){
                    $output = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="color: #FFBF00; font-size: 10px;"></i> Busy';
                }
                if($row['status'] == 'away'){
                    $output = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="color: red; font-size: 10px;"></i> Away';
                }
            } 

            echo $output;
        }

        if($_POST["action"] == "change_st_to_active"){
            $queryr = "UPDATE users SET status='active' WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }
        if($_POST["action"] == "change_st_to_busy"){
            $queryr = "UPDATE users SET status='busy' WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }
        if($_POST["action"] == "change_st_to_away"){
            $queryr = "UPDATE users SET status='away' WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }

        if($_POST["action"] == "get_old_messages"){
            $output = '';
            $queryr = "SELECT * FROM messages WHERE (from_user_id=".$_SESSION['user_id']." AND to_user_id=".$_POST['to_user_id'].") AND chat_message LIKE '%".$_POST['value']."%'";
            $statement = $connect->prepare($queryr);
            $statement->execute(); 
            $result = $statement->fetchAll();
            if($statement->rowCount() > 0){
                foreach($result as $row)
                {
                    $output = '
                    <a href="javascript:void(0)" id="add_old_msg" style="text-decoration: none; color: black;" data-msg="'.$row['chat_message'].'">
                        <div style="background: white; padding: 10px;">
                            <div class="row">
                                <div class="col-8" style="text-align: left; font-size: 14px;">
                                    '.$row['chat_message'].'
                                </div>
                                <div class="col-4" style="text-align: left; font-size: 12px;">
                                    '.date('d M, Y', strtotime($row['timestamp'])).'
                                    <br>
                                    at '.date('H:i A', strtotime($row['timestamp'])).'
                                </div>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    ';


                    echo $output;
                }
            }
            else{
                echo '
                <div style="background: white; padding: 10px;">
                    <div class="row">
                        <div class="col" style="text-align: center; font-size: 14px;">
                            No Such Messages
                        </div>
                    </div>
                </div>
                ';
            }
             

            
        }

        if($_POST["action"] == "get_old_groupmessages"){
            $output = '';
            $queryr = "SELECT * FROM group_messages WHERE (msg_from_id=".$_SESSION['user_id']." AND group_id=".$_POST['group_id'].") AND message LIKE '%".$_POST['value']."%'";
            $statement = $connect->prepare($queryr);
            $statement->execute(); 
            $result = $statement->fetchAll();
            if($statement->rowCount() > 0){
                foreach($result as $row)
                {
                    $output = '
                    <a href="javascript:void(0)" id="add_old_msg_group" style="text-decoration: none; color: black;" data-msg="'.$row['message'].'">
                        <div style="background: white; padding: 10px;">
                            <div class="row">
                                <div class="col-8" style="text-align: left; font-size: 14px;">
                                    '.$row['message'].'
                                </div>
                                <div class="col-4" style="text-align: left; font-size: 12px;">
                                    '.date('d M, Y', strtotime($row['message_date'])).'
                                    <br>
                                    at '.date('H:i A', strtotime($row['message_date'])).'
                                </div>
                            </div>
                        </div>
                    </a>
                        <div class="dropdown-divider"></div>
                    ';


                    echo $output;
                } 
            }

            else{
                echo '
                <div style="background: white; padding: 10px;">
                    <div class="row">
                        <div class="col" style="text-align: center; font-size: 14px;">
                            No Such Messages
                        </div>
                    </div>
                </div>
                ';
            }
            
        }

        if($_POST["action"] == "get_top3_messages"){
            $output = '';
            $queryr = "SELECT * FROM top_messages WHERE (from_id=".$_SESSION['user_id']." AND to_id=".$_POST['to_user_id'].") ORDER BY repeat_count DESC LIMIT 3";
            $statement = $connect->prepare($queryr);
            $statement->execute(); 
            $result = $statement->fetchAll();
            if($statement->rowCount() > 0){
                $output .= '<div class="row text-center" style="padding-left: 30px; padding-right: 65px;">';
                foreach($result as $row)
                {
                    $output .= '
                    <div class="col-4 m-0 p-1" style="width: auto; cursor: pointer;" id="add_old_msg" data-msg="'.$row['msg'].'">
                        <div style="text-align: center; background: white; padding: 3px; font-size: 13px;">
                            '.$row['msg'].'
                        </div>
                    </div>
                    ';


                    
                } 
                $output .= '</div>';
                
                echo $output;
            }
            
        }


        if($_POST["action"] == "get_top3_messages_group"){
            $output = '';
            $queryr = "SELECT * FROM top_messages_group WHERE (from_id=".$_SESSION['user_id']." AND group_id=".$_POST['group_id'].") ORDER BY repeat_count DESC LIMIT 3";
            $statement = $connect->prepare($queryr);
            $statement->execute();
            $result = $statement->fetchAll();
            if($statement->rowCount() > 0){
                $output .= '<div class="row text-center" style="padding-left: 30px; padding-right: 65px;">';
                foreach($result as $row)
                {
                    $output .= '
                    <div class="col-4 m-0 p-1" style="width: auto; cursor: pointer;" id="add_old_msg_group" data-msg="'.$row['msg'].'">
                        <div style="text-align: center; background: white; padding: 3px; font-size: 13px;">
                            '.$row['msg'].'
                        </div>
                    </div>
                    ';


                    
                } 
                $output .= '</div>';
                
                echo $output;
            }
            
        }

        if($_POST["action"] == "view_image_full"){
            
            $output = '
                <img src="uploads/'.$_POST['name'].'" class="img-fluid" />
            ';
            echo $output;
            
        }

        if($_POST["action"] == "fetch_sound_status"){
            $output = '';
            $queryr = "SELECT * FROM users WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute(); 
            $result = $statement->fetchAll();

            foreach($result as $row)
            {
                if($row['sound_status'] == '0'){
                    $output = 'Off';
                }
                else{
                    $output = 'On';
                }
            } 

            echo $output;
        }

        if($_POST["action"] == "change_as_off"){
            $queryr = "UPDATE users SET sound_status='0' WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }

        if($_POST["action"] == "change_as_on"){
            $queryr = "UPDATE users SET sound_status='1' WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($queryr);
            $statement->execute();  
        }

	}
?>