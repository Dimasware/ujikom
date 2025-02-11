<?php
function isLoggedIn(){
    return isset($_SESSION['user']);
}

function isAdmin(){
    return (isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1);
}
?>