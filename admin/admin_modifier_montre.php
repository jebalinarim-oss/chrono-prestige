<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_montre.php");

if (!isset($_SESSION["id"]) || !in_array($_SESSION["role"], ["vendeur", "admin"])) {
    header("Location: ../vues/connexion.php"); exit();
}

$uid       = (int)$_SESSION["id"];
$montre_id = (int)($_POST["montre_id"] ?? 0);

// Récupère la montre (le vendeur ne peut accéder qu'à la sienne)
$montre = ($_SESSION["role"] === "vendeur")
    ? getMontreByVendeur($conn, $montre_id, $uid)
    : getMontreById($conn, $montre_id);

if (!$montre) { header("Location: ../vues/vendeur/mes_montres.php?msg=erreur"); exit(); }

// Un vendeur ne peut pas modifier une montre déjà vendue
if ($montre["statut"] === "vendu" && $_SESSION["role"] !== "admin") {
    header("Location: ../vues/vendeur/mes_montres.php?msg=deja_vendu"); exit();
}

updateMontre($conn, $montre_id,
    trim($_POST["titre"]        ?? ""),
    trim($_POST["marque"]       ?? ""),
    (float)($_POST["prix"]      ?? 0),
    trim($_POST["description"]  ?? ""),
    trim($_POST["reference"]    ?? ""),
    trim($_POST["materiau"]     ?? ""),
    trim($_POST["mouvement"]    ?? ""),
    trim($_POST["couleur"]      ?? ""),
    (int)($_POST["categorie_id"] ?? 0)
);

// Nouvelle image si uploadée
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
    $image = basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], "../assets/img/montres/" . $image);
    updateMontreImage($conn, $montre_id, $image);
}

header("Location: ../vues/vendeur/mes_montres.php?msg=modif_ok");
exit();
?>
