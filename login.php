<?php

session_start();
require 'php/db.php';

if(isset($_SESSION['id']) && isset($_SESSION['pwd'])){
    header("location: ./");
}

$error = '';
if(isset($_POST['register']) && isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['repwd'])){
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $repwd = $_POST['repwd'];

    if($pwd == $repwd){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $param = $db->prepare("SELECT email FROM users WHERE email = ?");
            $param->bind_param("s", $email);
            $param->execute();
            $result = $param->get_result();

            if($result->num_rows == 0){
                $hpwd = password_hash($pwd, PASSWORD_DEFAULT);
                $param = $db->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                $param->bind_param('ss', $email, $hpwd);
                $param->execute();

                $_SESSION['email'] = $email;
                $_SESSION['pwd'] = $pwd;

                header("location: ./");
            }else{
                $error = 'Email Already Exists';
            }
        }else{
            $error = 'Email Not Vaild';
        }
    }else{
        $error = 'Password Not Matched';
    }
}

if(isset($_POST['login']) && isset($_POST['email']) && isset($_POST['pwd'])){
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $param = $db->prepare("SELECT password FROM users WHERE email = ? LIMIT 1");
    $param->bind_param('s', $email);
    $param->execute();
    $result = $param->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_row();
        if(password_verify($pwd, $row[0])){
            $_SESSION['email'] = $email;
            $_SESSION['pwd'] = $pwd;

            header('location: ./');
        }else{
            $error = 'Email OR Password Incorrect';
        }
    }else{
        $error = 'Email OR Password Incorrect';
    }
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
        <div class="login_box" id="loginBox">
            <center>
                <span class="text-danger"><?php echo $error;?></span> <!-- Error Tag -->
                <form action="#" class="login" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" placeholder="email@exmaple.com" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password</label>
                        <input type="password" name="pwd" id="pwd" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" class="form-control">
                    </div>
                    <button class="btn btn-primary" name="login" type="submit">Login</button>
                </form>
                <span class="orbar">OR</span>
                <a href="#reg" id="regbtn" class="btn btn-primary">Create New Account</a>
            </center>
        </div>
        <div class="register_box" id="registerBox" hidden>
            <center>
                <span class="text-danger"><?php echo $error;?></span> <!-- Error Tag -->
                <form action="#" class="login" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" placeholder="email@exmaple.com" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Password</label>
                        <input type="password" name="pwd" id="pwd" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="repwd" class="form-label">Confirm Password</label>
                        <input type="password" name="repwd" id="repwd" placeholder="&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;&#x2022;" class="form-control">
                    </div>
                    <button class="btn btn-primary" name="register" type="submit">Register</button>
                </form>
                <a href="#log" id="logbtn" class="link">I have account</a>
            </center>
        </div>
    </div>
    <script src="/files/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>