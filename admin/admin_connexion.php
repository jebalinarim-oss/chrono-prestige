<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_user.php");

$login = $_POST["login"] ?? "";
$mdp   = $_POST["mdp"]   ?? "";

// Vérifie login + mot de passe
$user = loginUser($conn, $login, $mdp);

if (!$user)                 { header("Location: ../vues/connexion.php?erreur=1");        exit(); }
if ($user["suspendu"] == 1) { header("Location: ../vues/connexion.php?erreur=suspendu"); exit(); }

// Ouvre la session
$_SESSION["id"]    = $user["id"];
$_SESSION["login"] = $user["username"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"]  = $user["role"];

header("Location: ../index.php");
exit();
?>
