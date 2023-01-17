let to_const_send=0;
let to_const_group=0;
let to_const_room=0;

$(document).ready(function(){
    
    load_users();
    function load_users(query = '')
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_users', query:query},
            success:function(data)
            {
                $('#all_chat_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_users();
    }, 10000);

    load_m_users();
    function load_m_users(query = '')
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_users_m', query:query},
            success:function(data)
            {
                $('#m_all_chat_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_m_users();
    }, 10000);

    load_users_pinned();
    function load_users_pinned(query = '')
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_users_pinned', query:query},
            success:function(data)
            {
                $('#all_chat_list_pinned').html(data);
            }
        });
    }
    setInterval(function(){
        load_users_pinned();
    }, 10000);

    load_m_users_pinned();
    function load_m_users_pinned(query = '')
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_users_m_pinned', query:query},
            success:function(data)
            {
                $('#m_all_chat_list_pinned').html(data);
            }
        });
    }
    setInterval(function(){
        load_m_users_pinned();
    }, 10000);

    //Search users home.php
    $(document).on('keyup', '#search_friend', function(){
        var search_value = $('#search_friend').val();
        if(search_value != '')
        {
            load_users(search_value);
        }
        else
        {
            load_users();
        }
    });

    //Search users home.php
    $(document).on('keyup', '#m_search_friend', function(){
        var search_value = $('#m_search_friend').val();
        if(search_value != '')
        {
            load_m_users(search_value);
        }
        else
        {
            load_m_users();
        }
    });

    //Search users home.php
    $(document).on('keyup', '#search_friend_pinned', function(){
        var search_value = $('#search_friend_pinned').val();
        if(search_value != '')
        {
            load_users_pinned(search_value);
        }
        else
        {
            load_users_pinned();
        }
    });

    //Search users home.php
    $(document).on('keyup', '#m_search_friend_pinned', function(){
        var search_value = $('#m_search_friend_pinned').val();
        if(search_value != '')
        {
            load_m_users_pinned(search_value);
        }
        else
        {
            load_m_users_pinned();
        }
    });

    $(document).on('click', '#m_recent_msg', function(){
        load_m_users();
        $('#m_all_chat_list').show();
        $('#m_all_chat_list_pinned').hide();
        $('#m_all_users_list').hide();
        $('.m_friends_search').show();
        $('.m_friends_search_pinned').hide();
        $('.m_user_search').hide();
    })

    $(document).on('click', '#recent_msg', function(){
        load_users();
        $('#all_chat_list').show();
        $('#all_chat_list_pinned').hide();
        $('#all_users_list').hide();
        $('.friends_search').show();
        $('.friends_search_pinned').hide();
        $('.user_search').hide();
    })

    $(document).on('click', '#m_recent_msg_pinned', function(){
        load_m_users_pinned();
        $('#m_all_chat_list_pinned').show();
        $('#m_all_chat_list').hide();
        $('#m_all_users_list').hide();
        $('.m_friends_search').hide();
        $('.m_user_search').hide();
        $('.m_friends_search_pinned').show();
    })

    $(document).on('click', '#recent_msg_pinned', function(){
        load_users_pinned();
        $('#all_chat_list_pinned').show();
        $('#all_chat_list').hide();
        $('#all_users_list').hide();
        $('.friends_search').hide();
        $('.user_search').hide();
        $('.friends_search_pinned').show();
    })


    load_allusers();
    function load_allusers(query = '')
    {
        $.ajax({
            url:"backend/action.php",
            method:"POST",
            data:{action:'fetch_all_users', query:query},
            success:function(data)
            {
                $('#all_users_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_allusers();
    }, 10000);


    load_m_allusers();
    function load_m_allusers(query = '')
    {
        $.ajax({
            url:"backend/action.php",
            method:"POST",
            data:{action:'fetch_all_m_users', query:query},
            success:function(data)
            {
                $('#m_all_users_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_m_allusers();
    }, 10000);

    //Search users home.php
    $(document).on('keyup', '#search_user', function(){
        var search_value = $('#search_user').val();
        if(search_value != '')
        {
            load_allusers(search_value);
        }
        else
        {
            load_allusers();
        }
    });

    //Search users home.php
    $(document).on('keyup', '#m_search_user', function(){
        var search_value = $('#m_search_user').val();
        if(search_value != '')
        {
            load_m_allusers(search_value);
        }
        else
        {
            load_m_allusers();
        }
    });

    $(document).on('click', '#start_chat', function(){
        var user_id = $(this).data('id');
        var action = 'start_new_chat';

        $.ajax({
            url: 'backend/action.php',
            method: 'POST',
            data: {action:action, user_id:user_id},
            success:function(data){
                
            }
        })
    });

    $(document).on('click', '#all_users', function(){
        load_allusers();
        $('#all_chat_list_pinned').hide();
        $('#all_chat_list').hide();
        $('#all_users_list').show();
        $('.friends_search').hide();
        $('.friends_search_pinned').hide();
        $('.user_search').show();
    })

    $(document).on('click', '#m_all_users', function(){
        load_allusers();
        $('#m_all_chat_list_pinned').hide();
        $('#m_all_chat_list').hide();
        $('#m_all_users_list').show();
        $('.m_friends_search').hide();
        $('.m_friends_search_pinned').hide();
        $('.m_user_search').show();
    })

    //send Room Messages
    $(document).on('submit', '#send_message_room', function(event){
        event.preventDefault();
        var message_text_room = $('#message_text_room').val();

        var length = message_text_room.replace(/\s/g, "").length;

        var action = 'send_message_room';

        $('#message_text_room').css('border-color', 'white');

        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{message_text_room:message_text_room, action:action},
            beforeSend:function(){
                $('#message_text_room').attr('disabled', 'Yes');
                $('#send_message_room').addClass('disabled');
            },
            success:function(data)
            {
                $('#message_text_room').val('');
                $('#message_text_room').removeAttr('disabled');
                $('#send_message_room').removeClass('disabled');
                load_messages_room();
            }
        })
        
    });

    function load_room_messages()
    {
        var action = 'fetch_room_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:action},
            success:function(data)
            {
                $('#room_chat_history').html(data);
            }
        })
    }

    load_messages_room();
    function load_messages_room()
    {
        var action = 'fetch_room_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:action},
            success:function(data)
            {
                $('#room_chat_history').html(data);
                $('#user_chat_h_room').scrollTop($('#user_chat_h_room').prop('scrollHeight'));
            }
        })
    }

    setInterval(function(){
        $.ajax({
            url:"backend/to_refresh_room.php",
            success:function(r)
            {
                $('#hidden2').html(r);
                var sst = $('#st_value2').val();
                if(to_const_room == '1'){
                    load_messages_room();
                    to_const='0';
                    if(sst == 'On'){
                        var a = new Audio()
                        a.src =   "msg.mp3"
                        a.play()
                    }
                    
                }
            }
        })

    }, 2000);

    //send message
    $(document).on('submit', '#send_message', function(event){
        event.preventDefault();
        var message_text = $('#message_text').val();
        var image_name = $('#image_name_message').val();
        var file_ext = $('#file_ext_msg').val();
        var to_user_id = $('#send_message_messenger').data("to_userid");

        var length = message_text.replace(/\s/g, "").length;

        var action = 'send_message_messenger';

            $('#message_text').css('border-color', 'white');

            $.ajax({
                url:"backend/message_action.php",
                method:"POST",
                data:{message_text:message_text, file_ext:file_ext, image_name:image_name,to_user_id:to_user_id, action:action},
                beforeSend:function(){
                    $('#message_text').attr('disabled', 'Yes');
                    $('#send_message_messenger').addClass('disabled');
                },
                success:function(data)
                {
                    $('#message_text').val('');
                    $('#file_upload_details').html('');

                    $('#attachment_box').hide('fast');
                    $('#message_text').removeAttr('disabled');
                    $('#send_message_messenger').removeClass('disabled');
                    load_users();
                    load_messages();
                }
            })
        
    });

    function load_user_messages()
    {
        var user_id = $('#user_id_i').val();
        var action = 'fetch_user_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{user_id:user_id, action:action},
            success:function(data)
            {
                $('#user_chat_history').html(data);
            }
        })
    }

    load_messages();
    function load_messages()
    {
        var user_id = $('#user_id_i').val();
        var action = 'fetch_user_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{user_id:user_id, action:action},
            success:function(data)
            {
                $('#user_chat_history').html(data);
                $('#user_chat_h').scrollTop($('#user_chat_h').prop('scrollHeight'));
            }
        })
    }

    load_status();
    function load_status()
    {
        var user_id = $('#id_for_status').val();
        var action = 'fetch_user_head_status';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{user_id:user_id, action:action},
            success:function(data)
            {
                $('#head_status').html(data);
            }
        })
    }

    setInterval(function(){
        load_status();
    }, 3000);

    setInterval(function(){
        $.ajax({
            url:"backend/to_refresh.php",
            success:function(r)
            {
                $('#hidden').html(r);
                var sst = $('#st_value').val();
                if(to_const_send == '1'){
                    load_users();
                    load_messages();
                    to_const='0';
                    if(sst == 'On'){
                        var a = new Audio()
                        a.src =   "msg.mp3"
                        a.play()
                    }
                    
                }
            }
        })

    }, 2000);

    $(document).on('change', '#uploaded_image', function() {
        var property = document.getElementById('uploaded_image').files[0];
        var file_name = property.name;
        
        var formdata = new FormData();
        formdata.append('file',property);
        $.ajax({
            url: 'backend/image_upload.php',
            method: 'POST',
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
                $('#cng_btn').html('<i class="fa fa-spinner"></i> Uploading...');
                $('#update_profile').addClass('disabled');
            },
            success:function(data){
                $('#image_name').val(data);
                $('#update_profile').removeClass('disabled');
                $('#cng_btn').html('<i class="fa fa-check"></i> Uploaded');
                setTimeout(function(){
                    $('#cng_btn').html('<i class="fa fa-image"></i> Change');
                },5000);
            }
        })

		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
	});

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#imgupload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#uploaded_image").change(function(){
        readURL(this);
    });


    $(document).on('click', '#update_profile', function(){
        var f_name = $('#first_name').val();
        var l_name = $('#last_name').val();
        var image_name = $('#image_name').val();

        var action = 'update_profile';

        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action, f_name:f_name, l_name:l_name, image_name:image_name},
            beforeSend:function(){
                $('#update_profile').addClass('disabled');
            },
            success:function(data){
                window.open('messages.php','_self');
                $('#update_profile').removeClass('disabled');
                $('#success_message').html('<div class="alert alert-success" role="alert">Profile Updated</div>');
            }
        })
    })

    $(document).on('click', '#add_group', function(){
        var group_name = $('#group_name').val();
        var action = 'create_new_group';

        var length = group_name.replace(/\s/g, "").length;
 
        if(group_name != '' && length != 0){
            $.ajax({
                url: 'backend/message_action.php',
                method: 'POST',
                data: {action:action, group_name:group_name},
                beforeSend:function(){
                    $('#add_group').addClass('disabled');
                },
                success:function(data){
                    location.reload();
                    $('#add_group').removeClass('disabled');
                    $('#success_message_create_group').html('<div class="col" style="text-align: center;"><br><h4 class="text-success"><i class="fa fa-check-square" style="font-size: 50px;" aria-hidden="true"></i><br><strong>Success</strong></h4><br><br></div>');
                }
            })
        }
        else{
            $('#error_msg_make_group').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Enter Group Name</div>');
            alert('Enter Name');
        }
        
    })

    $(document).on('click', '#page_reload', function(){
        location.reload();
    })

    
    load_chatgroups();
    function load_chatgroups()
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_chatgroups'},
            success:function(data)
            {
                $('#group_chat_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_chatgroups();
    }, 10000);

    load_m_chatgroups();
    function load_m_chatgroups()
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_all_chatgroups_m'},
            success:function(data)
            {
                $('#m_group_chat_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_m_chatgroups();
    }, 10000);

    //send group message
    $(document).on('submit', '#send_group_message_form', function(event){
        event.preventDefault();
        var message_text = $('#group_message_text').val();
        var image_name = $('#image_name_message_group').val();
        var file_ext = $('#file_ext_msg_group').val();
        var group_id = $('#group_id_i').val();

        var action = 'send_group_message';

            $.ajax({
                url:"backend/message_action.php",
                method:"POST",
                data:{message_text:message_text, file_ext:file_ext,image_name:image_name, group_id:group_id, action:action},
                beforeSend:function(){
                    $('#group_message_text').attr('disabled', 'Yes');
                    $('#send_group_message_btn').addClass('disabled');
                },
                success:function(data)
                {
                    $('#group_message_text').val('');
                    $('#file_upload_details_group').html('');

                    $('#group_message_text').removeAttr('disabled');
                    $('#send_group_message_btn').removeClass('disabled');
                    load_group_messages();
                }
            })
    });

    function load_all_group_messages()
    {
        var group_id = $('#group_id_i').val();
        var action = 'fetch_group_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{group_id:group_id, action:action},
            success:function(data)
            {
                $('#group_chat_history').html(data);
            }
        })
    }

    load_group_messages();
    function load_group_messages()
    {
        var group_id = $('#group_id_i').val();
        var action = 'fetch_group_messages';
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{group_id:group_id, action:action},
            success:function(data)
            {
                $('#group_chat_history').html(data);
                $('#group_chat_h').scrollTop($('#group_chat_h').prop('scrollHeight'));
            }
        })
    }

    function load_allusers_for_group(group_id, query = '')
    {
        var group_id = $('#group_id_i').val();

        $.ajax({
            url:"backend/action.php",
            method:"POST",
            data:{action:'fetch_all_users_for_group', query:query, group_id:group_id},
            success:function(data)
            {
                $('#member_lists_to_add_in_group').html(data);
            }
        });
    }
    setInterval(function(){
        load_allusers_for_group();
    }, 10000);

    $(document).on('click', '#fetch_all_members', function(){
        var group_id = $(this).data('group_id');
        load_allusers_for_group(group_id);
    })

    $(document).on('keyup', '#search_user_for_group', function(){
        var group_id = $('#group_id_i').val();
        var search_value = $('#search_user_for_group').val();
        if(search_value != '')
        {
            load_allusers_for_group(group_id, search_value);
        }
        else
        {
            load_allusers_for_group();
        }
    });

    $(document).on('click', '.add_member_to_group', function(){
        var user_id = $(this).data('user_id');
        var group_id = $('#group_id_i').val();

        var action = 'add_member_to_group';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {user_id:user_id, group_id:group_id, action:action},
            beforeSend:function(){
                $('#add_to_group_'+user_id).addClass('disabled');
                $('#add_to_group_'+user_id).html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                $('#add_member_message').html(data);
                $('#add_to_group_'+user_id).html('<i class="fa fa-check"></i>');
                load_allusers_for_group();
                load_group_member_count();
            }
        })
    });

    function load_group_member(group_id)
    {
        var group_id = $('#group_id_i').val();

        $.ajax({
            url:"backend/action.php",
            method:"POST",
            data:{action:'load_group_member', group_id:group_id},
            success:function(data)
            {
                $('#all_group_members').html(data);
            }
        });
    }
    setInterval(function(){
        load_group_member();
    }, 3000);

    $(document).on('click', '#fetch_group_m_info', function(){
        var group_id = $(this).data('group_id');
        load_group_member(group_id);
    })

    $(document).on('click', '.remove_member', function(){
        var user_id = $(this).data('user_id');
        var group_id = $('#group_id_i').val();

        var action = 'remove_member_from_group';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {user_id:user_id, group_id:group_id, action:action},
            beforeSend:function(){
                $('#remove_from_group_'+user_id).addClass('disabled');
                $('#remove_from_group_'+user_id).html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                $('#remove_member_message').html(data);
                $('#remove_from_group_'+user_id).html('<i class="fa fa-check"></i>');
                load_group_member();
                load_group_member_count();
            }
        })
    });

    $(document).on('click', '#leave_group', function(){
        var group_id = $('#group_id_i').val();

        var action = 'leave_from_group';
        if(confirm('Are you sure, you want to leave the group?')){
            $.ajax({
                url: 'backend/message_action.php',
                method: 'POST',
                data: {group_id:group_id, action:action},
                success:function(data){
                    window.open('groupchat.php');
                }
            })
        }
        
    });

    $(document).on('click', '#change_group_name', function(){
        var group_name = $('#group_name_info').val();
        var group_id = $('#group_id_i').val();

        var action = 'change_group_name';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {group_id:group_id, group_name:group_name, action:action},
            beforeSend:function(){
                $('#change_group_name').addClass('disabled');

            },
            success:function(data){
                $('#remove_member_message').html(data);
                $('#change_group_name').removeClass('disabled');
            }
        })
        
        
    });
    load_group_member_count();
    function load_group_member_count()
    {
        var group_id = $('#group_id_i').val();

        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'load_group_member_count', group_id:group_id},
            success:function(data)
            {
                $('#count_member').html(data);
            }
        });
    }
    setInterval(function(){
        load_group_member_count();
    }, 3000);


    //Block User
    $(document).on('click', '#block_user', function() {
        var user_id = $(this).data('id');
        var action = 'block_user';

        if(confirm('Are you sure, you want to ban this user?')){
            $.ajax({
                url: 'backend/message_action.php',
                method: 'POST',
                data: {user_id:user_id, action:action},
                beforeSend:function() { 
                    $('#block_user').addClass('disabled');
                },
                success:function(data) {
                    window.open('messages.php','_self');
                }


            })
        }
    })
    //Load_users_home.php
    load_blocked_users();
    function load_blocked_users()
    {
        $.ajax({
            url:"backend/message_action.php",
            method:"POST",
            data:{action:'fetch_blocked_users'},
            success:function(data)
            {
                $('#blocked_users_list').html(data);
            }
        });
    }
    setInterval(function(){
        load_blocked_users();
    }, 3000);

    $(document).on('click', '.unblock_user', function(){
        var user_id = $(this).data('user_id');

        var action = 'unblock_user';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {user_id:user_id, action:action},
            beforeSend:function(){
                $('#unblock_'+user_id).addClass('disabled');
                $('#unblock_'+user_id).html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                $('#message_blocked_users').html(data);
                $('#unblock_'+user_id).html('<i class="fa fa-check"></i>');
                load_blocked_users();
                load_users();
            }
        })
    });

    $(document).on('click', ('#change_password '), function(){
        var old_pass = $('#old_pass').val();
        var new_pass = $('#new_pass').val();
        var conf_new_pass = $('#c_new_pass').val();

        var action = 'change_password';

        if (old_pass != '') {
            if (new_pass != '') {
                $('#change_pass_error').html('');
                if (conf_new_pass != '') {
                    $('#change_pass_error').html('');
                    if (new_pass == conf_new_pass) {
                        if (new_pass.length >= 8) {
                            $.ajax({
                                url: 'backend/action.php',
                                method: 'POST',
                                data: {action:action, old_pass:old_pass, new_pass:new_pass},
                                beforeSend:function(){
                                    $('#change_password').addClass('disabled');
                                },
                                success:function(data){
                                    $('#change_password').removeClass('disabled');
                                        
                                    $('#change_pass_error').html(data);
                                    $('#settings').hide();
                                }
                            })
                        }
                        else{
                            $('#change_pass_error').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Password must be 8 characters long</div>');
                        }

                        
                    }
                    else{
                        $('#change_pass_error').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Password not match</div>');
                    }
                }
                else{
                    $('#change_pass_error').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Enter new password</div>');
                }
            }
            else{
                $('#change_pass_error').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Enter new password</div>');
            }
        }
        else{
            $('#change_pass_error').html('<div class="alert alert-danger" role="alert" style="font-size: 13px;">Enter Old Password</div>');
        }
    })

    setInterval(function(){
        var group_id = $('#group_id_i').val();

        var action = 'get_group_refresh';
        $.ajax({
            url:"backend/message_action.php",
            method: 'POST',
            data: {group_id:group_id, action:action},
            success:function(data)
            {
                $('#hidden1').html(data);
                var vt = $('#group_refresh_val').val();

                if(vt == '1'){
                    load_group_messages();
                }
            }
        })

    }, 1000);

    setInterval(function(){
        var group_id = $('#group_id_i').val();

        var vt = $('#group_refresh_val').val();
        var action = 'reset_group_refresh';

        if(vt == '1'){
            $.ajax({
                url:"backend/message_action.php",
                method: 'POST',
                data: {group_id:group_id, action:action},
                success:function(data)
                {
                    
                }
            })
        }
    }, 2000);

    $(document).on('click', '#pin_chat', function(e){
        e.preventDefault();
        var chat_id = $(this).data('chat_id');
        var action = 'pin_chat';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {chat_id:chat_id, action:action},
            success:function(data){
                load_users_pinned();
                load_m_users_pinned();
                load_users();
                load_m_users();
            }
        })
    });

    $(document).on('click', '#unpin_chat', function(e){
        e.preventDefault();
        var chat_id = $(this).data('chat_id');
        var action = 'unpin_chat';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {chat_id:chat_id, action:action},
            success:function(data){
                load_users();
                load_m_users();
                load_users_pinned();
                load_m_users_pinned();
            }
        })
    });

    fetch_my_status();
    function fetch_my_status(){
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action: 'fetch_my_status'},
            success:function(data){
                $('#show_my_status').html(data);
                $('#show_my_status_m').html(data);
            }
        })
    }

    $(document).on('click', '#st_active', function(e){
        var action = 'change_st_to_active';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action},
            beforeSend:function() {
                $('#show_my_status').html('<i class="fa fa-spinner"></i>');
                $('#show_my_status_m').html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                fetch_my_status();
            }
        })
    });

    $(document).on('click', '#st_busy', function(e){
        var action = 'change_st_to_busy';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action},
            beforeSend:function() {
                $('#show_my_status').html('<i class="fa fa-spinner"></i>');
                $('#show_my_status_m').html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                fetch_my_status();
            }
        })
    });

    $(document).on('click', '#st_away', function(e){
        var action = 'change_st_to_away';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action},
            beforeSend:function() {
                $('#show_my_status').html('<i class="fa fa-spinner"></i>');
                $('#show_my_status_m').html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                fetch_my_status();
            }
        })
    });

    $(document).on('keyup', '#old_msg_query', function(){
        var value = $('#old_msg_query').val();
        var to_user_id = $('#send_message_messenger').data("to_userid");

        if(value != ''){
            $.ajax({
                url: 'backend/message_action.php',
                method: 'POST',
                data: {value:value, to_user_id:to_user_id, action: 'get_old_messages'},
                success:function(data){
                    $('#old_messages_result').show();
                    $('#old_messages_result').html(data);
                }
            })
        }
        else{
            $('#old_messages_result').hide();
        }
        
    })

    $(document).on('click', '#search_toggler', function(){
        
        $('#msg_search_box').slideToggle();
        
    })

    $(document).on('keyup', '#old_groupmsg_query', function(){
        var value = $('#old_groupmsg_query').val();
        var group_id = $('#group_id_i').val();

        if(value != ''){
            $.ajax({
                url: 'backend/message_action.php',
                method: 'POST',
                data: {value:value, group_id:group_id, action: 'get_old_groupmessages'},
                success:function(data){
                    $('#old_groupmessages_result').show();
                    $('#old_groupmessages_result').html(data);
                }
            })
        }
        else{
            $('#old_groupmessages_result').hide();
        }
        
    })

    $(document).on('click', '#add_old_msg', function(){
        
        var msg = $(this).data('msg');
        
        $('#message_text').val(msg);

        $('#old_messages_result').hide();

        $('#message_text').focus();
        
    })
    $(document).on('click', '#add_old_msg_group', function(){
        
        var msg = $(this).data('msg');
        
        $('#group_message_text').val(msg);

        $('#old_groupmessages_result').hide();

        $('#group_message_text').focus();
        
    })

    function gettop3(to_user_id){
        var to_user_id = $('#send_message_messenger').data("to_userid");
        var action = 'get_top3_messages';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {to_user_id:to_user_id ,action:action},
            success:function(data){
                $('#top3suggestedmsg').show('fast');
                $('#top3suggestedmsg').html(data);
            }
        })
    }

    $(document).on('click', '#message_text', function(){
        var to_user_id = $('#send_message_messenger').data("to_userid");
        gettop3(to_user_id);
    })

    function gettop3_group(to_user_id){
        var group_id = $('#group_id_i').val();
        var action = 'get_top3_messages_group';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {group_id:group_id ,action:action},
            success:function(data){
                $('#top3suggestedmsg_group').show('fast');
                $('#top3suggestedmsg_group').html(data);
            }
        })
    }

    $(document).on('click', '#group_message_text', function(){
        var group_id = $('#group_id_i').val();

        gettop3_group(group_id);
    })

    $(document).on('click', '#sendfiles', function(){
        $('#top3suggestedmsg').hide('fast');
        $('#attachment_box').slideToggle("fast");
        
    })


    $(document).on('change', '#uploaded_image_msg', function() {
        var property = document.getElementById('uploaded_image_msg').files[0];
        var file_name = property.name;
        
        var formdata = new FormData();
        formdata.append('file',property);
        $.ajax({
            url: 'backend/msg_img_upload.php',
            method: 'POST',
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
                $('#send_img_loader').html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
                $('#send_message_messenger').addClass('disabled');
            },
            success:function(data){
                $('#file_upload_details').html(data);
                $('#send_message_messenger').removeClass('disabled');
                $('#send_img_loader').hide();
                $('#message_text').focus();
            }
        })
	});

    
    fetch_sound_status();
    function fetch_sound_status(){
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action: 'fetch_sound_status'},
            success:function(data){
                $('#show_sound_status').html(data);
                $('#st_value').val(data);
                $('#st_value2').val(data);
            }
        })
    }

    setInterval(function(){
        fetch_sound_status();
    }, 1000);

    $(document).on('click', '#sa_on', function(e){
        var action = 'change_as_on';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action},
            beforeSend:function() {
                $('#show_sound_status').html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                fetch_sound_status();
            }
        })
    });

    $(document).on('click', '#sa_off', function(e){
        var action = 'change_as_off';
        $.ajax({
            url: 'backend/message_action.php',
            method: 'POST',
            data: {action:action},
            beforeSend:function() {
                $('#show_sound_status').html('<i class="fa fa-spinner"></i>');
            },
            success:function(data){
                fetch_sound_status();
            }
        })
    });

    $(document).on('click', '#download_file',function(){
        var file_name = $(this).data('file_name');
        var action = 'download_file';
        $.ajax({
            url: 'file_download.php',
            method: 'POST',
            data: {action:action, file_name:file_name},
            success:function(data){
                
            }
        })
    });

});

