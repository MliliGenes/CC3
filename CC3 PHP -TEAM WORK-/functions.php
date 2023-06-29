<?php


function redirect($to = '/'){
    header(sprintf('Location: %s',$to));
    exit;
}


function setFlash($msg = '',$type='info'){
    setSessionValue('flash' ,[ 
        'content' => $msg ,
        'type' => $type
    ]);
}


function getFlash(){
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;

}


function setSessionValue($key,$value){
    $_SESSION[$key] = $value;
}


function getSessionValue($key,$default = ''){
    $msg = $_SESSION[$key] ?? $default;
    return $msg;
}
function unsetSessionValue($key){
    unset($_SESSION[$key]);
}

function getFormInput($key,$default = '',$escape = true, $trim = true){
    $value = getSessionValue($key,$default);
    unset($_SESSION[$key]);
    if($escape && $trim){
        return e(t($value));
    }elseif($escape){
        return e($value);
    }elseif($trim){
        return t($value);
    }else{
        return $value;
    }

}

function e($txt){
return htmlspecialchars($txt);
}
function t($txt){
return trim($txt);
}

function isLogedIn($to = "/",$param) {
        if (!isset($param)) {
            redirect($to);
        }
}

function exportToSession($array,$to){
    foreach($array as $k => $v){
        $to[$k] = $v;
    }
}