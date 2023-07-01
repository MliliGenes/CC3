<?php
    require("init.php");

    $id = $_SESSION["id"];
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($id)) {
        $rating = $_POST['rate'];
        $comment = t($_POST['comment']);
        $stmt = $pdo->prepare("INSERT INTO review (establishment_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_GET["id"], getSessionValue("id"), $rating, $comment]);
        unset($_POST['rate']);
        unset($_POST['comment']);
        redirect("info.php?id=".$_GET["id"]);
    }
    $rs = $pdo->prepare("SELECT * FROM services 
    WHERE id_establishment = ? ");
    $rs->execute([$_GET["id"]]);
    $servs = $rs->fetchAll(PDO::FETCH_ASSOC);
    

    $q = "SELECT * FROM establishment 
    WHERE id=?";

    $rs = $pdo->prepare($q);
    $rs->execute([$_GET["id"]]);
    $estInfo = $rs->fetch(PDO::FETCH_ASSOC);
    

    $reviews = $pdo->prepare("SELECT * FROM review JOIN users ON users.id = review.user_id WHERE establishment_id = ?");
    $reviews->execute(array($_GET["id"]));
    $allrevs = $reviews->fetchAll(PDO::FETCH_ASSOC);
    $revNum = 0;
    foreach($allrevs as $rev){
        $revNum += $rev["rating"];
    }
    $avg = $reviews->rowCount() > 0 ? number_format($revNum / $reviews->rowCount(), 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="./css/zayd2.css">
    <title>info</title>
</head>

<body>
    <div id="loader">
        <iframe src="loader.html" frameborder="0"></iframe>
    </div>
    <nav>
        <h1>Services</h1>
        <?php
        foreach($servs as $serv){
            echo "<div class='service'>
                    <div class='service-content'>
                        <div>
                            <h1>
                                {$serv['title'] }
                            </h1>
                        </div>
                        <div>
                            <p class='lead'>
                                $serv[description]
                            </p>
                        </div>
                    </div>
                </div>";
        }
        if($estInfo["user_id"] == $id){ ?>
        <div class="addService">
            <a href="<?php 
            echo sprintf('addserivice.php?idEat=%s',$estInfo['id']);
                ?>">add new service</a>
        </div>
        <?php }

        ?>
    </nav>
    <section>
        <div class='title'>
            <h1><?= $estInfo["name"] ?></h1>
            <h4 style="text-align: center; padding:1rem;">Tele : <?= $estInfo["contacts"]?></h4>
            <?php if($avg > 0){ ?>
            <h2><i class='bi bi-star-fill'></i> <?= $avg ?></h2>
            <?php }else{?>
            <h2><i class='bi bi-star'></i> 0.0</h2>
            <?php }?>


        </div>
        <div class='wrapper'>
            <div class='about'>
                <h2>Establishment Description</h2>
                <p><?=$estInfo["bio"]?></p>
            </div>
            <div class='infos'>
                <div>
                    <h2>Category :</h2> <br>
                    <p><?=$estInfo["category"]?></p><br>
                </div>
                <div>
                    <h2>Opening | Closing time :</h2> <br>
                    <p><?=$estInfo["opening_hours"]?> | <?=$estInfo["closing_hours"]?></p>
                </div>
                <div>
                    <h2>Address</h2> <br>
                    <p><?=$estInfo["address"] ?></p>
                </div>

            </div>
        </div>
        <div class="revu">
            <div class="bigrev">
                <h1>Reviews</h1>
                <?php

                        if($reviews->rowCount() == 0){
                            
                        }
                        foreach($allrevs as $rev){
                            
                            echo "
                            <div class='card'> 
                                    <div>
                                        <h1>
                                            $rev[name]
                                        </h1>
                                        <div class='revs'>
                                            <div class='rev'>
                                                <i class='bi bi-star-fill'></i> $rev[rating].0
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class='lead'>
                                            $rev[comment]
                                        </p>
                                    </div>
                            </div>
                            ";
                        }
                    ?>
            </div>
            <div class="add-rev">
                <h1>Add a review</h1>
                <div class="container">
                    <div class="post">
                        <div class="textt">Thanks for rating us!</div>
                    </div>
                    <form method="POST">
                        <div class="star-widget">
                            <input type="radio" value="5" name="rate" id="rate-5">

                            <label for="rate-5" class="fas fa-star"></label>

                            <input type="radio" value="4" name="rate" id="rate-4">

                            <label for="rate-4" class="fas fa-star"></label>

                            <input type="radio" value="3" name="rate" id="rate-3">

                            <label for="rate-3" class="fas fa-star"></label>

                            <input type="radio" value="2" name="rate" id="rate-2">

                            <label for="rate-2" class="fas fa-star"></label>

                            <input type="radio" value="1" name="rate" id="rate-1">

                            <label for="rate-1" class="fas fa-star"></label>
                            <div class="text">
                                <div class="textarea">
                                    <textarea cols="30" name="comment" required
                                        placeholder="Describe your experience.."></textarea>
                                </div>
                                <div class="btn">
                                    <button type="submit" name="btn">Post</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <script>
                // const btn = document.querySelector("button");

                // const post = document.querySelector(".post");

                // const widget = document.querySelector(".star-widget");



                // btn.onclick = () => {

                //     widget.style.display = "none";

                //     post.style.display = "block";

                // }
                </script>
            </div>
        </div>
    </section>
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