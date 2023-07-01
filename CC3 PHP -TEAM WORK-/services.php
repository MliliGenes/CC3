<?php
            require("init.php");
            if(isset($_GET["category"])){
                $rs3 = $pdo->prepare("SELECT s.*, ROUND(AVG(r.rating),1) AS average_rating
                FROM establishment AS s
                LEFT JOIN review AS r
                ON s.id = r.establishment_id
                WHERE s.category = ? 
                GROUP BY s.id
                ORDER BY average_rating DESC");
                $rs3->execute([$_GET["category"]]);
                $ests = $rs3->fetchAll(PDO::FETCH_ASSOC);
            }else{
                    $rs = $pdo->prepare("SELECT s.*, ROUND(AVG(r.rating),1) AS average_rating
                    FROM establishment AS s
                    LEFT JOIN review AS r
                    ON s.id = r.establishment_id
                    GROUP BY s.id
                    ORDER BY average_rating DESC");
                    $rs->execute();
                    $ests = $rs->fetchAll(PDO::FETCH_ASSOC);
            }

            $id = getSessionValue("id");
            $selection1 = "SELECT * FROM users
            WHERE id = :id";
            $stmt1 = $pdo->prepare($selection1);
            $stmt1->bindValue(':id', $id);
            $stmt1->execute();
            $userInfo = $stmt1->fetch(PDO::FETCH_ASSOC);

            $selection = "SELECT * FROM establishment
            WHERE user_id = :id";
            $stmt = $pdo->prepare($selection);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/pagina de zayd.css">
    <title>Zone-Finder Establishments</title>
</head>

<body>
    <div id="loader">
        <iframe src="loader.html" frameborder="0"></iframe>
    </div>
    <div class="login">
        <?php if(isset($_SESSION['id'])){ ?>
        <a href="setup.php">Profile</a>
        <?php if($userInfo['role'] == "professional" && $stmt->rowCount() != 0){ ?>
        <a href="edit_establishment_info.php">Manage establishment</a>
        <?php }elseif($userInfo['role'] == "professional" && $stmt->rowCount() == 0){ ?>
        <a href="establishment_info.php">Add an establishment</a>
        <?php } ?>
        <?php }else{ ?>
        <a href="login.php">Login</a>
        <a href="signup.php">sign up</a>
        <?php } ?>
    </div>
    <h1>ZONE-FINDER</h1>
    <div class="cate-container">

        <a href="services.php?category=Healthcare" class="cat"><img src="./img/protection.png" alt="">
            <span>Healthcare</span> </a>

        <a href="services.php?category=Education" class="cat"><img src="./img/education.png" alt="">
            <span>Education</span> </a>


        <a href="services.php?category=Hospitality" class="cat"><img src="./img/room-service.png" alt="">
            <span>Hospitality</span> </a>

        <a href="services.php?category=Professional" class="cat"><img src="./img/portfolio.png" alt="">
            <span>Professional</span> </a>

        <a href="services.php?category=Personal" class="cat"><img src="./img/group.png" alt=""> <span>Personal</span>
        </a>
        <a href="services.php" class="all"><span>Show All</span> </a>
    </div>

    <div class="container">
        <?php
                if(count($ests) == 0){
                  ?>
        <h1 class="empty">No results found ...</h1>
        <?php
                }else{
                  ?>
        <table>
            <tr class="titles">
                <td>
                    <p>Name</p>
                </td>
                <td>
                    <p>Category</p>
                </td>
                <td>
                    <p>Opening time</p>
                </td>
                <td>
                    <p>Closing time</p>
                </td>
                <td>
                    <p>Rating</p>
                </td>
                <td>
                    <p>Creation date</p>
                </td>
            </tr><?php
                foreach($ests as $est){
                    $specail = ($est["user_id"] == $id) ? "special" : "" ;
                    
                    $rating = $est["average_rating"]
                    ? "<i class='bi bi-star-fill'></i> {$est['average_rating']}"
                    : "<i class='bi bi-star'></i> 0.0";

                    echo"<tr class='est' id='$specail' data-id='{$est['id']}'>
                            <td><p>$est[name]</p></td>
                            <td><p class='category'>$est[category]</p></td>
                            <td class='faded'><p>$est[opening_hours]</p></td>
                            <td class='faded'><p>$est[closing_hours]</p></td>
                            <td><p class='rating'> $rating </p></td>
                            <td class='date'><p>$est[date]</p></td>
                        </tr>";
                }
            }
                
            ?>
        </table>
    </div>
    <script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            var loader = document.getElementById('loader');
            loader.style.display = 'none';
        }, 800);
    });
    const links = document.querySelectorAll(".est");
    links.forEach(link => link.addEventListener('click', redirect))

    function redirect() {
        console.log(this.getAttribute("data-id"))
        window.location.href = "info.php?id=" + this.getAttribute("data-id");
    }
    </script>
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
</body>

</html>