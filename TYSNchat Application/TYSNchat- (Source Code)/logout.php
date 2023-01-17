<?php

    //logout.php

    session_start();

    unset($_SESSION["user_id"]);
    unset($_SESSION["email"]);
    unset($_SESSION["uniqid"]);

    session_destroy();

    header('location: index.php');

?>