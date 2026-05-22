<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $serveur = "localhost";
    $utilisateur = "grp15";
    $mot_de_passe = "Xueng2ea";
    $bdd = "db_grp15";

    $conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $bdd);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
        die("Erreur de connexion a la base de donnees");
    }
?>
