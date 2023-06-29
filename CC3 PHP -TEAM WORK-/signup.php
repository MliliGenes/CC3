<?php
    require("init.php");
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sub"])){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $re_password = $_POST["re_password"];

        if  ( isset($username) &&
            isset($email) &&
            isset($password) &&
            isset($re_password)){
                if (empty($username)){
                    setFlash("username is required","error");
                    redirect("signup.php");
                }elseif (empty($email)){
                    
                    setFlash("email is required","error");
                    redirect("signup.php");
                }elseif (empty($password)){
                    
                    setFlash("password is required","error");
                    redirect("signup.php");
                }elseif (empty($re_password)){
                    
                    setFlash("confirmation password is required","error");
                    redirect("signup.php");
                }elseif ($re_password != $password){
                    
                    setFlash("confirmation password is wrong","warning");
                    redirect("signup.php");
                }else{
                    $selection = "SELECT * FROM users WHERE name = :name OR email = :email";
                    $stmt = $pdo->prepare($selection);
                    $stmt->bindValue(':name', $username);
                    $stmt->bindValue(':email', $email);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if ($count > 0) {
                        if ($count == 1) {
                            setFlash("This email address or username is already in use", "warning");
                        } else {
                            setFlash("This email address and username are already in use", "warning");
                        }
                        redirect("signup.php");
                    }
                    else{
                        $insertion = "INSERT INTO users(name,email,password,role) VALUES (:name, :email, :password, 'citizen')";
                        $stmt = $pdo->prepare($insertion);
                        $stmt->bindValue(':name', $username);
                        $stmt->bindValue(':email', $email);
                        $stmt->bindValue(':password', md5($password));
                        $stmt->execute();
                        $lastId = $pdo->lastInsertId();
                        setSessionValue("id",$lastId);
                        unsetSessionValue("username");
                        unsetSessionValue("email");
                        unsetSessionValue("password");
                        unsetSessionValue("re_password");
                        redirect("signup_role.php");
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
    <title>sign up</title>
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
            <h1>Sign up</h1>
            <div class="input-div">
                <label class="form-label" for="name">Username</label>
                <input class="form-input" type="text" name="username" value="<?php echo getFormInput('username','') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" type="email" name="email" value="<?php echo getFormInput('email','') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" name="password"
                    value="<?php echo getFormInput('password','') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="re_password">Confirm Password</label>
                <input class="form-input" type="password" name="re_password"
                    value="<?php echo getFormInput('re_password','') ?>">
            </div>
            <input class="form-submition" type="submit" name="sub" value="Register">

            <div class="link-div">
                <p>Already have an account ?</p> <a href="login.php" class="link">Login</a>
            </div>
        </form>
        <img src="./img/The path.jpg" alt="">
    </div>
</body>

</html>