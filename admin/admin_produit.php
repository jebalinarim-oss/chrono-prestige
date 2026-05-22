<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_montre.php");

if (!isset($_SESSION["id"]) || !in_array($_SESSION["role"], ["vendeur", "admin"])) {
    header("Location: ../vues/connexion.php"); exit();
}

$uid    = (int)$_SESSION["id"];
$action = $_POST["action"] ?? "";

if ($action === "ajouter") {
    $titre       = trim($_POST["titre"]       ?? "");
    $marque      = trim($_POST["marque"]      ?? "");
    $prix        = (float)($_POST["prix"]     ?? 0);
    $description = trim($_POST["description"] ?? "");
    $image       = "";

    if (empty($titre) || empty($marque) || $prix <= 0) {
        header("Location: ../vues/vendeur/ajouter_montre.php?erreur=champs"); exit();
    }

    // Upload de l'image
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $image = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../assets/img/montres/" . $image);
    }

    addMontre($conn, $titre, $marque, $prix, $description, $image, $uid);
    header("Location: ../vues/vendeur/mes_montres.php?msg=ajout_ok"); exit();

} elseif ($action === "supprimer") {
    $montre_id = (int)($_POST["montre_id"] ?? 0);

    // Le vendeur ne supprime que ses montres, l'admin peut tout supprimer
    if ($_SESSION["role"] === "vendeur") {
        deleteMontreByVendeur($conn, $montre_id, $uid);
    } else {
        deleteMontre($conn, $montre_id);
    }

    header("Location: ../vues/vendeur/mes_montres.php?msg=suppression_ok"); exit();

} else {
    header("Location: ../vues/vendeur/mes_montres.php"); exit();
}
?>
