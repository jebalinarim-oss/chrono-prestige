<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_montre.php");
include("../crud/crud_panier.php");

if (!isset($_SESSION["id"])) { header("Location: ../vues/connexion.php"); exit(); }

$uid       = (int)$_SESSION["id"];
$action    = $_POST["action"]    ?? "";
$montre_id = (int)($_POST["montre_id"] ?? 0);
$retour    = $_POST["retour"]    ?? "boutique"; // "boutique" ou "detail"

if ($action === "ajouter") {
    // Vérifie que la montre est disponible
    if (!getMontreDisponible($conn, $montre_id)) {
        header("Location: ../vues/boutique.php?msg=erreur"); exit();
    }

    // Ajoute au panier ou incrémente la quantité
    $item = getPanierItem($conn, $uid, $montre_id);
    if ($item) {
        updatePanierQuantite($conn, (int)$item["id"], (int)$item["quantite"] + 1);
    } else {
        addToPanier($conn, $uid, $montre_id);
    }

    $url = ($retour === "detail") ? "../vues/detail_montre.php?id=$montre_id&msg=panier_ok" : "../vues/boutique.php?msg=panier_ok";
    header("Location: $url"); exit();

} elseif ($action === "retirer") {
    $panier_id = (int)($_POST["panier_id"] ?? 0);
    removeFromPanier($conn, $panier_id, $uid);
    header("Location: ../vues/panier.php?msg=retire"); exit();

} else {
    header("Location: ../vues/boutique.php"); exit();
}
?>
