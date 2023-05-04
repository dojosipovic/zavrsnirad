<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (!isset($_SESSION['prijava']))
    {

    if (isset($_POST['register_btn'])) {

        include "obrada_registracija.php";

    }
?>


<html>
    <head>
        <meta charset="utf-8"/>
        <title>Registracija</title>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <br>
        <h1 style="text-align: center;">Prijava završnih radova</h1>
        
        <div class="d-flex justify-content-center">
            <div style="width: 500px;">
                <form class="px-4 py-3 mx-5" action="registracija.php" method="POST" autocomplete="off">
                <?php
                    if (isset($_SESSION['message'])) {
                        echo "<div class='alert alert-success' role='alert'>".$_SESSION['message']."</div>";
                        unset($_SESSION['message']);
                        session_destroy();
                    }
                    elseif (isset($_SESSION['errorMessage'])) {
                        echo "<div class='alert alert-danger' role='alert'>".$_SESSION['errorMessage']."</div>";
                        unset($_SESSION['errorMessage']);
                        session_destroy();
                    }
                ?>
                    <h2 style="text-align: center;">Registracija</h2>
                    <br>
                    <div class="mb-3"><input class="form-control" type="text" name="ime" value="<?php if (isset($ime)) echo $ime; ?>" placeholder="Unesite Vaše ime..." autocomplete="off"/></div>
                    <div class="mb-3"><input class="form-control" type="text" name="prezime" value="<?php if (isset($prezime)) echo $prezime; ?>" placeholder="Unesite Vaše prezime..." autocomplete="off"/></div>
                    <div class="mb-3"><input class="form-control" type="email" name="email" value="<?php if (isset($email)) echo $email; ?>" placeholder="Unesite E-mail..." autocomplete="off"/></div>
                    <div class="mb-3"><input class="form-control" type="text" name="korisnicko_ime" value="<?php if (isset($korisnicko_ime)) echo $korisnicko_ime; ?>" placeholder="Unesite korisničko ime..." autocomplete="off"/></div>
                    <div class="mb-3"><input class="form-control" type="password" name="lozinka" value="<?php if (isset($lozinka)) echo $lozinka; ?>" placeholder="Unesite lozinku..." autocomplete="off"/></div>
                    <div class="mb-3"><input class="form-control" type="password" name="lozinka2" value="<?php if (isset($lozinka2)) echo $lozinka2; ?>" placeholder="Ponovite lozinku..." autocomplete="off"/></div>
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Nastavnički račun?</label>
                        <input class="form-check-input" type="checkbox" name="nastavnik" id="flexSwitchCheckDefault" unchecked <?php if (isset($nastavnik)) echo "checked"; ?>>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-outline-success" name="register_btn" value="Registriraj se"/>
                    <br>
                    <br>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="prijava.php" target="_self">Već imate korisnički račun? Prijavite se.</a>
                    <br>
                </form>
            </div>
        </div>

        
        <div class="registracija">
            
            
        </div>
    </body>

            <footer style="position: absolute; bottom: 0; width: 100%;">
                <div  class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Izradili Dominik Josipovič & Luka Šolaja 2021.
                </div>
            </footer>


</html>

<?php
    } elseif (isset($_SESSION['home'])) {
        include $_SESSION['home'];
    }
?>