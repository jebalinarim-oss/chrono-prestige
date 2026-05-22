<?php
session_start();

// Détruit la session et redirige vers l'accueil
$_SESSION = [];
session_destroy();

header("Location: ../index.php");
exit();
?>
