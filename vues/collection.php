<?php
if (!isset($conn)) {
    session_start();
    include("../config/db_connect.php");
}
include_once("../crud/crud_montre.php");
include_once("../crud/crud_panier.php");

$search      = trim($_GET["search"] ?? "");
$collections = getMontresFiltreesGroupees($conn, $search);

$nb_total = 0;
foreach ($collections as $montres) $nb_total += count($montres);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
    <link rel="stylesheet" href="../assets/css2/boutique.css">
    <link rel="stylesheet" href="../assets/css2/collection.css">
</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="collection.php" class="active">Collections</a>
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
        <h1 class="page-title">Collections</h1>

        <form method="GET" action="collection.php">
            <div class="filtres-bar">
                <input type="text" name="search" placeholder="Rechercher une montre..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-gold btn-sm">Rechercher</button>
                <?php if ($search): ?><a href="collection.php" class="btn btn-outline btn-sm">Effacer</a><?php endif; ?>
            </div>
        </form>

        <p style="color:#555; font-size:0.82rem; margin-bottom:30px;">
            <?php echo $nb_total; ?> montre(s) — <?php echo count($collections); ?> collection(s)
        </p>

        <?php if (empty($collections)): ?>
            <p style="color:#555;">Aucune montre trouvée.</p>
        <?php endif; ?>

        <?php foreach ($collections as $nom_marque => $montres): ?>
            <section class="collection-section">
                <h2 class="collection-titre">
                    <?php echo htmlspecialchars($nom_marque); ?>
                    <span class="collection-count"><?php echo count($montres); ?> montre(s)</span>
                </h2>
                <div class="montres-grid">
                    <?php foreach ($montres as $montre): ?>
                        <?php
                            $mid    = $montre["id"];
                            $titre  = htmlspecialchars($montre["titre"]);
                            $marque = htmlspecialchars($montre["marque"]);
                            $prix   = number_format($montre["prix"], 2, ',', ' ');
                            $ref    = htmlspecialchars($montre["reference"] ?? "");
                            $mouv   = htmlspecialchars($montre["mouvement"] ?? "");
                            $mat    = htmlspecialchars($montre["materiau"]  ?? "");
                            $image  = $montre["image"];
                            $statut = $montre["statut"];
                        ?>
                        <div class="montre-card">
                            <a href="detail_montre.php?id=<?php echo $mid; ?>">
                                <?php if (!empty($image)): ?>
                                    <img class="card-img" src="../assets/img/montres/<?php echo htmlspecialchars($image); ?>" alt="<?php echo $titre; ?>">
                                <?php else: ?>
                                    <div class="card-placeholder">⌚</div>
                                <?php endif; ?>
                            </a>
                            <?php if ($statut === "disponible"): ?>
                                <span class="col-badge col-badge-ok">Disponible</span>
                            <?php elseif ($statut === "vendu"): ?>
                                <span class="col-badge col-badge-red">Vendu</span>
                            <?php else: ?>
                                <span class="col-badge col-badge-warn">Suspendu</span>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="card-marque"><?php echo $marque; ?></div>
                                <div class="card-titre"><?php echo $titre; ?></div>
                                <?php if ($ref): ?><div style="font-size:0.75rem; color:#555; margin-bottom:4px;">Réf. <?php echo $ref; ?></div><?php endif; ?>
                                <?php if ($mouv || $mat): ?>
                                    <div style="font-size:0.75rem; color:#666; margin-bottom:10px;">
                                        <?php echo $mouv; ?><?php echo ($mouv && $mat) ? " · " : ""; ?><?php echo $mat; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-prix"><?php echo $prix; ?> €</div>
                                <a href="detail_montre.php?id=<?php echo $mid; ?>" class="btn btn-outline btn-sm" style="display:block; text-align:center; margin-top:10px;">Voir la fiche</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>
    <script src="../assets/js/main.js"></script>
</body>
</html>
