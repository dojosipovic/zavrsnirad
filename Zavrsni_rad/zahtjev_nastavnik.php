<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "administratori")
    {

    include "baza_podataka.php";

    if (isset($_POST["odbij_btn"])) {
        $id = $_POST["id"];
        $sql = "UPDATE ucenici SET zahtjev_profesor=0 WHERE id=$id;";
        $rezultat = $conn->query($sql);
    } elseif (isset($_POST["prihvati_btn"])) {
        $id = $_POST["id"];
        $sql = "SELECT * FROM ucenici WHERE id=$id;";
        $rezultat = $conn->query($sql);
        $redak = $rezultat->fetch_assoc();
        $ime = $redak["ime"];
        $prezime = $redak["prezime"];
        $email = $redak["email"];
        $username = $redak["username"];
        $pass = $redak["password"];

        $sql1 = $conn->query("INSERT INTO profesori(ime, prezime, email, username, password) VALUES ('$ime', '$prezime', '$email', '$username', '$pass');");
        $sql2 = $conn->query("DELETE FROM ucenici WHERE id=$id;");
        $sql3 = $conn->query("DELETE FROM prijave WHERE id_ucenik=$id;");

        if ($sql1 && $sql2 && $sql3) {
            $conn->query("COMMIT TRANSACTION");
        } else {
            $conn->query("ROLLBACK");
        }
    }
?>

<html>
    <head>
        <title>Zahtjevi</title>
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
        <h2 style="text-align: center;">Zahtjevi za nastavnika</h2>

        <div class="d-flex justify-content-center">
            <div style="width: 600px;">
                <?php
                    $sql = "SELECT * FROM ucenici WHERE zahtjev_profesor=1";
                    $rezultat = $conn->query($sql);
                    $br_red = 1;
                    
                    // print_r($rezultat->fetch_assoc());
                
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
                                <td><div class="d-flex justify-content-center"><form action="zahtjev_nastavnik.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="submit" class="btn btn-outline-success btn-sm" value="Prihvati" name="prihvati_btn"></form><div style="width: 5px;"></div><form action="zahtjev_nastavnik.php" method="POST"><input type="hidden" name="id" value="<?php echo $redak["id"]; ?>"><input type="submit" class="btn btn-outline-danger btn-sm" value="Odbij" name="odbij_btn"></form></div></td>
                            </tr>
                            <?php
                            $br_red += 1;
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Nemate zahtjeva za nastavnika!</div>";
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