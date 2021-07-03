<?php
function emptyInputLogin($username, $pwd)
{

    if (empty($username) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function uidExists($con, $username)
{
    $sql = "Select * from credentials where username =? ;";
    $stmt = mysqli_stmt_init($con);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../login.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}


function loginUser($con, $username, $pwd)
{
    $msg = '';
    $time = time() - 60;
    $ip_address = getIPAddress();
    // Getting total count of hits on the basis of IP
    $query = mysqli_query($con, "select count(*) as total_count from loginlogs where tryTime > $time and ipAddress='$ip_address'");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];
    //Checking if the attempt 3, or youcan set the no of attempt her. For now we taking only 3 fail attempted
    if ($total_count == 3) {
        $msg = "Too many failed login attempts. Please login after 60 sec";
        header("location: ../login.php?msg=$msg");
        exit();
    } else {
        $uidExists = uidExists($con, $username);
        if ($uidExists === false) {
            header("location: ../login.php?error=wronguser");
            exit();
        }
        // TODO: add hashed passwords
        $pwdHashed = $uidExists['password'];


        if (!password_verify($pwd, $pwdHashed)) {

            $total_count++;
            $rem_attm = 3 - $total_count;
            if ($rem_attm == 0) {
                $msg = "Too many failed login attempts. Please login after 60 sec";
                header("location: ../login.php?msg=$msg&error=wrongpwd");
            } else {
                $msg = "Please enter valid login details.<br/>$rem_attm attempts remaining";
                $try_time = time();
                mysqli_query($con, "insert into loginlogs(ipAddress,TryTime) values('$ip_address','$try_time')");
                header("location: ../login.php?msg=$msg&error=wrongpwd");
            }


            exit();
        } else {
            session_start();

            $_SESSION['userid'] = $uidExists['uid'];
            $_SESSION['dept'] = $uidExists['department'];
            mysqli_query($con, "delete from loginlogs where ipAddress='$ip_address'");
            header("location: ../home.php");
            exit();
        }
    }
}

function getIPAddress()
{
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}  

// TODO: Work on matching the session ID with the ip address to prevent session ip sharing