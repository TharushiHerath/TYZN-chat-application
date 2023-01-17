<?php  
	include('database_connection.php');
	include('time_difference.php');
	session_start();
    
	$time = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

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

            $query = "SELECT * FROM users WHERE id != ".$_SESSION['user_id']." ".$condition." ORDER BY first_name ASC";
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
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Busy';
                        }
                        else if($row['status']=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Away';
                        }
                    }
                    
                    

                    $html .= '
                    <a style="text-decoration: none;" id="start_chat" data-id="'.$row['id'].'" href="messages.php?user='.$row['uniqid'].'">
                        <div class="row chat_list" style="padding: 0px 20px; cursor: pointer;">
                            <div class="col" style="padding: 5px 10px;">
                                <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" style="float: left;" class="rounded-circle" />
                                <div class="users_name" style="margin-top: 10px 5px;">
                                    <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    <div style="float: right; margin-right: 15px;">'.$status.'</div>
                                </div>
                                <br>
                                <div class="last_msg" style="font-size: 12px;">
                                    <span style="margin-left: 10px;"><span>'.$status_text.'</span></span>
                                </div>
                            </div>
                        </div>
                    </a>
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

        if($_POST['action'] == 'fetch_all_m_users'){
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

            $query = "SELECT * FROM users WHERE id != ".$_SESSION['user_id']." ".$condition." ORDER BY first_name ASC";
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
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Busy';
                        }
                        else if($row['status']=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Away';
                        }
                    }
                    
                    

                    $html .= '
                    <a style="text-decoration: none;" id="start_chat" data-id="'.$row['id'].'" href="user_messages.php?user='.$row['uniqid'].'">
                        <div class="row chat_list" style="padding: 0px 20px; cursor: pointer;">
                            <div class="col" style="padding: 5px 10px;">
                                <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" style="float: left;" class="rounded-circle" />
                                <div class="users_name" style="margin-top: 10px 5px;">
                                    <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    <div style="float: right; margin-right: 15px;">'.$status.'</div>
                                </div>
                                <br>
                                <div class="last_msg" style="font-size: 12px;">
                                    <span style="margin-left: 10px;"><span>'.$status_text.'</span></span>
                                </div>
                            </div>
                        </div>
                    </a>
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

        if($_POST['action'] == 'start_new_chat'){
            sleep(1);
            
            $query_status = "SELECT * FROM chats WHERE (from_user_id = ".$_POST['user_id']." AND to_user_id = ".$_SESSION['user_id'].") OR (from_user_id = ".$_SESSION['user_id']." AND to_user_id = ".$_POST['user_id'].")";
            $statement = $connect->prepare($query_status);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                
            }
            else{
                $query_add = "INSERT INTO chats(from_user_id, to_user_id, last_msg, timestamp) VALUES(".$_SESSION['user_id'].", ".$_POST['user_id'].", 'Say Hi', '$time')";
                $statement = $connect->prepare($query_add);
                $statement->execute();
            }
        }

        if($_POST['action'] == 'fetch_all_users_for_group'){
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

            $query = "SELECT * FROM users WHERE id != ".$_SESSION['user_id']." ".$condition." ORDER BY first_name ASC";
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
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Busy';
                        }
                        else if($row['status']=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Away';
                        }
                    }

                    $query_get = "SELECT * FROM group_member WHERE group_id = '".$_POST['group_id']."' AND user_id = '".$row['id']."'";
                    $statement = $connect->prepare($query_get);
                    $statement->execute();
                    if($statement->rowCount() > 0){
                        $button = '<a href="javascript:void(0)" class="btn btn-sm btn-success disabled" style="padding: 1px 10px; margin-top: 3px;"><i class="fa fa-check"></i> Added</a>';
                    }
                    else{
                        $button = '<a href="javascript:void(0)" class="btn btn-sm btn-primary add_member_to_group" style="padding: 1px 10px; margin-top: 3px;" id="add_to_group_'.$row['id'].'" data-user_id="'.$row['id'].'"><i class="fa fa-plus"></i> Add</a>';
                    }
                    
                    

                    $html .= '
                        <div class="row" style="padding: 0px 20px;">
                            <div class="col" style="padding: 5px 10px;">
                                <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" style="float: left;" class="rounded-circle" />
                                <div class="users_name" style="margin-top: 10px 5px;">
                                    <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    <div style="float: right; margin-right: 15px;">
                                        '.$button.'
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


        if($_POST['action'] == 'load_group_member'){
			

            $query = "SELECT * FROM users INNER JOIN group_member ON group_member.user_id=users.id WHERE group_member.group_id=".$_POST['group_id']." ORDER BY first_name ASC";
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
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Busy';
                        }
                        else if($row['status']=="away"){
                            $status = '<i class="fa fa-circle rounded-circle" aria-hidden="true"  style="float: left; margin-top: 13px; color: black; font-size: 10px;"></i>';
                            $status_text = 'Away';
                        }
                    }

                    $query_get = "SELECT * FROM group_member WHERE group_id = '".$_POST['group_id']."' AND user_id = '".$row['id']."'";
                    $statement = $connect->prepare($query_get);
                    $statement->execute();
                    if($statement->rowCount() > 0){
                        $button = '<a href="javascript:void(0)" class="btn btn-sm btn-danger remove_member" id="remove_from_group_'.$row['id'].'" data-user_id="'.$row['id'].'" style="padding: 1px 10px; margin-top: 3px;"><i class="fa fa-times"></i> Remove</a>';
                    }
                    if($row['id'] == $_SESSION['user_id']){
                        $button = '<a href="javascript:void(0)" class="btn btn-sm btn-danger" id="leave_group" style="padding: 1px 10px; margin-top: 3px;"><i class="fa fa-sign-out"></i> Leave</a>';
                    }
                    
                    

                    $html .= '
                        <div class="row" style="padding: 0px 20px;">
                            <div class="col" style="padding: 5px 10px;">
                                <img src="profile_images/'.$row['profile_image'].'" height="40px" width="40px" style="float: left;" class="rounded-circle" />
                                <div class="users_name" style="margin-top: 10px 5px;">
                                    <div style="float: left; margin-left: 10px; font-weight: 550;">'.$row['first_name'].' '.$row['last_name'].'</div>
                                    <div style="float: right; margin-right: 15px;">
                                        '.$button.'
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

        if($_POST['action'] == 'change_password'){
			
            $html = '';

            $query = "SELECT * FROM users WHERE id=".$_SESSION['user_id']."";
            $statement = $connect->prepare($query);
            $statement->execute();

            foreach($statement->fetchAll() as $row)
            {
                if(password_verify($_POST['old_pass'], $row['password']))
				{
                    $password =	password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
                    
                    $query = "UPDATE users SET password = '$password' WHERE id=".$_SESSION['user_id']."";
                    $statement = $connect->prepare($query);
                    if($statement->execute()){
                        $html = '<div class="alert alert-success" role="alert" style="font-size: 13px;">Password Changed Successfully</div>';
                    }
                }
                else{
                    $html = '<div class="alert alert-danger" role="alert" style="font-size: 13px;">Old Password Incorrect</div>';
                }
            }
            echo $html;
		}

	}
?>