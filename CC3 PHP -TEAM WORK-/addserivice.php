<?php
require("init.php");
isLogedIn("index.php");

if( ! isset($_GET["idEat"]) ) {
    redirect();
}

$rs = $pdo->prepare("SELECT * FROM establishment  WHERE id = ?");
$rs->execute([$_GET["idEat"]]);

$establishment = $rs->fetch();


//allow authorized users only ...
if( 
    ! isset($establishment) || 
    (int)$establishment['user_id'] !== (int)getSessionValue('id') 
){
    redirect();
}



if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["sub"]){
    $title = t($_POST['title']);
    $description = t($_POST['description']);
    if(empty($title)){
        setFlash("title is required","error");
        redirect(sprintf("addserivice.php?idEat=%s",(int)$_GET["idEat"]));
    }elseif(empty($description)){
        setFlash("description is required","error");
        redirect(sprintf("addserivice.php?idEat=%s",(int)$_GET["idEat"]));
    }
        $update = "INSERT INTO services VALUES (?,?,?)";
        $stmt = $pdo->prepare($update);
        $stmt->execute(array((int)$_GET["idEat"], $title ,$description ));
        unsetSessionValue("title");
        unsetSessionValue("description");
        redirect(sprintf("info.php?id=%s",(int)$establishment['id']));
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/setup.css">

    <title>Add new service</title>
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
                <h1>Add new </h1>
                <h2>service</h2>
            </div>
            <div class="input-div">
                <label class="form-label" for="name">service title</label>
                <input class="form-input" type="text" name="title" value="<?php echo  getFormInput('title') ?>">
            </div>
            <div class="text-div">
                <label class="form-label" for="name">service description</label>
                <textarea name="description" class="form-text"><?php echo  getFormInput('description') ?></textarea>
            </div>
            <div class="input-div">
                <input type="submit" class="form-submition" value="add sevice" name="sub">
            </div>
        </form>
    </div>
</body>

</html>