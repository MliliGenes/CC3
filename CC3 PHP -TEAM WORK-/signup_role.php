<?php
require("init.php");
isLogedIn("login.php",$_SESSION["id"])
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>sign up</title>
</head>

<body>

    <div class="container">
        <form method="POST" class="form">
            <h1>Client or Professional ?</h1>
            <div class="input-div">
                <a href="role_setter.php?role=citizen" class="card">
                    I'm a client looking for services.
                </a>
            </div>
            <div class="input-div?">
                <a href="role_setter.php?role=professional" class="card">
                    I'm a business owner managing my establishment.
                </a>
            </div>
            <div class="link-div">
                <p>Already have an account ?</p> <a href="login.php" class="link">Login</a>
            </div>
        </form>
        <img src="./img/role.jpg" alt="">
    </div>
</body>

</html>