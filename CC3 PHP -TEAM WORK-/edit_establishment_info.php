<?php
    require("init.php");
    isLogedIn("login.php", $_SESSION['id']);
    $id = getSessionValue("id");
    $selection = "SELECT * FROM users
    WHERE id = :id";
    $stmt = $pdo->prepare($selection);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    if($userInfo['role'] != "professional"){
        redirect('login.php');
    }else{
        $selection = "SELECT * FROM establishment
        WHERE user_id = :id";
        $stmt = $pdo->prepare($selection);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $estInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() == 0 ){
            redirect('establishment_info.php');
        }
        $regexTele = "/^0[5-7][0-9]{8}$/";
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sub"])){
            $name = $_POST["name"];
            $address = $_POST["address"];
            $city = $_POST["city"];
            $tel = $_POST["tel"];
            $bio = $_POST["bio"];
            $opening = $_POST["opening"];
            $closing = $_POST["closing"]; 
            $category = $_POST["category"];
            if( isset($name) &&
                isset($address) &&
                isset($city) &&
                isset($tel) &&
                isset($bio) &&
                isset($opening) &&
                isset($closing) &&
                isset($category)){
                    $openingTime = strtotime($opening);
                    $closingTime = strtotime($closing);
                if (empty($name)){
                    setFlash("Name is required","error");
                    redirect("edit_establishment_info.php");
                }elseif (empty($city)){
                    setFlash("City is required","error");
                    redirect("establishment_info.php");
                }elseif (empty($address)){
                    setFlash("Address is required","error");
                    redirect("edit_establishment_info.php");
                }elseif (empty($tel)){
                    setFlash("Phone number is required","error");
                    redirect("edit_establishment_info.php");
                }elseif(!preg_match($regexTele,$tel)){
                    setFlash("the phone number shoud be in this format 0600000000 ","error");
                    redirect("edit_establishment_info.php");
                }elseif (empty($bio)){
                    setFlash("Bio is required","error");
                    redirect("edit_establishment_info.php");
                }elseif (empty($opening)){
                    setFlash("Opening hour is required","error");
                    redirect("edit_establishment_info.php");
                }elseif (empty($closing)){
                    setFlash("Closing hour is required","error");
                    redirect("edit_establishment_info.php");
                }elseif ($closingTime <= $openingTime){
                    setFlash("The closing hour should be after the opening","error");
                    redirect("edit_establishment_info.php");
                }elseif ($name == $estInfo["name"]&&
                $address == $estInfo["address"]&&
                $city == $estInfo["city"]&&
                $opening == $estInfo["opening_hours"]&&
                $closing == $estInfo["closing_hours"]&&
                $tel == $estInfo["contacts"]&&
                $category == $estInfo["category"]&&
                $bio == $estInfo["bio"])
                {
                    setFlash("No changes were made","error");
                    redirect("edit_establishment_info.php");
                }else{
                    $update = "UPDATE `establishment` SET
                    `name` = ?,
                    `address` = ?,
                    `city` = ?,
                    `opening_hours` = ?,
                    `closing_hours` = ?,
                    `contacts` = ?,
                    `category` = ?,
                    `bio` = ?
                    WHERE `user_id` = ?";
                        $stmt = $pdo->prepare($update);
                        if ($stmt->execute(
                                array(
                                    $name,
                                    $address,
                                    $city,
                                    $opening,
                                    $closing,
                                    $tel,
                                    $category,
                                    $bio,
                                    $id
                                )
                            )) {
                            setFlash("Establishment info are changed", "success");
                            redirect("index.php");
                        }
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
    <link rel="stylesheet" href="./css/establishment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <title>update Establishment info</title>
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
        <form method="POST" class="form">
            <a href="index.php" class="back"><i class="fa-solid fa-xmark"></i></a>
            <div class="greating">
                <h1>Establishment</h1>
                <h2>informations</h2>
            </div>
            <div class="mini-container">
                <div class="wrapper">
                    <div class="input-div">
                        <label class="form-label" for="name">Establishment name</label>
                        <input class="form-input" type="text" name="name" value="<?php echo  $estInfo['name'] ?>">
                    </div>
                    <div class="input-div">
                        <label class="form-label">city</label>
                        <input class="form-input" type="text" class="city" name="city"
                            value="<?php echo $estInfo['city']  ?>">
                        <ul data-seg></ul>
                    </div>
                    <div class="input-div">
                        <label class="form-label" for="name">Contact</label>
                        <input class="form-input " type="tel" name="tel" maxlength="10"
                            value="<?php echo  $estInfo['contacts'] ?>">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                </div>
                <div class="wrapper">
                    <div class="text-div">
                        <label class="form-label">Bio</label>
                        <textarea class="form-text" name="bio" id=""><?php echo  $estInfo['bio'] ?></textarea>
                    </div>
                    <div class="text-div">
                        <label class="form-label">address</label>
                        <textarea class="form-text" name="address" id=""><?php echo  $estInfo['address'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="input-div row">
                <label class="form-label" for="name">Category</label>
                <select name="category" class="form-input">
                    <option value="Healthcare" <?= ("Healthcare" == $estInfo['category'])? "selected" : "" ?>>Healthcare
                    </option>
                    <option value="Education" <?= ("Education" == $estInfo['category'])? "selected" : "" ?>>Education
                    </option>
                    <option value="Hospitality" <?= ("Hospitality" == $estInfo['category'])? "selected" : "" ?>>
                        Hospitality</option>
                    <option value="Professional" <?= ("Professional" == $estInfo['category'])? "selected" : "" ?>>
                        Professional</option>
                    <option value="Personal" <?= ("Personal" == $estInfo['category'])? "selected" : "" ?>>Personal
                    </option>
                </select>
            </div>
            <div class="input-div row">
                <label class="form-label" for="open">Opening hour</label>
                <input class="form-input" type="time" name="opening" value="<?php echo  $estInfo['opening_hours'] ?>">
            </div>
            <div class="input-div row">
                <label class="form-label" for="close">Closing hour</label>
                <input class="form-input" type="time" name="closing" value="<?php echo  $estInfo['closing_hours'] ?>">
            </div>
            <div class="input-div">
                <input type="submit" class="form-submition" value="Save changes" name="sub">
            </div>
        </form>
    </div>
    <script src="./js/countries.js"></script>
</body>

</html>