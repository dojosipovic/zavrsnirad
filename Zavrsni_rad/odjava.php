<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
?>

<html>
    <head>
        <title>Odjava</title>
        <meta charset="utf-8">
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>

    <br>
    <!--<h1 style="text-align: center;">Prijava završnih radova</h1>-->

    <?php
        
        if (isset($_SESSION['prijava'])) {
            $ime = $_SESSION['ime'];
            $prezime = $_SESSION['prezime'];
            if (session_destroy()) {
                ?>
                    <br><br>
                    <div class="d-flex justify-content-center" style="padding-top: 200px;">
                    <div style="width: 600px;" >
                    <div class="card text-center" style="padding: 10px;">
                        <div class="card-body">
                            <h2 class="card-title" >Doviđenja!</h2>
                            <p class="card-text" style="font-size: 25px;"><?php echo "Uspješno ste odjavljeni, " . $ime. " ". $prezime; ?></p>
                            <a href="ucenik.php" target="_self" class="btn btn-success" style="font-size: 20px;">U redu</a>
                        </div>
                    </div>
                    </div>
                    </div>
                <?php

                unset($_SESSION['username']);
                unset($_SESSION['lozinka']);
                unset($_SESSION['prijava']);
                unset($_SESSION['ime']);
                unset($_SESSION['prezime']);
                unset($_SESSION['tablica']);
                unset($_SESSION['home']);
                unset($_SESSION['id']);
                unset($_SESSION['profesor']);
                unset($_SESSION['predmet']);
                unset($_SESSION['tema']);
            } else {
                echo "Došlo je do pogreške! Molimo da se ponovo odjavite";
            }
        } else {
            include "prijava.php";
        }
        
    ?>

            <footer style="position: absolute; bottom: 0; width: 100%;">
                <div  class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Izradili Dominik Josipovič & Luka Šolaja 2021.
                </div>
            </footer>
    </body>
</html>