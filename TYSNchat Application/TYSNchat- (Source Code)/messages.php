<?php
    include('backend/database_connection.php');

    session_start();

    if(!isset($_SESSION['user_id']))
    {
        header('location: index.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TYSNchat Application</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
</head>
<style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css");
    body{
        overflow-x: hidden;
        font-family: Arial, Helvetica, sans-serif;
        background: #046A70;
    }
    .fa-bars{
        color: black;
        font-size: 22px;
        margin-top: 10px;
    }
    .has-search .form-control {
        padding-left: 2.375rem;
        border-radius: 25px;
        border: none;
    }

    .has-search .form-control:focus {
        box-shadow: none;
    }

    .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
    }
    .chat_list{
        background: white;
        border-radius: 7px;
    }
    .chat_list:hover{
        border-radius: 7px;
        background: #74B9FF;
        transition-duration: .3s;
    }

    .users_name{
        color: black;
    }
    .last_msg{
        color: grey; 
    }
    .chat_list:hover .users_name{
        color: white;
    }
    .chat_list:hover .last_msg{
        color: white; 
    }
    .send_box{
        width: auto;
        font-size: 14px;
        border-color: white;
        background: white;
    }
    .send_box:focus{
        box-shadow: none;
        border-color: white;
    }
    .my_message{
        background: #74B9FF;
        width: auto;
        max-width: 45%;
        padding: 10px;
        border-radius: 5px;
    }
    .my_message:after {
        content:'';
        position: absolute;
        top: 0;
        left: 100%;
        margin-left: -15px;
        width: 0;
        height: 0;
        border-bottom: solid 15px #74B9FF;
        border-top: solid 15px transparent;
        border-right: solid 15px transparent;
    }

    .user_message{
        background: #EEEEEE;
        width: auto;
        max-width: 45%;
        padding: 10px;
        border-radius: 5px;
    }
    .user_message:after {
        content:'';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        border-bottom: solid 15px #EEEEEE;
        border-top: solid 15px transparent;
        border-left: solid 15px transparent;
    }
    .btn:focus {
        outline: none;
        box-shadow: none;
    }
    .modal-body input.form-control{
        height: 30px;
        font-size: 13px;
    }
    .modal-body input.form-control:focus{
        height: 30px;
        box-shadow: none;
        font-size: 13px;
        border-color: black;
    }

    .abcd input.form-control{
        height: 30px;
        margin-top: 5px;
        border: none;
        border-radius: 10px;
        text-align: center;
        font-size: 13px;
    }

    .abcd input.form-control:focus{
        height: 30px;
        box-shadow: none;
        margin-top: 5px;
        border: none;
        border-radius: 10px;
        text-align: center;
    }
    /* width */
    ::-webkit-scrollbar {
        width: 7px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: transparent;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #CDCDCD;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: grey;
    }

    #close_vid_btn{
        border: none; 
        background: rgba(255, 255, 255, 0.4); 
        padding: 10px 15px; 
        position: absolute;
        border: 2px solid white;
        cursor: pointer;
        top: 0;
        right: 0;
    }
    #close_vid_btn:hover{
        border: none; 
        background: rgba(255, 255, 255, 0.7); 
        padding: 10px 15px; 
        border: 2px solid white;
        position: absolute; 
        cursor: pointer;
        transition-duration: .3s;
        top: 0;
        right: 0;
    }
   
    
</style>
<body>
    <!--/////////////////////////////////////////////////////////////////////////////////Top heading-->
    <div class="container">
        <div class="row mt-1">
            <div class="col text-center text-white" style="height: 4vh;">
                <h6><strong>TYSNchat Application</strong></h6>
            </div>
        </div>
    </div>

    <div class="container d-block d-lg-none p-0 margin-0">
        <div class="row">
            <div class="col-md-12 col-lg-4">   <!--*********    LEFT Panel  (DP,Search,Recent/All Users,chat list,group chat,group list)     *********-->
                <?php 
                    $query = "SELECT * FROM users WHERE id = '".$_SESSION['user_id']."'";
                    $statement = $connect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach($result as $row)
                    {
                        $profile_image = $row['profile_image'];
                        $firstname = $row['first_name'];
                        $lastname = $row['last_name'];
                    }
                ?>
                <div class="card" style="height: 94vh; border-right: none;">
<!--DP [230-265] --><div class="card-header border-0" style="background: #EEEEEE; padding: 10px 20px;"> 
                    <div style="float: left;">
                            <a href="jaascript:void(0)" style="float: left;" data-toggle="modal" data-target="#myprofilemodal">
                                <img src="profile_images/<?php echo $profile_image; ?>" class="rounded-circle" height="45px" width="45px" alt="">
                            </a>
                            <div class="row">
                                <div class="ml-2 mt-0" style="font-size: 14px; font-weight: 600; width: 200px;"><?php echo $firstname; ?> <?php echo $lastname; ?></div>
                            </div>
                            <div class="row">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" style="text-decoration: none; color: black; width: 100px; font-size: 12px;" class="dropdown-toggle ml-2" id="dropdownMenustatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="show_my_status_m"></span></a>

                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenustatus" style="font-size: 13px;">
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_active">Active</a>
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_busy">Busy</a>
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_away">Away</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex" style="float: right;">
                            <a href="chatroom.php" class="btn btn-sm" title="Public Chat Room"><i class="fa fa-comments" style="font-size: 25px;"></i></a>
                            <div class="dropdown">
                                <a href="javascript:void(0)" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" style="text-decoration: none; font-size: 24px; font-weight: bold; color: grey;" aria-expanded="false">&#8942;</a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="font-size: 15px;">
                                    
                                    <a class="dropdown-item" data-toggle="modal" data-target="#blocked_users_modal" href="javascript:void(0)"><i class="fa fa-ban"></i> Blocked Users</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#settings" href="javascript:void(0)"><i class="fa fa-cog"></i> Setting</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-0 pt-2" style="background: #FAFAFA; padding: 0px;">
<!--Search bar-->       <form>
                        <div class="form-group has-search m_friends_search" style="padding: 0px 10px 0px 10px;">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control" id="m_search_friend" placeholder="Search" autocomplete="off" />
                        </div>
                        <div class="form-group has-search m_friends_search_pinned" style="display: none; padding: 0px 10px 0px 10px;">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control" id="m_search_friend_pinned" placeholder="Search" autocomplete="off" />
                        </div>
                        <div class="form-group has-search m_user_search" style="display: none; padding: 0px 10px 0px 10px;">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control" id="m_search_user" placeholder="Search" autocomplete="off" />
                        </div>
                        </form>
<!--Recent & All users--><div class="row" style="margin-top: -7px;">
                            <div class="col text-center">
                                <a href="javascript:void(0)" id="m_recent_msg" class="btn btn-sm btn-light" style="border-bottom: 1px solid grey; font-size: 13px; width: 45%;">Recent</a>
                                <a href="javascript:void(0)" id="m_all_users" class="btn btn-sm btn-light" style="border-bottom: 1px solid grey; font-size: 13px; width: 45%;">All Users</a>
                            </div>
                        </div>
                        
<!--User list-->        <div class="msg_list_scroll mt-1" style="overflow-x: hidden; overflow-y: scroll; height: 35vh;">
                            <!--<div id="m_all_chat_list_pinned"></div>-->
                            <div id="m_all_chat_list"></div>
                            <div id="m_all_users_list" style="display: none;"></div>
                        </div>
<!--Group chat bar-->   <div class="row">
                            <div class="col" style="background: white; height: 5vh; padding-top: 5px; margin: 0px 15px;">
                                <span class="font-weight-bold" style="font-size: 17px; float: left;">Group Chats</span>
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#create_group" class="btn btn-sm btn-secondary" style="float: right; padding: 0px 10px; font-size: 13px;"><i class="fa fa-plus"></i> Group</a>
                            </div>
                        </div>
<!--Group list-->       <div class="group_list_scroll mt-1" style="overflow-x: hidden; overflow-y: scroll; height: 35vh;">
                            <div id="m_group_chat_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--/////////////////////////////////////////////////////////////////////////////////-->
    <div class="container d-none d-lg-block">
        <div class="row">
            <div class="col-md-12 col-lg-4 pr-0 margin-0">
                <?php 
                    $query = "SELECT * FROM users WHERE id = '".$_SESSION['user_id']."'";
                    $statement = $connect->prepare($query);
                    $statement->execute();
                    $result = $statement->fetchAll();
                    foreach($result as $row)
                    {
                        $profile_image = $row['profile_image'];
                        $firstname = $row['first_name'];
                        $lastname = $row['last_name'];
                    }
                ?>
                <div class="card" style="height: 94vh; border-right: none;">
                    <div class="card-header border-0" style="background: #EEEEEE; padding: 10px 20px;">
                        <div style="float: left;">
                            <a href="jaascript:void(0)" style="float: left;" data-toggle="modal" data-target="#myprofilemodal">
                                <img src="profile_images/<?php echo $profile_image; ?>" class="rounded-circle" height="45px" width="45px" alt="">
                            </a>
                            <div class="row">
                                <div class="ml-2 mt-0" style="font-size: 14px; font-weight: 600; width: 200px;"><?php echo $firstname; ?> <?php echo $lastname; ?></div>
                            </div>
                            <div class="row">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" style="text-decoration: none; color: black; width: 100px; font-size: 12px;" class="dropdown-toggle ml-2" id="dropdownMenustatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="show_my_status"></span></a>

                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenustatus" style="font-size: 13px;">
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_active">Active</a>
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_busy">Busy</a>
                                        <a class="dropdown-item" href="javascript:void(0)" id="st_away">Away</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex" style="float: right;">
                            <a href="chatroom.php" class="btn btn-sm" title="Public Chat Room"><i class="fa fa-comments" style="font-size: 25px;"></i></a>
                            <div class="dropdown">
                                <a href="javascript:void(0)" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" style="text-decoration: none; font-size: 24px; font-weight: bold; color: grey;" aria-expanded="false">&#8942;</a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="font-size: 15px;">
                                    

                                    <a class="dropdown-item" data-toggle="modal" data-target="#blocked_users_modal" href="javascript:void(0)"><i class="fa fa-ban"></i> Blocked Users</a>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#settings" href="javascript:void(0)"><i class="fa fa-cog"></i> Setting</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-0 pt-2" style="background: #FAFAFA; padding: 0px;">
                        <form>
                            <div class="form-group has-search friends_search" style="padding: 0px 10px 0px 10px;">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" id="search_friend" placeholder="Search">
                            </div>
                            <div class="form-group has-search friends_search_pinned" style="display: none; padding: 0px 10px 0px 10px;">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" id="search_friend_pinned" placeholder="Search">
                            </div>
                            <div class="form-group has-search user_search" style="display: none; padding: 0px 10px 0px 10px;">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" id="search_user" placeholder="Search">
                            </div>
                        </form>
                        <div class="row" style="margin-top: -7px;">
                            <div class="col text-center">
                                <a href="javascript:void(0)" id="recent_msg" class="btn btn-sm btn-light" style="border-bottom: 1px solid grey; font-size: 13px; width: 45%;">Recent</a>
                                <a href="javascript:void(0)" id="all_users" class="btn btn-sm btn-light" style="border-bottom: 1px solid grey; font-size: 13px; width: 45%;">All Users</a>
                            </div>
                        </div>
                        
                        <div class="msg_list_scroll mt-1" style="overflow-x: hidden; overflow-y: scroll; height: 30vh;">
                            <!--<div id="all_chat_list_pinned"></div>-->
                            <div id="all_chat_list"></div>
                            <div id="all_users_list" style="display: none;"></div>
                        </div>
                        <div class="row">
                            <div class="col" style="background: white; height: 5vh; padding-top: 5px; margin: 0px 15px;">
                                <span class="font-weight-bold" style="font-size: 17px; float: left;">Group Chats</span>
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#create_group" class="btn btn-sm btn-secondary" style="float: right; padding: 0px 10px; font-size: 13px;"><i class="fa fa-plus"></i> Group</a>
                            </div>
                        </div>
                        <div class="group_list_scroll mt-1" style="overflow-x: hidden; overflow-y: scroll; height: 35vh;">
                            <div id="group_chat_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        

            <div class="col-lg-8 p-0 margin-0"> <!--*********    RIGHT Panel  (DP,Search,chat area, typing msg,send)     *********-->
                <div class="card" style="height: 94vh;">
                    <?php 
                        error_reporting(0);
                        $user_uniqid = $_GET['user'];
                        $get_user_id = "SELECT * FROM users WHERE uniqid='".$_GET['user']."'"; 
                        $run_user_id = mysqli_query($con,$get_user_id);
                        $row_id = mysqli_fetch_array($run_user_id);

                        $user_iid = $row_id['id'];
                        $p_img = $row_id['profile_image'];
                        $fname = $row_id['first_name'];
                        $lname = $row_id['last_name'];
                    ?>
                    <?php 
                        error_reporting(0);
                        if(!$_GET['user']){ ?>
<!--Display P_Room-->       <div class="row">
                            <div class="col text-center" style="margin-top: 150px;">
                                <img src="images/chat.png" height="100px" width="100px" alt="" />
                                <h3 style="font-weight: light; margin-top: 10px;">Your Messages</h3>
                                <h6>Send private photos and messages to a friend.</h6>
                                <br>
                                <a href="chatroom.php" class="btn btn-md btn-info">Open Public Chat</a>
                            </div>
                        </div>
                    <?php } else{ ?>
<!--DP bar -->          <div class="card-header border-0" style="background: #EEEEEE; padding: 10px 20px;">
                            <div class="row">
                                <div class="col-8">
                                    <div style="float: left;">
                                        <img src="profile_images/<?php echo $p_img; ?>" class="rounded-circle" height="45px" width="45px" style="float: left;" alt="">
                                        <div class="row">
                                            <div class="ml-2 mt-1" style="font-size: 14px; font-weight: 600; width: 300px;"><?php echo $fname; ?> <?php echo $lname; ?></div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" value="<?php echo $user_iid; ?>" id="id_for_status" />
                                            <div class="ml-2" id="head_status" style="width: 300px; font-size: 12px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div style="float: right;">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" style="font-size: 22px; color: grey;" id="search_toggler"><i class="fa fa-search mr-1"></i></a>

                                            <a href="javascript:void(0)" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" style="text-decoration: none; font-size: 25px; font-weight: bold; color: grey;" aria-expanded="false">&#8942;</a>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1" style="font-size: 15px;">
                                                <a class="dropdown-item" style="color: red;" href="javascript:void(0)" id="block_user" data-id="<?php echo $user_iid; ?>"><i class="fa fa-ban"></i> Block</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

<!--msg_search_box-->       <div class="row abcd" id="msg_search_box" style="display: none;">
                                <form style="width: 100%; padding: 5px;">
                                    <input type="text" class="form-control" placeholder="Search Messages" autocomplete="off" id="old_msg_query" />
                                </form>
                            </div>

                            <div id="old_messages_result" style="display: none; overflow-x: hidden; overflow-y: scroll; max-height: 35vh; margin-top: 10px;"></div>
                        </div>

<!--Chat area-->        <div class="card-body" id="user_chat_h" style="overflow-x: hidden; overflow-y: scroll;">
                            <div id="user_chat_history"></div>
                        </div>

<!--Send msg-->         <div class="card-footer" style="background: #EEEEEE; padding-top: 5px;">
                            
                            <div id="top3suggestedmsg" style="display: none; overflow: hidden; margin-bottom: 5px; padding: 0px 20px;"></div>
                            <div class="row">
                                <div class="col">
                                    <form id="send_message">
                                        <div class="input-group mb-1">
                                            
                                            <input type="number" id="user_id_i" style="display: none;" value="<?php echo $user_iid; ?>">
                                    
                                            <input type="text" style="border-radius: 30px;" class="form-control pl-3 pr-3 ml-2 mr-2 send_box" id="message_text" autocomplete="off" placeholder="Enter Message" />
                                            
                                            <div class="input-group-append">
                                                <button type="submit" class="btn" style="border: none;" id="send_message_messenger" data-to_userid="<?php echo $user_iid; ?>"><img src="images/send.png" style="margin-top: -5px; margin-left: 10px;" width="30px" alt=""></button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal" id="myprofilemodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="myprofilemodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header p-2 text-center">
            <h5 class="modal-title w-100">My Profile</h5>
            <a href="javascript:void(0)" data-dismiss="modal"><i class="fa fa-times" style="color: black; font-size: 22px;"></i></a>
        </div>
        <div class="modal-body" style="height: 70vh; overflow-y: scroll;">
            <div id="success_message" style="text-align: center;"></div>
            <?php 
                $query = "SELECT * FROM users WHERE id = '".$_SESSION['user_id']."'";
                $statement = $connect->prepare($query);
                $statement->execute();
                $result = $statement->fetchAll();
                foreach($result as $row)
                {
                    $profile_image = $row['profile_image'];
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
                    $status = $row['status'];
                }
            ?>
            <div class="row">
                <div class="col text-center">
                    <img src="profile_images/<?php echo $profile_image; ?>" class="rounded-circle" id='imgupload' height="150px" width="150px" />
                    <br>
                    <span class="input-group-btn">
                        <span class="btn btn-default btn-file">
                        <div class="file btn btn-sm btn-light" id="cng_btn" style="position: relative; border: 1px solid #E2E6EA; width: auto; overflow: hidden; margin-top: 5px; font-size: 12px;"><i class='fa fa-picture-o' aria-hidden='true'></i> Change
  						    <input type="file" style="position: absolute; font-size: 50px; opacity: 0; right: 0; top: 0;" name="uploaded_image" id="uploaded_image" />
					    </div>
                        </span>
                    </span>
                    <input type="hidden" class="form-control" readonly>
                    <input type="hidden" id="image_name" value="<?php echo $profile_image; ?>" />
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-6">  
                    <label style="font-size: 12px;">First Name</label>  
                    <input type="text" id="first_name" value="<?php echo $first_name; ?>" class="form-control" />
                </div>
                <div class="col-6"> 
                    <label style="font-size: 12px;">First Name</label>    
                    <input type="text" id="last_name" value="<?php echo $last_name; ?>" class="form-control" />
                </div>
            </div>
            
        </div>
        <div class="modal-footer p-1 text-center">
            <div class="w-100">
                <button type="button" class="btn btn-sm btn-danger" style="padding: 1px 10px;" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" id="update_profile" style="padding: 1px 10px;">Update Profile</button>
            </div>
        </div>
        </div>
        </div>
    </div>
    
    <!-- Create_group Modal -->
    <div class="modal" id="create_group" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card" id="success_message_create_group">
                        <div class="card-header text-center">
                            <h5>Create New Group</h5>
                        </div>
                        <div class="card-body">
                            <div id="error_msg_make_group" style="text-align: center;"></div>
                            <label for="name" style="font-size: 14px; font-weight: bold;">Group Name</label><br>
                            <input type="text" class="form-control" id="group_name" placeholder="Enter group name" />
                        </div>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="add_group">Create Group</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Modal -->
    <div class="modal" id="settings" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card" id="success_add_member_to_group">
                        <div class="card-header text-center p-1">
                            <span style="text-align: center; font-size: 17px; font-weight: bold;">Settings</span>
                        </div>
                        <div class="card-body p-1" style="height: 60vh; overflow-y: scroll; overflow-x: hidden;">
                            <div style="text-align: center; font-size: 15px; font-weight: bold;"><u>Change Password</u></div>
                                <div class="text-center text-danger mb-2" id="change_pass_error"></div>
                                <label for="old_pass" style="font-size: 13px; font-weight: bold;">Old Password:</label>
                                <input type="password" name="old_pass" id="old_pass" class="form-control" />

                                <label for="new_pass" style="font-size: 13px; font-weight: bold;">New Password:</label>
                                <input type="password" name="new_pass" id="new_pass" class="form-control" />

                                <label for="c_new_pass" style="font-size: 13px; font-weight: bold;">Confirm New Password:</label>
                                <input type="password" name="c_new_pass" id="c_new_pass" class="form-control" />

                                <br>
                                <div style="text-align: center;">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" style="padding: 0px 10px;" id="change_password">Update Password</a>
                                </div>
                                <div class="dorpdown-divider"></div>
                                <div class="row mt-3">
                                    
                                </div>
                                <br><br><br><br><br>
                        </div>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-dismiss="modal">close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked_users Modal -->
    <div class="modal" id="blocked_users_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Blocked Users</h5>
                        </div>
                        <div class="card-body">
                            <div id="message_blocked_users" style="text-align: center;"></div>
                            <div id="blocked_users_list"></div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-dismiss="modal">close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    

    <div style="display: none;" id="hidden"></div>
    <input type="hidden" name="" id="st_value">
    
    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-button@latest/dist/index.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    <script src="scripts/script.js?v=<?php echo time(); ?>"></script>
    <script src="scripts/message_action.js?v=<?php echo time(); ?>"></script>
    
</body>
</html>