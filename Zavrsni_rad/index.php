<?php

    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    
    if (isset($_SESSION['prijava'])) {
        include "provjera.php";
    } else {
        session_destroy();
        include "prijava.php";
    }

?>