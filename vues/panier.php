<?php
session_start();
include("../config/db_connect.php");
include_once("../crud/crud_panier.php");

if (!isset($_SESSION["id"])) { header("Location: connexion.php"); exit(); }

$uid           = (int)$_SESSION["id"];
$articles      = getPanierByUser($conn, $uid);
$total_affiche = number_format(getTotalPanier($conn, $uid), 2, ',', ' ');

$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "retire")      { $message = "Article retiré du panier.";     $message_type = "msg-warn"; }
    if ($_GET["msg"] == "commande_ok") { $message = "Commande passée avec succès !"; }
    if ($_GET["msg"] == "adresse")     { $message = "Veuillez renseigner une adresse."; $message_type = "msg-error"; }
    if ($_GET["msg"] == "erreur")      { $message = "Une erreur est survenue.";      $message_type = "msg-error"; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier – CHRONO PRESTIGE</title>
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
            <a href="panier.php" class="active">🛒 Panier</a>
        </nav>
        <div class="header-right">
            <a href="profil.php" class="btn btn-outline btn-sm"><?php echo htmlspecialchars($_SESSION["login"]); ?></a>
        </div>
    </header>

    <main class="page-main">
        <h1 class="page-title">Mon Panier</h1>

        <?php if ($message): ?>
            <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <p style="color:#555;">Votre panier est vide. <a href="boutique.php">Retour à la boutique →</a></p>
        <?php else: ?>
            <div class="panier-layout">
                <div>
                    <?php foreach ($articles as $a): ?>
                        <div class="panier-item">
                            <?php if (!empty($a["image"])): ?>
                                <img src="../assets/img/montres/<?php echo htmlspecialchars($a["image"]); ?>" alt="">
                            <?php else: ?>
                                <div class="pi-placeholder">⌚</div>
                            <?php endif; ?>
                            <div>
                                <div class="pi-titre"><a href="detail_montre.php?id=<?php echo $a["montre_id"]; ?>"><?php echo htmlspecialchars($a["titre"]); ?></a></div>
                                <div class="pi-marque"><?php echo htmlspecialchars($a["marque"]); ?></div>
                            </div>
                            <div class="pi-prix"><?php echo number_format($a["prix"], 2, ',', ' '); ?> €</div>
                            <form method="POST" action="../admin/admin_panier.php">
                                <input type="hidden" name="action" value="retirer">
                                <input type="hidden" name="panier_id" value="<?php echo $a["panier_id"]; ?>">
                                <button type="submit" class="btn btn-red btn-sm">Retirer</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="panier-recap">
                    <h3>Récapitulatif</h3>
                    <?php foreach ($articles as $a): ?>
                        <div class="recap-ligne">
                            <span><?php echo htmlspecialchars($a["titre"]); ?></span>
                            <span><?php echo number_format($a["prix"], 2, ',', ' '); ?> €</span>
                        </div>
                    <?php endforeach; ?>
                    <div class="recap-total">
                        <span>Total</span>
                        <span><?php echo $total_affiche; ?> €</span>
                    </div>
                    <form method="POST" action="../admin/admin_commande.php" style="margin-top:22px;">
                        <h3 style="font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; color:#888; margin-bottom:14px;">Adresse de livraison</h3>
                        <div class="form-group">
                            <label>Rue et numéro</label>
                            <input type="text" name="rue" required placeholder="15 rue de la Paix">
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 2fr; gap:10px;">
                            <div class="form-group">
                                <label>Code postal</label>
                                <input type="text" name="code_postal" required placeholder="75001">
                            </div>
                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="ville" required placeholder="Paris">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Département</label>
                            <input type="text" name="departement" placeholder="Île-de-France">
                        </div>
                        <div class="form-group">
                            <label>Pays</label>
                            <input type="text" name="pays" required value="France">
                        </div>
                        <button type="submit" class="btn btn-gold" style="width:100%; margin-top:8px;">Confirmer la commande</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>
    <script src="../assets/js/main.js"></script>
</body>
</html>
