$(document).ready(function(){
    //Load_users_home.php
    load_users();
    function load_users(query = '')
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
        load_users();
    }, 60000);

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
});