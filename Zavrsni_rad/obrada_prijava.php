<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    // session_start();

    $_SESSION['errorMessage'] = null;

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $korisnicko_ime = $_POST['korisnicko_ime'];
    $korisnicko_ime = test_input($korisnicko_ime);
    $lozinka = $_POST['lozinka'];

    if (empty($korisnicko_ime)) {
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "KORISNIČKO IME: Niste unijeli ime!";
    } elseif (empty($lozinka)) {
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "LOZINKA: Niste unijeli lozinku!";
    } elseif (!isset($_SESSION['errorMessage'])) {
        $lozinka = test_input($lozinka);

        $_SESSION['lozinka'] = $lozinka;
        $_SESSION['username'] = $korisnicko_ime;
        
        include "provjera.php";
    }

?>