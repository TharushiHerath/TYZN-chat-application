$(document).ready(function(){
    
    //Create_account
    $(document).on('click', '#signup', function(){
        var f_name = $('#f_name').val();
        var l_name = $('#l_name').val();
        var email = $('#email').val();
        var pass = $('#password').val();
        var conf_pass = $('#c_password').val();

        var fname_length = f_name.replace(/\s/g, "").length;
        var lname_length = l_name.replace(/\s/g, "").length;
        var email_length = email.replace(/\s/g, "").length;
        var pass_length = pass.replace(/\s/g, "").length;
        var conf_pass_length = conf_pass.replace(/\s/g, "").length;

        var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        var action = 'create_new_account';

        if(f_name != '' && fname_length>0){
            $('#error_f_name').html('');
            if(l_name != '' && lname_length>0){
                $('#error_l_name').html('');
                if(email != '' && email.match(mailformat) && email_length>0){
                    $('#error_email').html('');
                    if(pass != '' && pass_length>5){
                        $('#error_password').html('');
                        if(conf_pass != '' && conf_pass==pass && conf_pass_length>5){
                            $('#error_c_password').html('');


                            $.ajax({
                                url: 'backend/pre_action.php',
                                method: 'POST',
                                data: {action:action, f_name:f_name, l_name:l_name, email:email, pass:pass},
                                beforeSend:function(){
                                    $('#signup').addClass('disabled');
                                    $('#signup').html('<i class="fa fa-circle-o-notch fa-spin"></i> Continue to Chat');
                                },
                                success:function(data){
                                    $('#message').html(data);
                                    var st = $('#signup_st').val();

                                    if (st == 1) {
                                        $('#signup').removeClass('disabled');
                                        $('#signup').html('Continue to Chat');
                                        
                                    }
                                    else{
                                        $('#signup').html('<i class="fa fa-check"></i>');
                                    }
                                }
                            })

                            
                        }
                        else{
                            $('#error_c_password').html('Password not matched');
                        }
                    }
                    else{
                        $('#error_password').html('Enter Password (at least 6 digit)');
                    }
                }
                else{
                    $('#error_email').html('Enter a Valid Email');
                }
            }
            else{
                $('#error_l_name').html('Enter Last Name');
            }
        }
        else{
            $('#error_f_name').html('Enter First Name');
        }
    });

    //Login_user_account
    $(document).on('click', ('#login'), function(){
        var email = $('#email').val();
        var pass = $('#password').val();

        var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

        var action = 'login_to_account';

        if (email != '' && email.match(mailformat)) {
            $('#error_email').html('');
            if (pass != '') {
                $('#error_password').html('');
                $.ajax({
                    url: 'backend/pre_action.php',
                    method: 'POST',
                    data: {action:action, email:email, pass:pass},
                    beforeSend:function(){
                        $('#login').addClass('disabled');
                        $('#login').html('<i class="fa fa-circle-o-notch fa-spin"></i> Continue to Chat');
                        $('#email').attr('disabled', 'Yes');
                        $('#password').attr('disabled', 'Yes');
                    },
                    success:function(data){
                        $('#message').html(data);
                        
                        var st = $('#login_st').val();

                        if (st == 1) {
                            $('#login').removeClass('disabled');
                            $('#login').html('Continue to Chat');
                            $('#email').removeAttr('disabled', 'Yes');
                            $('#password').removeAttr('disabled', 'Yes');
                        }
                        else{
                            $('#login').html('<i class="fa fa-check"></i>');
                        }
                    }
                })
            }
            else{
                $('#error_password').html('Enter Password');
            }
        }
        else{
            $('#error_email').html('Enter a Valid Email');
        }
    })
})