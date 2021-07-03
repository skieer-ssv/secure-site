<?php
session_start();
if (isset($_SESSION['userid'])) {

    header('Location:home.php');
} else {
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "emptyinput") {
            echo '<script>alert("Empty Input");</script>';
        } else if ($_GET['error'] == 'wronguser') {
            echo '<script>alert("Wrong Username");</script>';
        } else if ($_GET['error'] == "wrongpwd") {
            echo '<script>alert("Wrong Password");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <title>SS| Login</title>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>

        <center><div class="container" >
            <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50" style="margin-top: 100px;">
                <h2 style="text-align: center; margin-bottom:12px">Secure Site</h2>
                <form class="login100-form validate-form" action="./include/validation.inc.php" method="POST">
                    <span class="login100-form-title p-b-33">
                         Login
                    </span>

                    <div class="wrap-input100 ">
                        <input class="input100" type="text" name="username" placeholder="Username">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <div class="wrap-input100 rs1 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="pass" placeholder="Password">
                        <span class="focus-input100-1"></span>
                        <span class="focus-input100-2"></span>
                    </div>

                    <div class="container-login100-form-btn m-t-20">
                        <button class="login100-form-btn" type="submit" name="submit">

                            Sign in
                        </button>
                    </div>
                    <br>


                </form>
                <center> <?php if (isset($_GET['msg'])) echo $_GET['msg']; ?></center>
            </div>

        </div>

        </center>
</body>

</html>