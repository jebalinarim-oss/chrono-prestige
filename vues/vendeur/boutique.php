<?php
session_start();
include("../../config/db_connect.php");
include_once("../../crud/crud_montre.php");

$search  = trim($_GET["search"] ?? "");
$montres = getMontresSearch($conn, $search);
$message = (isset($_GET["msg"]) && $_GET["msg"] == "panier_ok") ? "Montre ajoutée au panier." : "";
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
    <link rel="stylesheet" href="../assets/css2/boutique.css">
</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php" class="active">Boutique</a>
            <a href="collection.php">Collections</a>
            <?php if (isset($_SESSION["id"])): ?>
                <a href="panier.php">🛒 Panier</a>
            <?php endif; ?>
        </nav>
        <div class="header-right">
            <?php if (isset($_SESSION["id"])): ?>
                <a href="profil.php" class="btn btn-outline btn-sm"><?php echo htmlspecialchars($_SESSION["login"]); ?></a>
            <?php else: ?>
                <a href="connexion.php" class="btn btn-outline btn-sm">Connexion</a>
                <a href="inscription.php" class="btn btn-gold btn-sm">Inscription</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="page-main">
        <h1 class="page-title">Boutique</h1>

        <?php if ($message): ?>
            <div class="msg msg-ok"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="GET" action="boutique.php">
            <div class="filtres-bar">
                <input type="text" name="search" placeholder="Rechercher une montre..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-gold btn-sm">Rechercher</button>
                <?php if ($search): ?><a href="boutique.php" class="btn btn-outline btn-sm">Effacer</a><?php endif; ?>
            </div>
        </form>

        <!-- Grille des montres -->
        <div class="montres-grid">

            <?php if (empty($montres)): ?>
                <p style="color:#555; grid-column:1/-1;">Aucune montre ne correspond à votre recherche.</p>
            <?php endif; ?>

            <?php foreach ($montres as $montre): ?>

                <?php
                    $mid     = $montre["id"];
                    $titre   = htmlspecialchars($montre["titre"]);
                    $marque  = htmlspecialchars($montre["marque"]);
                    $prix    = number_format($montre["prix"], 2, ',', ' ');
                    $image   = $montre["image"];
                ?>

                <div class="montre-card">

                    <a href="detail_montre.php?id=<?php echo $mid; ?>">
                        <?php if (!empty($image)): ?>
                            <img class="card-img" src="../assets/img/montres/<?php echo htmlspecialchars($image); ?>" alt="<?php echo $titre; ?>">
                        <?php else: ?>
                            <div class="card-placeholder">⌚</div>
                        <?php endif; ?>
                    </a>

                    <div class="card-body">
                        <div class="card-marque"><?php echo $marque; ?></div>
                        <div class="card-titre"><?php echo $titre; ?></div>
                        <div class="card-prix"><?php echo $prix; ?> €</div>

                        <div class="card-actions">
                            <?php if (isset($_SESSION["id"])): ?>

                                <form method="POST" action="../admin/admin_panier.php">
                                    <input type="hidden" name="action" value="ajouter">
                                    <input type="hidden" name="montre_id" value="<?php echo $mid; ?>">
                                    <input type="hidden" name="redirect" value="boutique">
                                    <button type="submit" class="btn-panier">+ Panier</button>
                                </form>

                            <?php else: ?>
                                <a href="connexion.php" class="btn-panier">Se connecter</a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>

    <script src="../assets/js/recherche.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
