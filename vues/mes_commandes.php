<?php
session_start();
include("../config/db_connect.php");
include_once("../crud/crud_commande.php");

if (!isset($_SESSION["id"])) { header("Location: connexion.php"); exit(); }

$uid       = (int)$_SESSION["id"];
$commandes = getCommandesByUser($conn, $uid);

// Labels statuts
$statut_labels = [
    "en_cours"  => ["En cours",  "badge-warn"],
    "expediee"  => ["Expédiée",  "badge-gold"],
    "livree"    => ["Livrée",    "badge-ok"],
    "annulee"   => ["Annulée",   "badge-red"],
];
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
    <link rel="stylesheet" href="../assets/css2/boutique.css">

</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="collection.php">Collections</a>
            <a href="panier.php">🛒 Panier</a>
        </nav>
        <div class="header-right">
            <a href="profil.php" class="btn btn-outline btn-sm"><?php echo htmlspecialchars($_SESSION["login"]); ?></a>
        </div>
    </header>

    <main class="page-main">
        <h1 class="page-title">Mes Commandes</h1>

        <?php if (empty($commandes)): ?>
            <p style="color:#555;">Vous n'avez pas encore de commandes. <a href="boutique.php">Voir la boutique →</a></p>
        <?php else: ?>
            <?php foreach ($commandes as $c): ?>
                <?php
                    $s  = $c["statut"];
                    $label = $statut_labels[$s] ?? [$s, "badge-warn"];
                ?>
                <div class="commande-card">
                    <div class="cc-header">
                        <span class="cc-id">Commande #<?php echo $c["id"]; ?></span>
                        <span class="badge <?php echo $label[1]; ?>"><?php echo $label[0]; ?></span>
                    </div>
                    <div class="cc-titre"><?php echo htmlspecialchars($c["titre"]); ?> — <span style="color:#666;"><?php echo htmlspecialchars($c["marque"]); ?></span></div>
                    <div class="cc-prix"><?php echo number_format($c["prix_achat"], 2, ',', ' '); ?> €</div>

                    <?php if (!empty($c["adresse_livraison"])): ?>
                        <div style="font-size:0.82rem; color:#555; margin-top:8px;">
                            Adresse : <?php echo htmlspecialchars($c["adresse_livraison"]); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($c["numero_suivi"])): ?>
                        <div style="font-size:0.82rem; color:#c9a84c; margin-top:4px;">
                            N° suivi : <?php echo htmlspecialchars($c["numero_suivi"]); ?>
                        </div>
                    <?php endif; ?>

                    <div class="cc-date"><?php echo $c["date_commande"]; ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
