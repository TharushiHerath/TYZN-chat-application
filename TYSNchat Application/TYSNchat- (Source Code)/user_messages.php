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
</head>
<style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css");
    body{
        overflow-x: hidden;
        font-family: Arial, Helvetica, sans-serif;
        background: #3498DB;
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
        border: none;
        background: white;
    }
    .send_box:focus{
        box-shadow: none;
        border: none;
    }
    .my_message{
        background: #74B9FF;
        width: auto;
        max-width: 45%;
        padding: 8px 15px;
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
        padding: 8px 15px;
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

    #msg_search_box input.form-control{
        height: 30px;
        margin-top: 5px;
        border: none;
        border-radius: 10px;
        text-align: center;
        font-size: 13px;
    }

    #msg_search_box input.form-control:focus{
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
    <div class="container d-block d-lg-none">
        <div class="row">
            <div class="col-md-12 p-0 margin-0">
                <div class="card" style="height: 100vh;">
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
                        <div class="row">
                            <div class="col text-center" style="margin-top: 150px;">
                                <img src="images/chat.png" height="100px" width="100px" alt="" />
                                <h3 style="font-weight: light; margin-top: 10px;">Your Messages</h3>
                                <h6>Send private photos and messages to a friend.</h6>
                                <br>
                            </div>
                        </div>
                    <?php } else{ ?>
                        <div class="card-header border-0" style="background: #EEEEEE; padding: 10px 15px;">
                            <div class="row">
                                <div class="col-8">
                                    <div style="float: left;">
                                        <a href="messages.php" class="float-left mr-3" style="font-size: 24px; color: grey;"><i class="fa fa-arrow-left"></i></a>
                                        <img src="profile_images/<?php echo $p_img; ?>" class="rounded-circle" height="45px" width="45px" style="float: left;" alt="">
                                        <div class="row">
                                            <div class="ml-2 mt-1" style="font-size: 14px; font-weight: 600; width: 150px;"><?php echo $fname; ?> <?php echo $lname; ?></div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" value="<?php echo $user_iid; ?>" id="id_for_status" />
                                            <div class="ml-2" id="head_status" style="width: 280px; font-size: 12px;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-4">
                                    <div style="float: right;">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" style="font-size: 22px; color: grey;" id="search_toggler"><i class="fa fa-search mr-1"></i></a>

                                            <a href="javascript:void(0)" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" style="text-decoration: none; font-size: 24px; font-weight: bold; color: grey;" aria-expanded="false">&#8942;</a>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1" style="font-size: 15px;">
                                                <a class="dropdown-item" style="color: red;" href="javascript:void(0)" id="block_user" data-id="<?php echo $user_iid; ?>"><i class="fa fa-ban"></i> Block</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="msg_search_box" style="display: none;">
                                <form style="width: 100%; padding: 5px;">
                                    <input type="text" class="form-control" placeholder="Search Messages" autocomplete="off" id="old_msg_query" />
                                </form>
                            </div>
                            
                            <div id="old_messages_result" style="display: none; overflow-x: hidden; overflow-y: scroll; max-height: 35vh; margin-top: 10px;"></div>
                            
                        </div>

                        <div class="card-body" id="user_chat_h" style="overflow-x: hidden; overflow-y: scroll;">
                            <div id="user_chat_history"></div>
                        </div>

                        <div class="card-footer" style="background: #EEEEEE;">
                            <div id="top3suggestedmsg" style="display: none; overflow: hidden; margin-bottom: 5px; padding: 0px 20px;"></div>
                            <div class="row">
                                <div class="col">
                                    <form id="send_message">
                                        <div class="input-group mb-1">
                                            <a href="javascript:void(0)" class="mr-1" style="text-decoration: none; color: black; font-size: 25px;" id="picker"><i class="bi bi-emoji-smile"></i></a>
                                            
                                            <a class="ml-2" href="javascript:void(0)" class="mr-1" style="transform: rotate(80deg); text-decoration: none; color: black; font-size: 25px;" id="sendfiles"><i class="fa fa-paperclip"></i></a>

                                            <input type="number" id="user_id_i" style="display: none;" value="<?php echo $user_iid; ?>">

                                            <input type="text" style="border-radius: 30px;" class="form-control pl-3 pr-3 ml-2 mr-2 send_box" id="message_text" autocomplete="off" placeholder="Enter Message" />
                                            

                                            <div class="input-group-append">
                                                <button type="submit" class="btn" style="border: none;" id="send_message_messenger" data-to_userid="<?php echo $user_iid; ?>"><img src="images/send.png" style="margin-top: -5px; margin-left: 10px;" width="30px" alt=""></button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div id="attachment_box" style="display: none; overflow: hidden; margin-bottom: 5px; padding: 0px 20px;">
                                <div class="row">
                                    <div class="col-6">
                                        <div id="dragged_div" style="background: white; border-radius: 15px; text-align: center; height: 70px;">
                                            <p style="padding-top: 25px;">Drag an Image and Drop Here</p>
                                            <input type="file" style="position: absolute; font-size: 50px; opacity: 0; right: 0; top: 0;" name="uploaded_image_msg" id="uploaded_image_msg" />
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div id="file_upload_details"></div>
                                        <div id="send_img_loader" style="padding-top: 25px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="view_full_size_image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="text-align: left; padding: 2px;">
                    <div id="img_view"></div>
                    <div class="card card-body" id="close_vid_btn" data-dismiss="modal" aria-label="Close" id="close_video_modal"><a href="javascript:void(0)" style="font-size: 20px; text-decoration: none; color: black;"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                </div>
                
            </div>
        </div>
    </div>

    <input type="hidden" name="" id="st_value">
    <div style="display: none;" id="hidden"></div>
    
    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-button@latest/dist/index.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/message_action.js"></script>
    <script>
        var input = document.querySelector('#message_text');
        var btn = document.querySelector('#picker');
        var picker = new EmojiButton({
            position: 'top'
        })
        picker.on('emoji', function(emoji){
            input.value += emoji;
            $('#message_text').focus();
        })
        btn.addEventListener('click', function(){
            picker.pickerVisible ? picker.hidePicker() : picker.showPicker(input);
        })
    </script>
</body>
</html>