<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_commande.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../vues/connexion.php"); exit();
}

$commande_id  = (int)($_POST["commande_id"]  ?? 0);
$statut       = trim($_POST["statut"]        ?? "");
$numero_suivi = trim($_POST["numero_suivi"]  ?? "");

$statuts_valides = ["en_cours", "expediee", "livree", "annulee"];

if (!$commande_id || !in_array($statut, $statuts_valides)) {
    header("Location: ../vues/admin/commandes.php?msg=erreur"); exit();
}

if ($statut === "expediee" && !empty($numero_suivi)) {
    updateExpedition($conn, $commande_id, $numero_suivi);
} else {
    updateCommandeStatut($conn, $commande_id, $statut);
}

header("Location: ../vues/admin/commandes.php?msg=ok");
exit();
?>
