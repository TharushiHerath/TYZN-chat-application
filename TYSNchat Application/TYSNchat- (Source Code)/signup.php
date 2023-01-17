<?php
    include('backend/database_connection.php');

    session_start();

    if(isset($_SESSION['user_id']))
    {
        header('location: home.php');
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
</head>
<style>
    body{
        background: #F7F7F7;
        font-family: Arial, Helvetica, sans-serif;
    }
    .card-body{
        font-size: 14px;
        font-weight: bold;
    }
    input.form-control{
        height: 30px;
        font-size: 13px;
    }
    input.form-control:focus{
        height: 30px;
        box-shadow: none;
        font-size: 13px;
        border-color: black;
    }
    .already_signed{
        font-size: 13.5px;
        font-weight: bold;
    }
    .error_msg{
        font-weight: normal;
        font-size: 11.5px;
    }
    .alert{
        padding: 7px;
    }
</style>
<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 col-lg-4 offset-lg-4 text-center">
                <div class="card">
                    <div class="card-header bg-white border-0 pb-0">
                        <h3>TYSNchat Application</h3>
                        <h6>Signup</h6>
                        <div class="dropdown-divider"></div>
                    </div>
                    <div class="card-body bg-white pt-2">
                        <span id="message"></span>
                        <div class="row">
                            <div class="col-6 text-left p-1">
                                <label for="f_name">First Name</label>
                                <input type="text" name="f_name" id="f_name" class="form-control" placeholder="First Name" />
                                <p class="text-danger error_msg" id="error_f_name"></p>
                            </div>
                            <div class="col-6 text-left p-1">
                                <label for="l_name">Last Name</label>
                                <input type="text" name="l_name" id="l_name" class="form-control" placeholder="Last Name" />
                                <p class="text-danger error_msg" id="error_l_name"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-left p-1">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" />
                                <p class="text-danger error_msg" id="error_email"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-left p-1">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                                <p class="text-danger error_msg" id="error_password"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-left p-1">
                                <label for="c_password">Confirm Password</label>
                                <input type="password" name="c_password" id="c_password" class="form-control" placeholder="Confirm Password" />
                                <p class="text-danger error_msg" id="error_c_password"></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-0">
                        <button type="button" class="btn btn-sm btn-dark" style="padding: 2px 10px; min-width: 50%;" id="signup">Continue to Chat</button>

                        <div class="row">
                            <div class="col-12 text-center p-1 mt-3">
                                <p class="already_signed">Already Signed Up? <a href="index.php" class="text-dark">Login Now</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card End -->
            </div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"></script>
    <script src="scripts/pre_action.js?v=<?php echo time(); ?>"></script>
</body>
</html>