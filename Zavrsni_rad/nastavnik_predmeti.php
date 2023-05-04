<?php
    if (session_status() !== PHP_SESSION_ACTIVE || session_id() === ""){
        session_start(); 
    }
    if (isset($_SESSION['prijava']) && isset($_SESSION['tablica']) && $_SESSION['tablica'] == "profesori")
    {

    include "baza_podataka.php";

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if (isset($_POST['dodaj_btn']))
    {
        if (empty($_POST["naziv"]))
        {
            $_SESSION['errorMessage'] = "Molimo unesite naziv predmeta!";
        } else {

            $naziv = $_POST["naziv"];
            $naziv = test_input($naziv);
            // $username = $_SESSION['username'];
            $id_profesor = $_SESSION['id'];

            $sql = "INSERT INTO predmeti (naziv, id_profesor) VALUES ('$naziv', $id_profesor);";
            $conn->query($sql);

        }
    } elseif (isset($_POST['dodaj_tema_btn'])) {

        if (empty($_POST["naziv_tema"]))
        {
            $_SESSION['errorMessage'] = "Molimo unesite naziv teme!";
        } else {

            $naziv = $_POST["naziv_tema"];
            $naziv = test_input($naziv);
            // $username = $_SESSION['username'];
            $id_predmet = $_POST['predmet_id'];

            $sql = "INSERT INTO teme (naziv, id_predmet) VALUES ('$naziv', $id_predmet);";
            $conn->query($sql);

        }
    } elseif (isset($_POST['obrisi_predmet_btn'])) {
        $id_predmet = $_POST['predmet_id'];

        $sql1 = "DELETE FROM predmeti WHERE id=$id_predmet;";

        $sql= "SELECT * FROM teme WHERE id_predmet=$id_predmet;";
        $conn->query($sql);
        $rezultat = $conn->query($sql);
        if ($rezultat->num_rows > 0)
        {
            $redak = $rezultat->fetch_assoc();
            $id_tema = $redak['id'];

            $sql= "SELECT * FROM prijave WHERE id_teme=$id_tema;";
            $conn->query($sql);
            $rezultat = $conn->query($sql);
            if ($rezultat->num_rows > 0) {
                $sql2 = "DELETE FROM prijave WHERE id_teme=$id_tema;";

                $sql3 = "DELETE FROM teme WHERE id_predmet=$id_predmet;";

                if ($conn->query($sql1) && $conn->query($sql2)&& $conn->query($sql3)) {
                    $conn->query("COMMIT");
                } else {
                    $conn->query("ROLLBACK");
                }
            } else {
                $sql2 = "DELETE FROM teme WHERE id_predmet=$id_predmet;";

                if ($conn->query($sql1) && $conn->query($sql2)) {
                    $conn->query("COMMIT");
                } else {
                    $conn->query("ROLLBACK");
                }
            }

            $sql2 = "DELETE FROM prijave WHERE id_teme=$id_tema;";

            $sql3 = "DELETE FROM teme WHERE id_predmet=$id_predmet;";

            if ($conn->query($sql1) && $conn->query($sql2) && $conn->query($sql3)) {
                $conn->query("COMMIT");
            } else {
                $conn->query("ROLLBACK");
            }
        } else {
            $conn->query($sql1);
        }

    } elseif (isset($_POST['obrisi_tema_btn'])) {
        $id_tema = $_POST['tema_id'];

        $sql1 = $conn->query("DELETE FROM teme WHERE id=$id_tema;");
        $sql2 = $conn->query("DELETE FROM prijave WHERE id_teme=$id_tema;");

        if ($sql1 && $sql2) {
            $conn->query("COMMIT");
        } else {
            $conn->query("ROLLBACK");
        }
    }
?>


<html>
    <head>
        <title>Predmeti</title>
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
        <h1 style="text-align: center;">Vaši predmeti i teme</h1>

        <br>

        <div class="d-flex justify-content-center">
            <div style="width: 800px;">
                <div class="d-flex justify-content-center">
                    <div style="width: 400px;">
                        <?php
                        if (isset($_SESSION['errorMessage'])) {
                            echo "<div class='alert alert-danger' role='alert' style='text-align: center;'>".$_SESSION['errorMessage']."</div>";
                            unset($_SESSION['errorMessage']);
                        }
                        ?>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    
                    <form action="nastavnik_predmeti.php" method="POST">
                        
                        <label for="predmet" class="fs-4">Novi predmet:&nbsp;</label>
                        <input type="text" id="predmet" name="naziv" class="fs-5" placeholder="Unesite naziv predmeta">&nbsp;
                        <input type="submit" value="Dodaj predmet" class="btn btn-outline-success btn-md" name="dodaj_btn">
                    </form>
                    
                </div>
                
            </div>
        </div>

        <br><br><br>


        <div class="d-flex justify-content-center">
            <div style="width: 800px;">
                <table class="table table-borderless">
                    <tbody>
                    <?php
                    
                    include "baza_podataka.php";

                    $id_profesor = $_SESSION['id'];
                    $sql = "SELECT * FROM predmeti WHERE id_profesor=$id_profesor;";

                    $rezultat = $conn->query($sql);

                    if ($rezultat->num_rows > 0) {
                        while($redak = $rezultat->fetch_assoc()) {
                            ?>
                                    <tr style="margin-bottom: 0;">
                                        <td><div class="fs-3 fw-bold"><?php echo $redak['naziv']; ?></div></td>
                                        <td>
                                        <div class="container ">
                                            <form action="nastavnik_predmeti.php" method="POST">
                                                <input type="text" id="tema" name="naziv_tema">
                                                <input type="hidden" name="predmet_id" value="<?php echo $redak['id'];?>">&nbsp;
                                                <input type="submit" value="Dodaj temu" class="btn btn-outline-success btn-sm" name="dodaj_tema_btn">
                                            </form>
                                            </div>
                                        </td>
                                        <td>
                                            <form action="nastavnik_predmeti.php" method="POST" style="margin-bottom: 0;">
                                                <input type="hidden" name="predmet_id" value="<?php echo $redak['id'];?>">
                                                <input type="submit" value="Obrisi predmet" class="btn btn-outline-danger btn-sm" name="obrisi_predmet_btn">
                                            </form>
                                        </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3">
                            
                            <?php
                            $id_predmet = $redak['id'];
                            $sql = "SELECT * FROM teme WHERE id_predmet=$id_predmet;";

                            $rezultat_tema = $conn->query($sql);
                            
                            if ($rezultat_tema->num_rows > 0) {
                                echo "<div class='d-flex justify-content-center'>";
                                echo "<table>";
                                while($redak_tema = $rezultat_tema->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><div class="fs-5"><?php echo $redak_tema['naziv']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                                        <td>
                                        <form action="nastavnik_predmeti.php" method="POST" style="margin-bottom: 0;">
                                            <input type="hidden" name="tema_id" value="<?php echo $redak_tema['id'];?>">
                                            <input type="submit" value="Obriši temu" class="btn btn-outline-danger btn-sm" name="obrisi_tema_btn">
                                        </form>
                                        </td>
                                    </tr>

                                    <?php
                                    
                                }
                                echo "</table>";
                                echo "</div>";
                            } else {
                                echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Nemate dodanih tema!</div>";
                            }
                        }
                    } else {
                        echo "<div class='alert alert-warning' role='alert' style='text-align: center;'>Nemate dodanih predmeta!</div>";
                    }
                ?>
                        </td>
                        </tr>
                    </tbody>
                </table>
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