<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_panier.php");
include("../crud/crud_commande.php");
include("../crud/crud_montre.php");

if (!isset($_SESSION["id"])) { header("Location: ../vues/connexion.php"); exit(); }

$uid         = (int)$_SESSION["id"];
$rue         = trim($_POST["rue"]         ?? "");
$code_postal = trim($_POST["code_postal"] ?? "");
$ville       = trim($_POST["ville"]       ?? "");
$departement = trim($_POST["departement"] ?? "");
$pays        = trim($_POST["pays"]        ?? "");

if (empty($rue) || empty($ville) || empty($pays)) {
    header("Location: ../vues/panier.php?msg=adresse"); exit();
}

$adresse = $rue . ", " . $code_postal . " " . $ville
         . ($departement ? ", " . $departement : "")
         . ", " . $pays;

// Récupère les articles du panier
$articles = getPanierByUser($conn, $uid);
if (empty($articles)) { header("Location: ../vues/panier.php?msg=erreur"); exit(); }

// Crée une commande par article disponible et marque la montre comme vendue
foreach ($articles as $a) {
    if ($a["montre_statut"] !== "disponible") continue;
    createCommande($conn, $uid, (int)$a["montre_id"], $adresse, (float)$a["prix"]);
    updateMontreStatut($conn, (int)$a["montre_id"], "vendu");
}

// Vide le panier
viderPanier($conn, $uid);

header("Location: ../vues/panier.php?msg=commande_ok");
exit();
?>
