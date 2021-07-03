<?php
if (isset($_POST['submit'])) {
    require_once 'dbConfig.inc.php';
    require_once 'functions.inc.php';

    $username = strip_tags($_POST['username']);
    $pwd = strip_tags($_POST['pass']);

    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($con, $username, $pwd);
} else {
    header("location: ../login.php");
    exit();
}
