<html>
    <head>
        <title>Prijava završnih radova</title>
        <meta charset="utf-8">
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>
    

    <?php

        include "baza_podataka.php";

        $username = $_SESSION['username'];
        $password = $_SESSION['lozinka'];
        $password = md5($password);

        $sql = "SELECT id, ime, prezime FROM ucenici WHERE username='$username' AND password='$password'";
        if ($conn->query($sql) === false) {
            echo "Greška";
            die();
        }

        $rezultat = $conn->query($sql);
        

        if ($rezultat->num_rows === 1) {
            $redak = $rezultat->fetch_assoc();
            ?>
                <br>
                <!--<h1 style="text-align: center;">Prijava završnih radova</h1>-->
                <br><br>
                <div class="d-flex justify-content-center" style="padding-top: 200px;">
                <div style="width: 600px;">
                <div class="card text-center" style="padding: 10px;">
                    <div class="card-body">
                        <h2 class="card-title">Dobrodošli!</h2>
                        <p class="card-text" style="font-size: 25px;"><?php echo $redak['ime']. " ".$redak['prezime'] . ", " . "učenik"; ?></p>
                        <a href="ucenik.php" target="_self" class="btn btn-success" style="font-size: 20px;">Nastavi</a>
                    </div>
                </div>
                </div>
                </div>
            <?php
            $_SESSION['prijava'] = 1;
            $_SESSION['ime'] = $redak['ime'];
            $_SESSION['prezime'] = $redak['prezime'];
            $_SESSION['tablica'] = "ucenici";
            $_SESSION['home'] = "ucenik.php";
            $_SESSION['id'] = $redak['id'];

            unset($_SESSION['errorMessage']);
        } else {
            $sql = "SELECT id, ime, prezime FROM profesori WHERE username='$username' AND password='$password'";
            if ($conn->query($sql) === false) {
                echo "Greška";
                die();
            }

            $rezultat = $conn->query($sql);

            if ($rezultat->num_rows === 1) {
                $redak = $rezultat->fetch_assoc();
                ?>
                    <br>
                    <!--<h1 style="text-align: center;">Prijava završnih radova</h1>-->
                    <br><br>
                    <div class="d-flex justify-content-center" style="padding-top: 200px;">
                    <div style="width: 600px;">
                    <div class="card text-center" style="padding: 10px;">
                        <div class="card-body">
                            <h2 class="card-title">Dobrodošli!</h2>
                            <p class="card-text" style="font-size: 25px;"><?php echo $redak['ime']. " ".$redak['prezime'] . ", " . "profesor"; ?></p>
                            <a href="nastavnik.php" target="_self" class="btn btn-success" style="font-size:20px;">Nastavi</a>
                        </div>
                    </div>
                    </div>
                    </div>
                <?php
                $_SESSION['prijava'] = 1;
                $_SESSION['ime'] = $redak['ime'];
                $_SESSION['prezime'] = $redak['prezime'];
                $_SESSION['tablica'] = "profesori";
                $_SESSION['home'] = "nastavnik.php";
                $_SESSION['id'] = $redak['id'];

                unset($_SESSION['errorMessage']);
            } else {
                $sql = "SELECT id, ime, prezime FROM administratori WHERE username='$username' AND password='$password'";
                if ($conn->query($sql) === false) {
                    echo "Greška";
                    die();
                }

                $rezultat = $conn->query($sql);

                if ($rezultat->num_rows === 1) {
                    $redak = $rezultat->fetch_assoc();
                    ?>
                        <br>
                       <!-- <h1 style="text-align: center;">Prijava završnih radova</h1>-->
                        <br><br>
                        <div class="d-flex justify-content-center" style="padding-top: 200px;">
                        <div style="width: 600px;">
                        <div class="card text-center" style="padding: 10px;">
                            <div class="card-body">
                                <h2 class="card-title">Dobrodošli!</h2>
                                <p class="card-text" style="font-size: 25px;"><?php echo $redak['ime']. " ".$redak['prezime'] . ", " . "administrator"; ?></p>
                                <a href="admin.php" target="_self" class="btn btn-success" style="font-size:20px;">Nastavi</a>
                            </div>
                        </div>
                        </div>
                        </div>
                    <?php
                    $_SESSION['prijava'] = 1;
                    $_SESSION['ime'] = $redak['ime'];
                    $_SESSION['prezime'] = $redak['prezime'];
                    $_SESSION['tablica'] = "administratori";
                    $_SESSION['home'] = "admin.php";
                    $_SESSION['id'] = $redak['id'];

                    unset($_SESSION['errorMessage']);
                } else {
                    $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "LOZINKA: Netočna lozinka ili username!";
                    unset($_SESSION['username']);
                    unset($_SESSION['lozinka']);
                    session_destroy();
                    $password = "";
                }
            }
        }

    ?>

            <footer style="position: absolute; bottom: 0; width: 100%;">
                <div  class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Izradili Dominik Josipovič & Luka Šolaja 2021.
                </div>
            </footer>

    </body>

</html>