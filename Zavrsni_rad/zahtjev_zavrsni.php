<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "profesori")
    {

    include "baza_podataka.php";

    if(isset($_POST['prihvati_btn'])) {
        $id = $_POST['id'];
        $sql = "UPDATE prijave SET prihvaceno=1 WHERE id=$id;";
        $conn->query($sql);
    } elseif (isset($_POST['odbij_btn']) || isset($_POST['obrisi_btn'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM prijave WHERE id=$id;";
        $conn->query($sql);
    }
?>

<html>
    <head>
        <title>Završni radovi</title>
        <meta charset="utf-8">
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
        <h1 style="text-align: center;">Zahtjevi završnih radova</h1>

        <br>
        <h2 style="text-align: center;">Primljeni zahtjevi</h2>

        <div class="d-flex justify-content-center">
            <div style="width: 600px;">
                <?php
                    $sql = "SELECT
                            ucenici.ime,
                            ucenici.prezime,
                            ucenici.username,
                            teme.naziv AS tema_naziv,
                            predmeti.naziv AS predmet_naziv,
                            prijave.datum,
                            prijave.vrijeme,
                            prijave.id
                            FROM prijave
                            INNER JOIN teme ON prijave.id_teme = teme.id
                            INNER JOIN ucenici ON ucenici.id = prijave.id_ucenik
                            INNER JOIN predmeti ON predmeti.id = teme.id_predmet
                            WHERE prijave.prihvaceno = 0
                            ORDER BY datum DESC , vrijeme DESC;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $br_red = 1;

                    if ($rezultat->num_rows > 0) {
                        echo "<table class='table table table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th scope='col'>#</th>";
                        echo "<th scope='col'>Učenik</th>";
                        echo "<th scope='col'>Tema</th>";
                        echo "<th scope='col'>Predmet</th>";
                        echo "<th scope='col'>Datum</th>";
                        echo "<th scope='col'>Vrijeme</th>";
                        echo "<th scope='col'>Opcije</th>";
                        echo "</tr>";
                        echo "</thead>";
                        while($redak = $rezultat->fetch_assoc()) {
                            $datum = date_create(date($redak['datum']));
                            $datum = date_format($datum,"d.m.Y");

                            $vrijeme = date_create(date($redak['vrijeme']));
                            $vrijeme = date_format($vrijeme,"H:i:s");
                            ?>
                            <tbody>
                            <tr>
                                <th scope="row"><?php echo $br_red; ?></th>
                                <td><?php echo $redak["ime"] . " " . $redak["prezime"] . " (" . $redak["username"] . ")"; ?></td>
                                <td><?php echo $redak["tema_naziv"]; ?></td>
                                <td><?php echo $redak["predmet_naziv"]; ?></td>
                                <td><?php echo $datum; ?></td>
                                <td><?php echo $vrijeme; ?></td>
                                <td><div class="d-flex justify-content-center"><form action="zahtjev_zavrsni.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="submit" value="Prihvati" class="btn btn-outline-success btn-sm" name="prihvati_btn"></form><div style="width: 5px;"></div><form action="zahtjev_zavrsni.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="submit" class="btn btn-outline-danger btn-sm" value="Odbij" name="odbij_btn"></form></div></td>
                            </tr>
                            <?php
                            $br_red += 1;
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Nemate zahtjeva za završne radove!</div>";
                    }
                ?>
            </div>
        </div>

        <br><br>

        <h2 style="text-align: center;">Potvrđeni zahtjevi</h2>

        <div class="d-flex justify-content-center">
            <div style="width: 600px;">
                <?php
                $sql = "SELECT
                ucenici.ime,
                ucenici.prezime,
                ucenici.username,
                teme.naziv AS tema_naziv,
                predmeti.naziv AS predmet_naziv,
                prijave.datum,
                prijave.vrijeme,
                prijave.id
                FROM prijave
                INNER JOIN teme ON prijave.id_teme = teme.id
                INNER JOIN ucenici ON ucenici.id = prijave.id_ucenik
                INNER JOIN predmeti ON predmeti.id = teme.id_predmet
                WHERE prijave.prihvaceno = 1
                ORDER BY datum DESC , vrijeme DESC;";
                $conn->query($sql);
                $rezultat = $conn->query($sql);
                $br_red = 1;
        
                // print_r($rezultat->fetch_assoc());

                if ($rezultat->num_rows > 0) {
                    echo "<table class='table table table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th scope='col'>#</th>";
                    echo "<th scope='col'>Učenik</th>";
                    echo "<th scope='col'>Tema</th>";
                    echo "<th scope='col'>Predmet</th>";
                    echo "<th scope='col'>Datum</th>";
                    echo "<th scope='col'>Vrijeme</th>";
                    echo "<th scope='col'>Opcije</th>";
                    echo "</tr>";
                    echo "</thead>";
                    while($redak = $rezultat->fetch_assoc()) {
                        $datum = date_create(date($redak['datum']));
                        $datum = date_format($datum,"d.m.Y");

                        $vrijeme = date_create(date($redak['vrijeme']));
                        $vrijeme = date_format($vrijeme,"H:i:s");
                        ?>
                        <tbody>
                        <tr>
                            <th scope="row"><?php echo $br_red; ?></th>
                            <td><?php echo $redak["ime"] . " " . $redak["prezime"] . " (" . $redak["username"] . ")"; ?></td>
                            <td><?php echo $redak["tema_naziv"]; ?></td>
                            <td><?php echo $redak["predmet_naziv"]; ?></td>
                            <td><?php echo $datum; ?></td>
                            <td><?php echo $vrijeme; ?></td>
                            <td><form action="zahtjev_zavrsni.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="submit" value="Obriši" class="btn btn-outline-danger btn-sm" name="obrisi_btn"></td>
                        </tr>
                        <?php
                        $br_red += 1;
                    }
                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Nemate potvrđenih zahtjeva!</div>";
                }
                ?>
            </div>
        </div>

        

        <br><br><br><br>
        <div class="d-grid gap-2 col-4 mx-auto">
            <a href='nastavnik.php'><button class="btn btn-primary" type="button">NATRAG</button></a>
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