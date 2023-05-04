<?php
    include "baza_podataka.php";


    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }

    $_SESSION['errorMessage'] = $_SESSION['message'] = null;

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $imeError = $prezimeError = $emailError = $usernameError = $passwordError = false;

    //stvaranje pomoćnih varijabli
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];
    $lozinka2 = $_POST['lozinka2'];
    $nastavnik = null;

    if (isset($_POST['nastavnik'])) {
        $nastavnik = "1";
    }

    if (empty($ime)) {
        $imeError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>IME: Niste unijeli ime!</p>";
    } else {
        $ime = test_input($ime);

        if (!preg_match("/^[a-zA-ZčČžŽšŠđĐĆć ]{2,200}$/", $ime)) {
            $imeError = true;
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>IME: Dozvoljena su samo slova i razmaci!(najmanje dva)</p>";
            $ime = "";
        }
    }

    if (empty($prezime)) {
        $prezimeError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>PREZIME: Niste unijeli prezime!</p>";
    } else {
        $prezime = test_input($prezime);

        if (!preg_match("/^[a-zA-ZčČžŽšŠđĐĆć ]{2,200}$/", $prezime)) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>PREZIME: Dozvoljena su samo slova i razmaci!(najmanje dva)</p>";
            $prezimeError = true;
            $prezime = "";
        }
    }

    if (empty($email)) {
        $emailError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>EMAIL: Niste unijeli email!</p>";
    } else {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = test_input($email);

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>EMAIL: email je nevažeći!</p>";
            $emailError = true;
            $email = "";
        } else {
            $sql = "SELECT email FROM ucenici WHERE email='$email'";
            $rezultat = $conn->query($sql);
            if ($rezultat->num_rows > 0) {
                $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>EMAIL: Email je već registriran!</p>";
                $emailError = true;
                $email = "";
            } else {
                $sql = "SELECT email FROM profesori WHERE email='$email'";
                $rezultat = $conn->query($sql);
                if ($rezultat->num_rows > 0) {
                    $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>EMAIL: Email je već registriran!</p>";
                    $emailError = true;
                    $email = "";
                } else {
                    $sql = "SELECT email FROM administratori WHERE email='$email'";
                    $rezultat = $conn->query($sql);
                    if ($rezultat->num_rows > 0) {
                        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>EMAIL: Email je već registriran!</p>";
                        $emailError = true;
                        $email = "";
                    }
                }
            }
        }
        
    }

    if (empty($korisnicko_ime)) {
        $usernameError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>KORISNIČKO IME: Niste unijeli ime!</p>";
    } else {
        $korisnicko_ime = test_input($korisnicko_ime);
        
        if (!preg_match("/^[a-z0-9]{5,20}$/", $korisnicko_ime)) {
            $usernameError = true;
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>KORISNIČKO IME: Dozvoljena su samo mala slova i bojevi!(5-20 znakova)</p>";
            $korisnicko_ime = "";
        } else {
            $sql = "SELECT username FROM ucenici WHERE username='$korisnicko_ime'";
            $rezultat = $conn->query($sql);
            if ($rezultat->num_rows > 0) {
                $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>KORISNIČKO IME: korisnik je već registriran!</p>";
                $usernameError = true;
                $korisnicko_ime = "";
            } else {
                $sql = "SELECT username FROM profesori WHERE username='$korisnicko_ime'";
                $rezultat = $conn->query($sql);
                if ($rezultat->num_rows > 0) {
                    $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>KORISNIČKO IME: korisnik je već registriran!</p>";
                    $usernameError = true;
                    $korisnicko_ime = "";
                } else {
                    $sql = "SELECT username FROM administratori WHERE username='$korisnicko_ime'";
                    $rezultat = $conn->query($sql);
                    if ($rezultat->num_rows > 0) {
                        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>KORISNIČKO IME: korisnik je već registriran!</p>";
                        $usernameError = true;
                        $korisnicko_ime = "";
                    }
                }
            }
        }
    }

    if (empty($lozinka)) {
        $passwordError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>LOZINKA: Niste unijeli lozinku!</p>";
    } else {
        $lozinka = test_input($lozinka);

        if (!preg_match("/^[a-zA-ZčČžŽšŠđĐĆć0-9!@#$%]{8,25}$/", $lozinka)) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>LOZINKA: Dozvoljena su samo slova!(8-25 znakova)</p>";
            $passwordError = true;
            $lozinka = "";
            $lozinka2 = "";
        }
    }

    if (empty($lozinka2)) {
        $passwordError = true;
        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>LOZINKA: Niste unijeli lozinku!</p>";
    } else {
        $lozinka2 = test_input($lozinka2);

        if ($lozinka != $lozinka2) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "<p>LOZINKA: Lozinke se ne podudaraju!</p>";
            $passwordError = true;
            $lozinka = "";
            $lozinka2 = "";
        }
    }

    if (!isset($_SESSION['errorMessage'])) {
        $lozinka = md5($lozinka);
        $sql = "INSERT INTO ucenici(ime, prezime, email, username, password, zahtjev_profesor)
                VALUES ('$ime', '$prezime', '$email', '$korisnicko_ime', '$lozinka', '$nastavnik')";

        if ($conn->query($sql) === false) {
            $_SESSION['errorMessage'] = "<p>Greška pri upisu podataka!</p>";
        } else {
            $_SESSION['message'] = "<p>Uspješno ste registrirani! <a target='_self' class='alert-link' href='prijava.php'>Prijavite se.</a></p>";
            $ime = null;
            $prezime = null;
            $email = null;
            $korisnicko_ime = null;
            $lozinka = null;
            $lozinka2 = null;
            $nastavnik = null;
        }
    }
?>