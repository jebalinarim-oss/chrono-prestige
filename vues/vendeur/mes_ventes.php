<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

$uid = (int)$_SESSION["id"];

// Ventes
$res = mysqli_query($conn,
    "SELECT c.*, m.titre, m.marque, u.username as acheteur_nom, u.email as acheteur_email
     FROM commandes c JOIN montres m ON c.montre_id=m.id JOIN users u ON c.acheteur_id=u.id
     WHERE m.vendeur_id=$uid ORDER BY c.date_commande DESC"
);

$ventes = [];
while ($v = mysqli_fetch_assoc($res)) $ventes[] = $v;

// Ventes actives vs terminées
$nb_actives   = 0;
$nb_terminees = 0;
foreach ($ventes as $v) {
    if ($v["statut"] == "livree" || $v["statut"] == "annulee") $nb_terminees++;
    else $nb_actives++;
}

$statut_labels = [
    "en_cours" => ["En cours",  "badge-warn"],
    "expediee" => ["Expédiée",  "badge-gold"],
    "livree"   => ["Livrée",    "badge-ok"],
    "annulee"  => ["Annulée",   "badge-red"],
];
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes ventes – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../../assets/css2/main.css">
</head>
<body>

    <header class="site-header">
        <a href="../../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav><a href="../../index.php">Accueil</a></nav>
        <div class="header-right">
            <a href="../deconnexion.php" class="btn btn-outline btn-sm">Déconnexion</a>
        </div>
    </header>

    <div class="dashboard-wrap">
        <aside class="sidebar">
            <div class="sidebar-title">Vendeur</div>
            <a href="dashboard.php">Dashboard</a>
            <a href="ajouter_montre.php">+ Ajouter une montre</a>
            <a href="mes_montres.php">Mes annonces</a>
            <a href="mes_ventes.php" class="active">Mes ventes</a>
        </aside>

        <main class="dash-content">
            <h1>Mes ventes</h1>

            <div class="stats-row" style="margin-bottom:24px;">
                <div class="stat-box"><div class="val"><?php echo count($ventes); ?></div><div class="lbl">Total ventes</div></div>
                <div class="stat-box"><div class="val"><?php echo $nb_actives; ?></div><div class="lbl">En cours</div></div>
                <div class="stat-box"><div class="val"><?php echo $nb_terminees; ?></div><div class="lbl">Terminées</div></div>
            </div>

            <?php if (empty($ventes)): ?>
                <p style="color:#555;">Aucune vente pour l'instant.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Montre</th><th>Acheteur</th><th>Prix</th><th>Statut</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventes as $v): ?>
                            <?php $label = $statut_labels[$v["statut"]] ?? [$v["statut"], "badge-warn"]; ?>
                            <tr>
                                <td style="color:#555;">#<?php echo $v["id"]; ?></td>
                                <td><?php echo htmlspecialchars($v["titre"]); ?></td>
                                <td><?php echo htmlspecialchars($v["acheteur_nom"]); ?></td>
                                <td><?php echo number_format($v["prix_achat"], 2, ',', ' '); ?> €</td>
                                <td><span class="badge <?php echo $label[1]; ?>"><?php echo $label[0]; ?></span></td>
                                <td style="color:#444; font-size:0.8rem;"><?php echo $v["date_commande"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
