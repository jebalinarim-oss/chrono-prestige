<?php
// ========== PHP ==========
session_start();
include("config/db_connect.php");

$login    = isset($_SESSION["login"]) ? htmlspecialchars($_SESSION["login"]) : "";
$role     = isset($_SESSION["role"])  ? $_SESSION["role"] : "";
$connecte = isset($_SESSION["id"]);


?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="assets/css2/main.css">
    <link rel="stylesheet" href="assets/css2/index.css">
    <link rel="stylesheet" href="assets/css2/boutique.css">
</head>
<body>

    <header class="site-header">
        <a href="index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="index.php" class="active">Accueil</a>
            <a href="vues/boutique.php">Boutique</a>
            <a href="vues/collection.php">Collections</a>
            <?php if ($connecte): ?>
                <a href="vues/panier.php">Panier</a>
            <?php endif; ?>
            <?php if ($role == "vendeur"): ?>
                <a href="vues/vendeur/dashboard.php">Espace vendeur</a>
            <?php endif; ?>
            <?php if ($role == "admin"): ?>
                <a href="vues/admin/dashboard.php">Espace admin</a>
            <?php endif; ?>
        </nav>
        <div class="header-right">
            <?php if ($connecte): ?>
                <a href="vues/profil.php" class="btn btn-outline btn-sm"><?php echo $login; ?></a>
                <a href="vues/deconnexion.php" class="btn btn-red btn-sm">Déconnexion</a>
            <?php else: ?>
                <a href="vues/connexion.php"  class="btn btn-outline btn-sm">Connexion</a>
                <a href="vues/inscription.php" class="btn btn-gold btn-sm">Inscription</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <!-- Hero -->
        <section class="hero">
            <div class="hero-content">
                <h1 class="hero-title">L'excellence horlogère</h1>
                <p class="hero-description">
                    Découvrez une sélection de montres prestigieuses, modernes et intemporelles.
                    Chrono Prestige réunit passion, élégance et raffinement.
                </p>
                <div class="hero-buttons">
                    <a href="vues/boutique.php" class="btn btn-primary">Découvrir</a>
                    <a href="vues/collection.php" class="btn btn-secondary">Explorer la collection</a>
                </div>
            </div>
        </section>

        <!-- Catégories / univers -->
        <section class="content-section">
            <h2 class="section-title">Notre univers</h2>
            <div class="cards">
                <div class="watch-card">
                    <h3>Montres classiques</h3>
                    <p>Des modèles intemporels au design raffiné.</p>
                </div>
                <div class="watch-card">
                    <h3>Montres sportives</h3>
                    <p>La précision et la performance dans chaque détail.</p>
                </div>
                <div class="watch-card">
                    <h3>Éditions exclusives</h3>
                    <p>Des pièces rares pour les passionnés d'horlogerie.</p>
                </div>
            </div>
        </section>

        <!-- Liens rapides selon rôle -->
        <?php if ($connecte): ?>
        <section class="content-section" style="padding-bottom:60px;">
            <h2 class="section-title">Mon espace</h2>
            <div class="cards">
                <div class="watch-card">
                    <h3>Mon profil</h3>
                    <p>Gérer mes informations, commandes et favoris.</p>
                    <a href="vues/profil.php" class="btn btn-secondary" style="margin-top:14px; display:inline-block;">Accéder</a>
                </div>
                <div class="watch-card">
                    <h3>Mes commandes</h3>
                    <p>Suivre l'état de livraison de mes achats.</p>
                    <a href="vues/mes_commandes.php" class="btn btn-secondary" style="margin-top:14px; display:inline-block;">Voir</a>
                </div>
                <?php if ($role == "vendeur"): ?>
                <div class="watch-card">
                    <h3>Espace vendeur</h3>
                    <p>Gérer mes annonces, ventes et expéditions.</p>
                    <a href="vues/vendeur/dashboard.php" class="btn btn-primary" style="margin-top:14px; display:inline-block;">Dashboard</a>
                </div>
                <?php endif; ?>
                <?php if ($role == "admin"): ?>
                <div class="watch-card">
                    <h3>Administration</h3>
                    <p>Gérer les utilisateurs, montres, commandes et litiges.</p>
                    <a href="vues/admin/dashboard.php" class="btn btn-primary" style="margin-top:14px; display:inline-block;">Dashboard</a>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="footer-links">
            <a href="#">Mentions légales</a>
            <a href="#">Confidentialité</a>
            <a href="#">Contact</a>
            <a href="#">Aide</a>
        </div>
        <p class="copyright">© 2026 CHRONO PRESTIGE — Tous droits réservés</p>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
