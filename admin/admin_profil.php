<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_user.php");

if (!isset($_SESSION["id"])) { header("Location: ../vues/connexion.php"); exit(); }

$uid    = (int)$_SESSION["id"];
$nom    = trim($_POST["nom"]    ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email  = trim($_POST["email"]  ?? "");
$age    = (int)($_POST["age"]   ?? 0);

// Email déjà pris par un autre compte
if (checkEmailTakenByOther($conn, $email, $uid)) {
    header("Location: ../vues/profil.php?erreur=email"); exit();
}

updateUserProfile($conn, $uid, $nom, $prenom, $email, $age);

// Change le mot de passe seulement s'il est renseigné
if (!empty($_POST["mdp"])) {
    updateUserPassword($conn, $uid, $_POST["mdp"]);
}

$_SESSION["email"] = $email;

header("Location: ../vues/profil.php?msg=update_ok");
exit();
?>
