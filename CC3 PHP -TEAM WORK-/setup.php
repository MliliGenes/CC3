<?php
    require("init.php");
    isLogedIn("login.php", $_SESSION['id']);
        $id = getSessionValue("id");
        $selection = "SELECT name, email, role FROM users WHERE id = :id";
        $stmt = $pdo->prepare($selection);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["sub"]){
            $username = t($_POST['username']);
            if($username==$userInfo["name"]){
                setFlash("No changes were made","error");
                redirect("setup.php");
            }elseif(t($username) == ""){
                setFlash("Username field is required","error");
                redirect("setup.php");
            }else{
                $selection_name = "SELECT * FROM users WHERE name = :name";
                $stmt = $pdo->prepare($selection_name);
                $stmt->bindValue(':name', $username);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count > 0) {
                    setFlash("This username is already in use", "warning");
                    redirect("setup.php");
                }else{
                    $id = getSessionValue("id");
                    $update = "UPDATE users SET name = :name WHERE id = :id";
                    $stmt = $pdo->prepare($update);
                    $stmt->bindParam(':name', $username);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    setFlash("Username is changed", "success");
                    redirect("setup.php");
                }
            }
        }

   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/setup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Account setup</title>
</head>

<body>
    <a href="logout.php" class="logout">Log out</a>
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
        <img src="./img/banner.jpg" alt="" class="banner">
        <form method="POST" class="form">
            <a href="index.php" class="back"><i class="fa-solid fa-xmark"></i></a>
            <div class="greating">
                <h1>Welcome !</h1>
                <h2><?php echo $userInfo["name"] ?></h2>
            </div>
            <div class="input-div">
                <label class="form-label" for="name">Username</label>
                <input class="form-input" type="text" name="username" value="<?php
                echo $userInfo["name"];
                ?>">
            </div>
            <div class="input-div">
                <label class="form-label" for="name">Email</label>
                <input class="form-input" type="email" name="email" value="<?php
                echo $userInfo["email"];
                ?>" readonly>
            </div>
            <div class="input-div">
                <a href="./change_email.php" class="update">Change email</a>
                <a href="./change_password.php" class="update">Change password</a>
            </div>
            <div class="input-div">
                <input type="submit" class="form-submition" value="Commit changes" name="sub">
            </div>
        </form>
    </div>
</body>

</html>