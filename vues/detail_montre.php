<?php
session_start();
include("../config/db_connect.php");
include_once("../crud/crud_montre.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: boutique.php"); exit();
}

$id = (int)$_GET["id"];
$m  = getMontreDetail($conn, $id);
if (!$m) { header("Location: boutique.php"); exit(); }




$titre     = htmlspecialchars($m["titre"]);
$marque    = htmlspecialchars($m["marque"]);
$prix      = number_format($m["prix"], 2, ',', ' ');
$ref       = htmlspecialchars($m["reference"]   ?? "");
$materiau  = htmlspecialchars($m["materiau"]    ?? "");
$mouvement = htmlspecialchars($m["mouvement"]   ?? "");
$couleur   = htmlspecialchars($m["couleur"]     ?? "");
$desc      = nl2br(htmlspecialchars($m["description"] ?? ""));
$statut    = $m["statut"];

$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "panier_ok") { $message = "Montre ajoutée au panier."; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titre; ?> – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
    <link rel="stylesheet" href="../assets/css2/boutique.css">
    <link rel="stylesheet" href="../assets/css2/detail.css">
</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
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

        <div style="max-width:1100px; margin:0 auto; padding:0 20px 20px;">
            <a href="boutique.php" style="font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#9A7B1A; text-decoration:none;">← Retour boutique</a>
        </div>

        <?php if ($message): ?>
            <div class="msg <?php echo $message_type; ?>" style="max-width:1100px; margin:0 auto 20px; padding-left:20px; padding-right:20px;"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="detail-layout">

            <div class="detail-img">
                <?php if (!empty($m["image"])): ?>
                    <img src="../assets/img/montres/<?php echo htmlspecialchars($m["image"]); ?>" alt="<?php echo $titre; ?>">
                <?php else: ?>
                    <div class="placeholder">⌚</div>
                <?php endif; ?>
            </div>

            <div class="detail-info">

                <div class="di-marque"><?php echo $marque; ?></div>
                <h1 class="di-titre"><?php echo $titre; ?></h1>
                <div class="di-prix"><?php echo $prix; ?> €</div>

                <div class="detail-specs">
                    <table>
                        <?php if ($ref):       ?><tr><td>Référence</td> <td><?php echo $ref; ?></td></tr><?php endif; ?>
                        <?php if ($mouvement): ?><tr><td>Mouvement</td> <td><?php echo $mouvement; ?></td></tr><?php endif; ?>
                        <?php if ($materiau):  ?><tr><td>Matériau</td>  <td><?php echo $materiau; ?></td></tr><?php endif; ?>
                        <?php if ($couleur):   ?><tr><td>Couleur</td>   <td><?php echo $couleur; ?></td></tr><?php endif; ?>
                        <tr><td>Disponibilité</td><td>
                            <?php if ($statut == "disponible"): ?>
                                <span class="badge badge-ok">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-red">Vendu</span>
                            <?php endif; ?>
                        </td></tr>
                        <?php if ($m["authentique"] == 1): ?>
                            <tr><td>Authenticité</td><td><span class="badge badge-ok">Certifié authentique ✓</span></td></tr>
                        <?php endif; ?>
                    </table>
                </div>

                <?php if ($desc): ?>
                    <div class="di-desc"><?php echo $desc; ?></div>
                <?php endif; ?>

                <div class="detail-actions">
                    <?php if ($statut == "disponible" && isset($_SESSION["id"])): ?>
                        <form method="POST" action="../admin/admin_panier.php">
                            <input type="hidden" name="action" value="ajouter">
                            <input type="hidden" name="montre_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="retour" value="detail">
                            <button type="submit" class="btn btn-gold">Ajouter au panier</button>
                        </form>
                    <?php elseif ($statut == "disponible"): ?>
                        <a href="connexion.php" class="btn btn-outline">Se connecter pour acheter</a>
                    <?php else: ?>
                        <div class="badge badge-red" style="padding:14px 20px; font-size:13px; text-align:center; letter-spacing:1px;">Cette montre a été vendue</div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($m["vendeur_nom"])): ?>
                    <div class="di-vendeur">Vendu par <strong><?php echo htmlspecialchars($m["vendeur_nom"]); ?></strong></div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>
    <script src="../assets/js/main.js"></script>
</body>
</html>
