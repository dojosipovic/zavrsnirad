<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "ucenici")
    {

    include "baza_podataka.php";

    

?>

<html>
    <head>
        <title>Tema</title>
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
                        <a class="nav-link active" aria-current="page" href="ucenik.php">Početna</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="ucenik_tema.php">Završni rad</a>
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
            <h1 style="text-align: center;">Završni rad</h1>

            <br>
            
            <?php
                if (isset($_POST['profesor'])) {
                    echo "<h2 style='text-align: center;'>Odaberi podatke</h2>";
                    $id_profesor = $_POST['profesor'];
                    $_SESSION['profesor'] = $id_profesor;
                    $sql = "SELECT * FROM profesori WHERE id=$id_profesor;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $redak = $rezultat->fetch_assoc();

                    $sql = "SELECT * FROM predmeti WHERE id_profesor=$id_profesor;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);

                    if ($rezultat->num_rows > 0) {
                        ?>
                        <div class="d-flex justify-content-center" >
                            <div style="width: 400px;">
                                <form action="ucenik_tema.php" method="POST">
                                    <select name="predmet" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                        <option name="" value="" disabled selected hidden>Odaberi predmet</option>
                                        <?php
                                            while($redak = $rezultat->fetch_assoc()){
                                                $id_predmet = $redak['id'];
                                                $naziv_predmet = $redak['naziv'];
                                                echo "<option value='$id_predmet'>$naziv_predmet</option>";
                                            }
                                        ?>
                                    </select>
                                    <div class="d-flex justify-content-end"><input type="submit" value="Sljedeće" class="btn btn-outline-success btn-lg" name="predmet_btn"></div>
                                </form>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<div class='d-flex justify-content-center'><div style='width: 400px;'><div class='alert alert-warning' role='alert' style='text-align: center;'>Profesor " . $redak['ime'] . " " . $redak['prezime'] . " trenutno nema postavljene predmete!</div></div></div>";
                    }
                } elseif (isset($_POST['predmet'])) {
                    echo "<h2 style='text-align: center;'>Odaberi podatke</h2>";
                    $id_predmet = $_POST['predmet'];
                    $id_profesor = $_SESSION['profesor'];
                    $_SESSION['predmet'] = $id_predmet;
                    $sql = "SELECT * FROM profesori WHERE id=$id_profesor";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $redak = $rezultat->fetch_assoc();

                    $sql = "SELECT * FROM teme WHERE id_predmet=$id_predmet;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);

                    if ($rezultat->num_rows > 0) {
                        ?>
                        <div class="d-flex justify-content-center">
                            <div style="width: 400px;">
                            <form action="ucenik_tema.php" method="POST">
                                <select name="tema" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                    <option name="" value="" disabled selected hidden>Odaberi temu</option>
                                    <?php
                                        while($redak = $rezultat->fetch_assoc()){
                                            $id_tema = $redak['id'];
                                            $naziv_tema = $redak['naziv'];
                                            echo "<option value='$id_tema'>$naziv_tema</option>";
                                        }
                                    ?>
                                </select>
                                <div class="d-flex justify-content-end"><input type="submit" value="Sljedeće" class="btn btn-outline-success btn-lg" name="tema_btn"></div>
                            </form>
                            </div>
                        </div>
                        
                        <?php
                    }
                    else {
                        echo "<div class='d-flex justify-content-center'><div style='width: 400px;'><div class='alert alert-warning' role='alert' style='text-align: center;'>Profesor " . $redak['ime'] . " " . $redak['prezime'] . " trenutno nema postavljene teme!</div></div></div>";
                    }
                } elseif (isset($_POST['tema'])) {
                    echo "<h2 style='text-align: center;'>Odaberi podatke</h2>";
                    $_SESSION['tema'] = $_POST['tema'];

                    $id_profesor = $_SESSION['profesor'];
                    $id_predmet = $_SESSION['predmet'];
                    $id_tema = $_SESSION['tema'];

                    $sql = "SELECT * FROM profesori WHERE id=$id_profesor;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $redak = $rezultat->fetch_assoc();
                    $profesor = $redak['ime'] . " " . $redak['prezime'] . " (" . $redak['username'] . ")";

                    $sql = "SELECT * FROM predmeti WHERE id=$id_predmet;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $redak = $rezultat->fetch_assoc();
                    $predmet = $redak['naziv'];

                    $sql = "SELECT * FROM teme WHERE id=$id_tema;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);
                    $redak = $rezultat->fetch_assoc();
                    $tema = $redak['naziv'];
                    ?>

                        <div class="d-flex justify-content-center">
                            <div class="card" style="width: 500px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                    <table>
                                        <tr>
                                            <td><p class="card-text fs-5">Profesor:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $profesor; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Predmet:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $predmet; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Tema:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $tema; ?></p></td>
                                        </tr>
                                        <tr>
                                        
                                        </tr>
                                    </table>
                                    </div>
                                    <br>
                                    <div class="d-flex justify-content-end">
                                        <form action="ucenik_tema.php" method="POST">
                                                <input type="submit" value="Potvrdi" class="btn btn-success" name="potvrdi_btn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                } elseif (isset($_POST['potvrdi_btn'])) {
                    echo "<h2 style='text-align: center;'>Odaberi podatke</h2>";
                    $id_ucenik = $_SESSION['id'];
                    $id_teme = $_SESSION['tema'];
                    $datum = date("Y-m-d");
                    $vrijeme = date("H:i:s");
                    $prihvaceno = null;
                    $id_predmet = $_SESSION['predmet'];
                    $id_profesor = $_SESSION['profesor'];

                    $sql = "SELECT * FROM prijave WHERE id_teme=$id_teme;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);

                    if ($rezultat->num_rows > 0) {
                        ?>
                            <div class="d-flex justify-content-center"><div style="width: 400px;"><div class="alert alert-warning" role="alert" style="text-align: center;"><div class="alert alert-warning" role="alert">Tema ovog predmeta već je prijavljena.</div></div></div>
                        <?php
                    } else {
                        $sql1 = "INSERT INTO prijave(id_ucenik, prihvaceno, id_teme, datum, vrijeme) VALUES ($id_ucenik, '$prihvaceno', $id_teme, '$datum', '$vrijeme');";
                        $sql2 = "SELECT * FROM teme WHERE id=$id_teme;";
                        $sql3 = "SELECT * FROM predmeti WHERE id=$id_predmet;";
                        $sql4 = "SELECT * FROM profesori WHERE id=$id_profesor;";

                        if ($conn->query($sql1) && $conn->query($sql2) && $conn->query($sql3) && $conn->query($sql4)) {
                            $conn->query("COMMIT");
                            ?>
                                <div class="d-flex justify-content-center"><div style="width: 400px;"><div class="alert alert-success" role="alert" style="text-align: center;">Uspješno ste prijavili svoj završni rad, molimo pričekajte da ga profesor prihvati.</div></div></div>
                            <?php
                        } else {
                            $conn->query("ROLLBACK");
                            ?>
                                <div class="d-flex justify-content-center"><div style="width: 400px;"><div class="alert alert-danger" role="alert" style="text-align: center;">Došlo je do pogreške prilikom prijave završnog rada. Molimo pokušajte ponovo.</div></div></div>
                            <?php
                        }
                    }

                    
                } else {
                    $id_ucenik = $_SESSION['id'];
                    $sql = "SELECT * FROM prijave WHERE id_ucenik=$id_ucenik;";
                    $conn->query($sql);
                    $rezultat = $conn->query($sql);

                    if ($rezultat->num_rows > 0) {
                        $sql = "SELECT prijave.prihvaceno,
                                        prijave.datum,
                                        prijave.vrijeme,
                                        teme.naziv AS naziv_teme,
                                        predmeti.naziv AS naziv_predmet,
                                        profesori.ime, profesori.prezime,
                                        profesori.username
                                FROM prijave
                                INNER JOIN teme ON teme.id = prijave.id_teme
                                INNER JOIN predmeti ON teme.id_predmet = predmeti.id
                                INNER JOIN profesori ON predmeti.id_profesor = profesori.id
                                WHERE prijave.id_ucenik = $id_ucenik;";
                        $conn->query($sql);
                        $rezultat = $conn->query($sql);
                        $redak = $rezultat->fetch_assoc();

                        $datum = date_create(date($redak['datum']));
                        $datum = date_format($datum,"d.m.Y");

                        $vrijeme = date_create(date($redak['vrijeme']));
                        $vrijeme = date_format($vrijeme,"H:i:s");
                        ?>
                            <div class="d-flex justify-content-center">
                            <div class="card" style="width: 500px;">
                                <img src="<?php if ($redak['prihvaceno'] == 0){echo "decline.png";} else{echo "accept.png";}; ?>" class="card-img-top" style="height: 150px; width: 150px; margin-left: auto; margin-right: auto; margin-top: 20px" alt="...">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                    <table>
                                        <tr>
                                            <td><p class="card-text fs-5">Tema:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $redak['naziv_teme']; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Predmet:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $redak['naziv_predmet']; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Profesor (username):</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $redak['ime'] . " " . $redak['prezime'] . " (" . $redak['username'] . ")";?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Potvrđeno:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php if ($redak['prihvaceno'] == 0){echo "Ne";} else{echo "Da";}; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Datum prijave:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $datum; ?></p></td>
                                        </tr>
                                        <tr>
                                            <td><p class="card-text fs-5">Vrijeme prijave:</p></td>
                                            <td><p class="card-text fs-5" style="margin-left: 10px;"><?php echo $vrijeme; ?></p></td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            </div>
                            
                        <?php
                    } else {
                        echo "<h2 style='text-align: center;'>Odaberi podatke</h1>";
                        $sql = "SELECT * FROM profesori;";
                        $conn->query($sql);
                        $rezultat = $conn->query($sql);

                        if ($rezultat->num_rows > 0) {
                            ?>
                            <div class="d-flex justify-content-center">
                            <div style="width: 400px;">
                            <form action="ucenik_tema.php" method="POST">
                            
                            <select name="profesor" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                                <option name="" value="" disabled selected hidden>Odaberi profesora mentora</option>
                                <?php
                                    while($redak = $rezultat->fetch_assoc()){
                                        $id_profesor = $redak['id'];
                                        $ime_prezime_prof = $redak['ime'] . " " . $redak['prezime'] . " (" . $redak['username'] . ")";
                                        echo "<option value='$id_profesor'>$ime_prezime_prof</option>";
                                    }
                                ?>
                            </select>
                            
                            <div class="d-flex justify-content-end"><input type="submit" value="Sljedeće" class="btn btn-outline-success btn-lg" name="profesor_btn"></div>
                            
                            </form>
                            </div>
                            </div>

                            <?php
                        } else {
                            echo "<div class='d-flex justify-content-center'><div style='width: 400px;'><div class='alert alert-warning' role='alert' style='text-align: center;'>Profesori još nisu uvedeni u sustav!</div></div></div>";
                        }
                    }
        
                }
                    
                    
            ?>

        <br><br><br><br>
        <div class="d-grid gap-2 col-3 mx-auto">
            <a href="ucenik.php"><button class="btn btn-primary" type="button">NATRAG</button></a>
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