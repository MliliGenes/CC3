<?php 

session_start();

require('db.php');
require("functions.php");

foreach($_POST as $inputName=>$inputValue){
    setSessionValue($inputName,$inputValue);
}