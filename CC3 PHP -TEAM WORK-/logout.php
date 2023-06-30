<?php
    require("init.php");
    session_unset();
    session_destroy();
    redirect("login.php");
?>