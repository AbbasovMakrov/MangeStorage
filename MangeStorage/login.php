<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login Form Tutorial</title>
    <style>
        .login-form {
            width: 300px;
            margin: 0 auto;
            font-family: Tahoma, Geneva, sans-serif;
        }
        .login-form h1 {
            text-align: center;
            color: #ff0025;
            font-size: 24px;
            padding: 20px 0 20px 0;
        }
        .login-form input[type="password"],
        .login-form input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 1px solid #dddddd;
            margin-bottom: 15px;
            box-sizing:border-box;
        }
        .login-form input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #ff0025;
            border: 0;
            box-sizing: border-box;
            cursor: pointer;
            font-weight: bold;
            color: #ffffff;
        }
    </style>
</head>
<body>
<div class="login-form">
    <h1>Login Form</h1>
    <form  method="post">
        <input type="text" name="user" placeholder="Username">
        <input type="password" name="pass" placeholder="Password">
        <input  name ='sub' type="submit">
    </form>
    <?php
    require_once "Includs/Classes.php";
    if (isset($_POST['sub']))
    {
        $login = new SignIn($_POST['user'],$_POST['pass']);
    }
    ?>
</div>
</body>
</html>