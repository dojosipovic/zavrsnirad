<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    // session_start();
    if (!isset($_SESSION['prijava'])) {

    if (isset($_POST['login_btn'])) {

        include "obrada_prijava.php";

    }

    if (!isset($_SESSION['prijava']) || !isset($_SESSION['username']) || !isset($_SESSION['lozinka'])) {

?>

<html>
    <head>
        <meta charset="utf-8"/>
        <title>Zaboravljena lozinka</title>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>
        <h1 style="text-align: center; padding-top: 150px;">Prijava završnih radova</h1>

        <div class="d-flex justify-content-center">
            <div style="width: 700px;">
                <h2 style="text-align: center; ">Zaboravljena lozinka</h2>
                <br>
                <div class="card">
                    <div class="card-body">
                        Ukoliko ste zaboravili svoju lozinku molimo vas da nam se javite s Vašeg registriranog maila na mail <strong>zavrsniradofficial@gmail.com</strong>.
                        Obavezno pošaljite mail s adrese s kojom ste se registrirali u sustav te morate navesti svoje korisničko ime.
                        Možete koristiti sljedeći predložak poruke: <br><br>
                        Poštovani,<br>
                        javljam se u vezi zaboravljene lozinke. Moj username je (username) i molio/la bih vas da mi vratite lozinku. <br>
                        Lijep pozdrav,<br>
                        Ime Prezime

                        <br><br><br>
                        <div class="d-grid gap-2 col-4 ">
                            <a href='prijava.php'><button class="btn btn-primary" type="button">NATRAG</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer style="position: absolute; bottom: 0; width: 100%;">
            <div  class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                Izradili Dominik Josipovič & Luka Šolaja 2021.
            </div>
        </footer>
    </body>
</html>

<?php
    }
 } elseif (isset($_SESSION['home'])) {
    include $_SESSION['home'];
}
?>