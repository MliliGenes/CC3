<?php
    require("init.php");
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sub"])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $hashedPassword = md5($password);
        if (isset($email) &&
        isset($password)){
                if (empty($email)){
                    setFlash("email is required","error");
                    redirect("login.php");
                }elseif (empty($password)){
                    setFlash("password is required","error");
                    redirect("login.php");
                }else{
                    $selection = "SELECT * FROM users
                    WHERE email = :email AND password = :password";
                    $stmt = $pdo->prepare($selection);
                    $stmt->bindValue(':email', $email);
                    $stmt->bindValue(':password', $hashedPassword);
                    $stmt->execute();
                    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                    $count = $stmt->rowCount();
                    if ($count == 1) {
                        setSessionValue("id",$userInfo['id']);
                        unsetSessionValue("username");
                        unsetSessionValue("email");
                        unsetSessionValue("password");
                        unsetSessionValue("re_password");
                        redirect("index.php");
                    }elseif($count == 0){
                        setFlash("Email or password wrong","warning");
                        redirect("login.php");
                    }
                }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <form method="POST" class="form">
            <?php
            $flash = getFlash();
            if($flash){  
                ?>
            <div class="notifs <?php echo $flash["type"];?>">
                <?php
                    echo $flash["content"];
            ?>
            </div>
            <?php
                }
            ?>
            <h1>Login</h1>
            <div class="input-div log">
                <label class="form-label" for="name">Email</label>
                <input class="form-input" type="text" name="email" value="<?php echo getFormInput('email','') ?>">
            </div>
            <div class="input-div log">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" name="password"
                    value="<?php echo getFormInput('password','') ?>">
            </div>
            <input class="form-submition" type="submit" name="sub" value="Login">

            <div class="link-div">
                <p>You don't have an account ?</p><a href="signup.php" class="link">Sign up</a>
            </div>
        </form>
        <img src="./img/The path.jpg" alt="">
    </div>
</body>

</html>