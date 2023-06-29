<?php
    require("init.php");
    isLogedIn("login.php", $_SESSION['id']);
    if(isset($_GET["role"])){
        $role = $_GET["role"];
        if($role == "professional"){
            $id = getSessionValue("id");
            $update = "UPDATE users SET role = 'professional' WHERE id = :id";
            $stmt = $pdo->prepare($update);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        elseif($role == "citizen"){
            $id = getSessionValue("id");
            $update = "UPDATE users SET role = 'citizen' WHERE id = :id";
            $stmt = $pdo->prepare($update);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        redirect("setup.php");
    }
?>