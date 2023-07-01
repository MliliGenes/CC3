<?php
require("init.php");
isLogedIn("signup.php", $_SESSION['id']);
$id = getSessionValue("id");
$selection = "SELECT password FROM users WHERE id = :id";
$stmt = $pdo->prepare($selection);
$stmt->bindValue(":id",$id);
$stmt->execute();
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["sub"]){
    $newPassword = t($_POST['newPassword']);
    $password = t($_POST['password']);
    $confirm = t($_POST['confirm']);
    $hashedPassword = md5($password);
    
    if(empty($newPassword)){
        setFlash("New password field is empty","error");
        redirect("change_password.php");
    }elseif(empty($confirm)){
        setFlash("Confirmation password field is empty","error");
        redirect("change_password.php");
    }elseif(empty($password)){
        setFlash("Old password field is empty","error");
        redirect("change_password.php");
    }elseif($newPassword != $confirm){
        setFlash("Password and confirmation password do not match","error");
        redirect("change_password.php");
    }if($userInfo['password'] === $hashedPassword){
        if($hashedPassword == md5($newPassword)) {
            setFlash("No changes were made", "warning");
            redirect("change_password.php");
        }else{
            $hashedNewPassword = md5($newPassword);
            $update = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $pdo->prepare($update);
            $stmt->bindParam(':password', $hashedNewPassword);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            unsetSessionValue("newPassword");
            unsetSessionValue("password");
            unsetSessionValue("confirm");
            setFlash("Password changed", "success");
            redirect("setup.php");
        }
        
    }else{
        setFlash("Incorrect password" ,"error");
        redirect("change_password.php");
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
    <div id="loader">
        <iframe src="loader.html" frameborder="0"></iframe>
    </div>
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
                <h2>password</h2>
            </div>
            <div class="input-div">
                <label class="form-label" for="name">New password</label>
                <input class="form-input" type="password" name="newPassword"
                    value="<?php echo  getFormInput('newPassword') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="name">Confirm password</label>
                <input class="form-input" type="password" name="confirm" value="<?php echo  getFormInput('confirm') ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="name"> password</label>
                <input class="form-input" type="password" name="password">
            </div>
            <div class="input-div">
                <input type="submit" class="form-submition" value="Commit changes" name="sub">
            </div>
        </form>
    </div>
</body>
<style>
#loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: #262626;
    z-index: 9999;
}

#loader iframe {
    width: 100%;
    height: 100%;
}
</style>


<script>
window.addEventListener('load', function() {
    setTimeout(function() {
        var loader = document.getElementById('loader');
        loader.style.display = 'none';
    }, 800);
});
</script>

</html>