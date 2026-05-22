<?php
session_start();
include("../config/db_connect.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: boutique.php");
    exit();
}

$id = (int)$_GET["id"];
$res = mysqli_query($conn, "SELECT m.*, u.username as vendeur_nom FROM montres m LEFT JOIN users u ON m.vendeur_id=u.id WHERE m.id=$id");

if (mysqli_num_rows($res) == 0) {
    header("Location: boutique.php");
    exit();
}

$montre = mysqli_fetch_assoc($res);

// Compteur panier
$nb_panier = 0;

if (isset($_SESSION["id"])) {
    $uid = (int)$_SESSION["id"];
    $r = mysqli_query($conn, "SELECT COUNT(*) as nb FROM panier WHERE acheteur_id=$uid");
    $nb_panier = mysqli_fetch_assoc($r)["nb"];
}

$message = "";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "panier_ok") $message = "<p class='msg-success'>Montre ajoutée au panier.</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($montre["titre"]); ?> – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/style.css">
    <link rel="stylesheet" href="../assets/css2/produit.css">
</head>
<body>

    <header>
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="panier.php">Panier (<?php echo $nb_panier; ?>)</a>
        </nav>
        <?php if (isset($_SESSION["login"])): ?>
            <a href="deconnexion.php">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php">Connexion</a>
            <a href="inscription.php">Inscription</a>
        <?php endif; ?>
    </header>

    <main>
        <?php echo $message; ?>

        <div class="produit-container">
            <div class="produit-image">
                <?php if (!empty($montre["image"])): ?>
                    <img src="../assets/img/montres/<?php echo htmlspecialchars($montre["image"]); ?>" alt="<?php echo htmlspecialchars($montre["titre"]); ?>">
                <?php else: ?>
                    <div class="no-image">⌚</div>
                <?php endif; ?>
            </div>

            <div class="produit-info">
                <p class="marque"><?php echo htmlspecialchars($montre["marque"]); ?></p>
                <h1><?php echo htmlspecialchars($montre["titre"]); ?></h1>
                <p class="prix"><?php echo number_format($montre["prix"], 2, ',', ' '); ?> €</p>

                <?php if ($montre["statut"] == "disponible"): ?>
                    <span class="statut statut-disponible">Disponible</span>
                <?php else: ?>
                    <span class="statut statut-vendu">Vendu</span>
                <?php endif; ?>

                <?php if (!empty($montre["description"])): ?>
                    <p class="description"><?php echo nl2br(htmlspecialchars($montre["description"])); ?></p>
                <?php endif; ?>

                <div class="produit-actions">
                    <?php if ($montre["statut"] == "disponible"): ?>
                        <?php if (isset($_SESSION["id"])): ?>
                            <form method="POST" action="../admin/admin_panier.php">
                                <input type="hidden" name="action" value="ajouter">
                                <input type="hidden" name="montre_id" value="<?php echo $id; ?>">
                                <input type="hidden" name="redirect" value="detail&id=<?php echo $id; ?>">
                                <button type="submit" class="btn-panier">Ajouter au panier</button>
                            </form>

                        <?php else: ?>
                            <a href="connexion.php" class="btn-panier">Connexion pour acheter</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($montre["vendeur_nom"])): ?>
                    <p style="margin-top:25px; color:#666; font-size:0.85rem;">
                        Vendu par <strong style="color:#aaa;"><?php echo htmlspecialchars($montre["vendeur_nom"]); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© 2026 CHRONO PRESTIGE</p>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
