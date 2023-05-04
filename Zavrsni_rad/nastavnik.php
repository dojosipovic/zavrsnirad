<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }

    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "profesori")
    {

    include "baza_podataka.php";
    
    $id = $_SESSION['id'];
    $sql = "SELECT prijave.id FROM prijave, teme, predmeti, profesori WHERE teme.id = prijave.id_teme AND predmeti.id=teme.id_predmet
    AND profesori.id = predmeti.id_profesor AND prijave.prihvaceno=0 AND profesori.id=$id;";

    if ($conn->query($sql)) {
        $rezultat = $conn->query($sql);

        if ($rezultat->num_rows > 0) {
            $zahtjevi = $rezultat->num_rows;
        }
    }
?>

<html>
    <head>
        <title>Nastavnik</title>
        <meta charset="utf-8"/>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>
        
        <nav class="navbar sticky-top navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
            <div class="container-fluid">
                <a class="navbar-brand" href="">Navigacija</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="nastavnik.php">Početna</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="nastavnik_predmeti.php">Predmeti</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="zahtjev_zavrsni.php">Završni radovi</a>
                    </li>
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Račun
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="promijeni_lozinku.php">Promijeni lozinku</a></li>
                        <li><a class="dropdown-item" href="odjava.php">Odjavi se</a></li>
                    </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <br>
        <h1 style="text-align: center;">Prijava završnih radova</h1>
        <h2 style="text-align: center;">Nastavnici</h2>

        <br>

        <div class="d-flex justify-content-center">
        <div style="width: 600px;">
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card border-primary" style="height: 300px">
                <img src="subjects.png" style="height: 150px; width: 150px; margin-left: auto; margin-right: auto;" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><a href="nastavnik_predmeti.php " style="text-decoration: none;">Predmeti i teme</a></h5>
                    <p class="card-text">Dodajte ili uklonite vaše predmete i teme</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-primary" style="height: 300px">
                <img src="request_subjects.png" style="height: 150px; width: 150px; margin-left: auto; margin-right: auto; padding-top: 10px;" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><a href="zahtjev_zavrsni.php" style="text-decoration: none;">Završni radovi</a></h5>
                    <p class="card-text">Prihvatite ili odbijte učeničke zahtjeve za temu zavšnog rada</p>
                    <p class="card-text"><small class="text-success"><?php if (isset($zahtjevi)) echo "Trenutni zahtjevi: $zahtjevi"; else echo "Nemate zahtjeva"; ?></small></p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-primary" style="height: 250px">
                <img src="password.png" style="height: 150px; width: 150px; margin-left: auto; margin-right: auto; padding-top: 5px;" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><a href="promijeni_lozinku.php" style="text-decoration: none;">Promijeni lozinku</a></h5>
                    <p class="card-text">Promijenite svoju lozinku</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-primary" style="height: 250px">
                <img src="logout.ico" style="height: 150px; width: 145px; margin-left: auto; margin-right: auto; padding-top: 10px;" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><a href="odjava.php" style="text-decoration: none;">Odjava</a></h5>
                    <p class="card-text">Odjavi se sa svojeg računa</p>
                </div>
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
    } elseif (isset($_SESSION['home'])) {
        include $_SESSION['home'];
    } else {
        include "prijava.php";
    }
?>