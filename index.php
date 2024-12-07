<?php

session_start();
require 'php/db.php';

if(isset($_SESSION['email']) && isset($_SESSION['pwd'])){
    $email = $_SESSION['email'];
    $pwd = $_SESSION['pwd'];

    $param = $db->prepare("SELECT id, space, last_update, password FROM `users` WHERE email = ? LIMIT 1");
    $param->bind_param('s', $email);
    $param->execute();

    $result = $param->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_row();
        $usrid = $row[0];
        $usrSpace = $row[1];
        $usrLastUpdate = $row[2];
        if(!password_verify($pwd, $row[3])){
            header("location:logout.php");
        }
    }else{
        header("location:logout.php");
    }
}else{
    header("location: login.php");
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();

    header("location: login.php");
}

$done = '';
if(isset($_POST['space'])){
    $space = $_POST['space'];
    $time = time();
    $param = $db->prepare("UPDATE users SET space = ?, last_update = ? WHERE id = ?");
    $param->bind_param('sii', $space, $time, $usrid);
    $param->execute();

    $usrSpace = $space;
    $usrLastUpdate = $time;
    $done = 'Your Space has been saved';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myText</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/files/style.css">
</head>
<body>
    <nav class="navbar bg-primary navbar-dark ">
        <div class="container-md">
            <a class="navbar-brand" href="/">myText</a>
        </div>
    </nav>
    <noscript>
        <center>
            <h1 class="text-danger">Please Enable JavaScript</h1>
        </center>
    </noscript>
    <div class="container">
        <div class="editor">
            <span class="text-success"><?php echo $done?></span>
            <form method="post">
                <button class="btn btn-primary" type="submit">Save Space</button>
                <a href="/?logout" class="btn btn-outline-secondary outbtn" type="submit">Log Out</a>
                <div class="mb-3">
                    <label for="space">Your Space</label>
                    <textarea class="form-control space" name="space" placeholder="Write and Save..." id="floatingTextarea"><?php echo $usrSpace;?></textarea>
                    <span class="text-secondary" id="updatetime"><?php echo $usrLastUpdate;?></span>
                </div>
            </form>
        </div>
    </div>
    <script src="/files/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>