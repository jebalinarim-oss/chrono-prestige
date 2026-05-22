<?php
session_start();
include("../config/db_connect.php");
include("../crud/crud_montre.php");

// Charge les montres groupées par marque puis affiche la vue
$collections = getMontresGroupedByMarque($conn);

include("../vues/collection.php");
?>
