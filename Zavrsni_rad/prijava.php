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
        <title>Prijava završnog rada</title>
        <link rel="icon" href="icon.png">
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <br>
        <h1 style="text-align: center; padding-top: 200px;">Prijava završnih radova</h1>
        
        <div class="d-flex justify-content-center">
            <div style="width: 500px;">
            <form class="px-4 py-3 mx-5" action="prijava.php" method="POST">
            <?php
                if (isset($_SESSION['errorMessage'])) {
                    echo "<div class='alert alert-danger' role='alert'>".$_SESSION['errorMessage']."</div>";
                    unset($_SESSION['errorMessage']);
                }
            ?>
                <h2 style="text-align: center; ">Prijava</h2>
                <br>
                <div class="mb-3" >
                    <input type="text" name="korisnicko_ime" value="<?php if (isset($korisnicko_ime)) echo $korisnicko_ime; ?>" class="form-control" id="exampleDropdownFormEmail1" placeholder="Korisničko ime" autocomplete="off"/>
                </div>
                <div class="mb-3">
                    <input type="password" name="lozinka" class="form-control" id="exampleDropdownFormPassword1" placeholder="Lozinka" autocomplete="off"/>
                </div>
                        
                <div class="d-grid gap-2 col-5 mx-auto">
                    <input type="submit" class="btn btn-outline-success" name="login_btn" value="Prijava"/>
                </div>
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="registracija.php" target="_self">Nemate račun? Stvorite ga.</a>
                <a class="dropdown-item" href="zaboravljena_lozinka.php" target="_self">Zaboravio sam lozinku.</a>
                
                
            </form>
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