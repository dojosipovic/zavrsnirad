<?php

    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava'])) {


    if (isset($_POST['promijeni'])) {

        include "baza_podataka.php";

        $_SESSION['errorMessage'] = null;
        $_SESSION['message'] = null;

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
    
            return $data;
        }

        $lozinka = test_input($_POST['lozinka']);
        $novaLozinka = test_input($_POST['lozinka2']);
        $ponovLozinka = test_input($_POST['lozinka3']);

        if (empty($lozinka) || empty($novaLozinka) || empty($ponovLozinka)) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "Ispunite sva polja!";
        } elseif (!preg_match("/^[a-zA-ZčČžŽšŠđĐĆć0-9!@#$%]{8,25}$/", $novaLozinka)) {
            $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "Dozvoljena su samo slova i brojke!(8-25 znakova)";
        } else {
            $tablica = $_SESSION['tablica'];
            $lozinka = md5($lozinka);
            $novaLozinka = md5($novaLozinka);
            $ponovLozinka = md5($ponovLozinka);

            $sql = "SELECT password FROM $tablica WHERE password='$lozinka' AND username='".$_SESSION['username']."'";
            if ($conn->query($sql) === false) {
                echo "Greška";
                die();
            }

            $rezultat = $conn->query($sql);

            if ($rezultat->num_rows === 1) {
                $redak = $rezultat->fetch_assoc();
                if ($redak['password'] === $lozinka) {
                    if ($novaLozinka === $ponovLozinka) {
                        $sql = "UPDATE ".$_SESSION['tablica']." SET password='$novaLozinka' WHERE password='$lozinka' AND username='".$_SESSION['username']."'";
                        if ($conn->query($sql) === false) {
                            echo "Greška";
                            die();
                        } else {
                            $_SESSION['message'] = "Lozinka je uspješno promijenjena!";
                        }
                    } else {
                        $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "Nove lozinke se ne podudaraju!";
                    }
                } else {
                    $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "Trenutna lozinka nije ispravna!";
                }
            } else {
                $_SESSION['errorMessage'] = $_SESSION['errorMessage'] . "Trenutna lozinka nije ispravna!";
            }
        }

    }

?>

<html>
    <head>
        <title>Promjena lozinke</title>
        <meta charset="utf-8" />
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>



        <div class="d-flex justify-content-center">
        <div style="width: 500px;">
        <form class="px-4 py-3 mx-5" action="promijeni_lozinku.php" method="POST">
        <?php
            if (isset($_SESSION['errorMessage'])) {
                echo "<div class='alert alert-danger' role='alert'>".$_SESSION['errorMessage']."</div>";
                unset($_SESSION['errorMessage']);
            }
            elseif (isset($_SESSION['message'])) {
                echo "<div class='alert alert-success' role='alert'>".$_SESSION['message']."</div>";
                unset($_SESSION['message']);
            }
        ?>
            <h2 style="text-align: center;">Promjena lozinke</h1>
            <div class="mb-3">
                <input type="password" name="lozinka" class="form-control" placeholder="Trenutna lozinka..." autocomplete="off"/>
            </div>
            <div class="mb-3">
                <input type="password" name="lozinka2" class="form-control" id="exampleDropdownFormPassword1" placeholder="Nova lozinka..." autocomplete="off"/>
            </div>
            <div class="mb-3">
                <input type="password" name="lozinka3" class="form-control" id="exampleDropdownFormPassword1" placeholder="Ponovi novu lozinku..." autocomplete="off"/>
            </div>
                    
            <div class="d-grid gap-2 col-5 mx-auto">
                <input type="submit" class="btn btn-outline-success" name="promijeni" value="Promijeni"/>
            </div>
            
            
        </form>
        </div>
        </div>

        
        <div class="d-grid gap-2 col-3 mx-auto">
            <a href=<?php echo $_SESSION['home'];?>><button class="btn btn-primary" type="button">NATRAG</button></a>
        </div>

            <footer style="position: absolute; bottom: 0; width: 100%;">
                <div  class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Izradili Dominik Josipovič & Luka Šolaja 2021.
                </div>
            </footer>

    </body>
</html>

<?php
    } else {
        include "prijava.php";
    }
?>