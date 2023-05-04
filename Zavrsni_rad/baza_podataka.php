<?php
    //spajanje na bazu podataka
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "zavrsnirad_model2";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Neuspješno spajanje na bazu podataka: " . $conn->connect_error);
    }

    //korištenje baze
    $sql = "USE $database";

    if ($conn->query($sql) === false) {
        echo "Greška prilikom upisivanja podataka: " . $conn->error;
    }

?>