<?php
require("init.php");
isLogedIn("signup.php", $_SESSION['id']);
$id = getSessionValue("id");
$selection = "SELECT  email, password FROM users WHERE id = :id";
$stmt = $pdo->prepare($selection);
$stmt->bindValue(":id",$id);
$stmt->execute();
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["sub"]){
    $newEmail = t($_POST['newEmail']);
    $password = t($_POST['password']);
    $hashedPassword = md5($password);
    
    if(empty($newEmail)){
        setFlash("New email field is empty","error");
        redirect("change_email.php");
    }elseif(empty($password)){
        setFlash("Password field is empty","error");
        redirect("change_email.php");
    }elseif($newEmail == $userInfo['email']){
        setFlash("No changes were made","error");
        redirect("change_email.php");
    }
    if($userInfo['password'] === $hashedPassword){
        $selection = "SELECT * FROM users WHERE  email = :email";
        $stmt = $pdo->prepare($selection);
        $stmt->bindValue(':email', $newEmail);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            setFlash("This email is already in use", "warning");
            redirect("change_email.php");
        }else{
            $update = "UPDATE users SET email = :email WHERE id = :id";
            $stmt = $pdo->prepare($update);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            unsetSessionValue("password");
            unsetSessionValue("newEmail");
            unsetSessionValue("newPassword");
            unsetSessionValue("confirm");
            setFlash("Email changed", "success");
            redirect("setup.php");
        }
        
    }else{
        setFlash("Incorrect password" ,"error");
        redirect("change_email.php");
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/setup.css">

    <title>change Email</title>
</head>

<body>
    <div class="container">
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
        <img src="./img/Patternatic.jpg" alt="" class="banner">
        <form method="POST" class="form">
            <div class="greating">
                <h1>Change your</h1>
                <h2>email</h2>
            </div>
            <div class="input-div">
                <label class="form-label" for="name">Old email</label>
                <input class="form-input" type="email" name="oldEmail" value="<?php echo $userInfo['email']  ?>"
                    readonly>
            </div>
            <div class="input-div">
                <label class="form-label" for="name">New email</label>
                <input class="form-input" type="email" name="newEmail" value="<?php echo  getFormInput('newEmail') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="name">Password</label>
                <input class="form-input" type="password" name="password"
                    value="<?php echo  getFormInput('password') ?>">
            </div>
            <div class="input-div">
                <input type="submit" class="form-submition" value="Commit changes" name="sub">
            </div>
        </form>
    </div>
</body>

</html>