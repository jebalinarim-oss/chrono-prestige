<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_commande.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "vendeur") {
    header("Location: ../vues/connexion.php"); exit();
}

$uid         = (int)$_SESSION["id"];
$commande_id = (int)($_POST["commande_id"] ?? 0);
$suivi       = trim($_POST["numero_suivi"] ?? "");

// Vérifie que la commande appartient bien à ce vendeur
if (!checkCommandeAppartientVendeur($conn, $commande_id, $uid)) {
    header("Location: ../vues/vendeur/expeditions.php?msg=erreur"); exit();
}

updateExpedition($conn, $commande_id, $suivi);

header("Location: ../vues/vendeur/expeditions.php?msg=expedition_ok");
exit();
?>
