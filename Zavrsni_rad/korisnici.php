<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "administratori")
    {

    include "baza_podataka.php";

    if (isset($_POST["obrisi_btn"]))
    {
        $id = $_POST["id"];

        if ($_POST["titula"] == "profesor")
        {
            $sql = "SELECT profesori.id AS id_profesor,
                    predmeti.id AS id_predmet,
                    teme.id AS id_teme,
                    prijave.id AS id_prijave
            FROM profesori
            INNER JOIN predmeti ON predmeti.id_profesor = profesori.id
            INNER JOIN teme ON teme.id_predmet = predmeti.id
            INNER JOIN prijave ON prijave.id_teme = teme.id
            WHERE profesori.id=$id;";
            $rezultat = $conn->query($sql);
            $redak = $rezultat->fetch_assoc();
            

            if (!isset($redak['id_predmet'])) {
                $sql3 = true;
            } else {
                $id_predmet = $redak['id_predmet'];
                $sql3 = $conn->query("DELETE FROM teme WHERE id_predmet=$id_predmet;");
            }

            if (!isset($redak['id_teme'])) {
                $sql4 = true;
            } else {
                $id_teme = $redak['id_teme'];
                $sql4 = $conn->query("DELETE FROM prijave WHERE id_teme=$id_teme;");
            }

            $sql1 = $conn->query("DELETE FROM profesori WHERE id=$id;");
            $sql2 = $conn->query("DELETE FROM predmeti WHERE id_profesor=$id;");
            
            

            if ($sql1 && $sql2 && $sql3 && $sql4) {
                $conn->query("COMMIT TRANSACTION");
            } else {
                $conn->query("ROLLBACK");
            }

        } elseif ($_POST["titula"] == "ucenik") {
            $sql1 = "DELETE FROM ucenici WHERE id=$id;";
            $sql2 = "DELETE FROM prijave WHERE prijave.id_ucenik = ucenici.id AND prijave.id_ucenik=$id;";

            if ($conn->query($sql1) && $conn->query($sql2)) {
                $conn->query("COMMIT TRANSACTION");
            } else {
                $conn->query("ROLLBACK");
            }
        }
    }


?>

<html>
    <head>
        <title>Korisnici</title>
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
                    <a class="nav-link active" aria-current="page" href="admin.php">Početna</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="zahtjev_nastavnik.php">Zahtjevi</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="korisnici.php">Korisnici</a>
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
        <h1 style="text-align: center;">Korisnici sustava</h1>

        <br>
        <h2 style="text-align: center;">Učenici</h2>

        <div class="d-flex justify-content-center">
            <div style="width: 600px;">
                <?php
                    // ZA UČENIKE ------------------------------------------

                    $sql = "SELECT * FROM ucenici;";
                    $rezultat = $conn->query($sql);
                    $br_red = 1;

                    if ($rezultat->num_rows > 0) {
                        echo "<table class='table table table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th scope='col'>#</th>";
                        echo "<th scope='col'>Ime</th>";
                        echo "<th scope='col'>Prezime</th>";
                        echo "<th scope='col'>E-mail</th>";
                        echo "<th scope='col'>Username</th>";
                        echo "<th scope='col'>Opcije</th>";
                        echo "</tr>";
                        echo "</thead>";
                        while($redak = $rezultat->fetch_assoc()) {
                            ?>
                            <tbody>
                            <tr>
                                <th scope="row"><?php echo $br_red; ?></th>
                                <td><?php echo $redak["ime"]; ?></td>
                                <td><?php echo $redak["prezime"]; ?></td>
                                <td><?php echo $redak["email"]; ?></td>
                                <td><?php echo $redak["username"]; ?></td>
                                <td><form action="korisnici.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="hidden" name="titula" value="ucenik"><input type="submit" class="btn btn-outline-danger btn-sm" value="Obriši" name="obrisi_btn"></form></td>
                            </tr>
                            <?php
                            $br_red += 1;
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Trenutno ne postoje učenički računi!</div>";
                    }
                ?>
            </div>
        </div>
        
        <br><br>

        <h2 style="text-align: center;">Profesori</h2>

        <div class="d-flex justify-content-center">
            <div style="width: 600px;">
                <?php
                    // ZA PROFESORE ------------------------------------------

                    $sql = "SELECT * FROM profesori;";
                    $rezultat = $conn->query($sql);
                    $br_red = 1;

                    if ($rezultat->num_rows > 0) {
                        echo "<table class='table table table-hover'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th scope='col'>#</th>";
                        echo "<th scope='col'>Ime</th>";
                        echo "<th scope='col'>Prezime</th>";
                        echo "<th scope='col'>E-mail</th>";
                        echo "<th scope='col'>Username</th>";
                        echo "<th scope='col'>Opcije</th>";
                        echo "</tr>";
                        echo "</thead>";
                        while($redak = $rezultat->fetch_assoc()) {
                            ?>
                                <tbody>
                                <tr>
                                    <th scope="row"><?php echo $br_red; ?></th>
                                    <td><?php echo $redak["ime"]; ?></td>
                                    <td><?php echo $redak["prezime"]; ?></td>
                                    <td><?php echo $redak["email"]; ?></td>
                                    <td><?php echo $redak["username"]; ?></td>
                                    <td><form action="korisnici.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="hidden" name="titula" value="profesor"><input type="submit" class="btn btn-outline-danger btn-sm" value="Obriši" name="obrisi_btn"></form></td>
                                </tr>
                            <?php
                            $br_red += 1;
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Trenutno ne postoje profesorski računi!</div>";
                    }
                ?>
            </div>
        </div>

        

        <br><br><br><br>
        <div class="d-grid gap-2 col-4 mx-auto">
            <a href='admin.php'><button class="btn btn-primary" type="button">NATRAG</button></a>
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