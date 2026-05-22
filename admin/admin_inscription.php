<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_user.php");

$login  = trim($_POST["login"]  ?? "");
$nom    = trim($_POST["nom"]    ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$age    = (int)($_POST["age"]   ?? 0);
$email  = trim($_POST["email"]  ?? "");
$mdp    = $_POST["mdp"]         ?? "";
$role   = $_POST["role"]        ?? "";

// Seuls client et vendeur peuvent s'inscrire (pas admin)
if ($role !== "client" && $role !== "vendeur") {
    header("Location: ../vues/inscription.php?erreur=role"); exit();
}

// Login ou email déjà utilisé
if (checkLoginOrEmailExists($conn, $login, $email)) {
    header("Location: ../vues/inscription.php?erreur=existe"); exit();
}

// Crée le compte
$new_id = createUser($conn, $login, $nom, $prenom, $age, $email, $mdp, $role);

header("Location: ../vues/connexion.php?success=1");
exit();
?>
