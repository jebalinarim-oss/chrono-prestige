<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

$uid = (int)$_SESSION["id"];

// Statistiques
$r1 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM montres WHERE vendeur_id=$uid");
$nb_montres = mysqli_fetch_assoc($r1)["nb"];

$r2 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM montres WHERE vendeur_id=$uid AND statut='disponible'");
$nb_disponibles = mysqli_fetch_assoc($r2)["nb"];

$r3 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes c JOIN montres m ON c.montre_id=m.id WHERE m.vendeur_id=$uid");
$nb_ventes = mysqli_fetch_assoc($r3)["nb"];

// Commandes récentes
$res_recent = mysqli_query($conn,
    "SELECT c.*, m.titre, u.username as acheteur
     FROM commandes c JOIN montres m ON c.montre_id=m.id JOIN users u ON c.acheteur_id=u.id
     WHERE m.vendeur_id=$uid ORDER BY c.date_commande DESC LIMIT 5"
);
$recentes = [];
while ($row = mysqli_fetch_assoc($res_recent)) $recentes[] = $row;
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendeur – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../../assets/css2/main.css">
</head>
<body>

    <header class="site-header">
        <a href="../../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../../index.php">Accueil</a>
            <a href="../boutique.php">Boutique</a>
        </nav>
        <div class="header-right">
            <a href="../profil.php" class="btn btn-outline btn-sm"><?php echo htmlspecialchars($_SESSION["login"]); ?></a>
            <a href="../deconnexion.php" class="btn btn-red btn-sm">Déconnexion</a>
        </div>
    </header>

    <div class="dashboard-wrap">
        <aside class="sidebar">
            <div class="sidebar-title">Vendeur</div>
            <a href="dashboard.php"      class="active">Dashboard</a>
            <a href="ajouter_montre.php">+ Ajouter une montre</a>
            <a href="mes_montres.php">Mes annonces</a>
            <a href="mes_ventes.php">Mes ventes</a>
        </aside>

        <main class="dash-content">
            <h1>Bonjour, <?php echo htmlspecialchars($_SESSION["login"]); ?></h1>

            <div class="stats-row">
                <div class="stat-box"><div class="val"><?php echo $nb_montres; ?></div><div class="lbl">Annonces</div></div>
                <div class="stat-box"><div class="val"><?php echo $nb_disponibles; ?></div><div class="lbl">Disponibles</div></div>
                <div class="stat-box"><div class="val"><?php echo $nb_ventes; ?></div><div class="lbl">Ventes</div></div>
            </div>

            <div style="display:flex; gap:12px; margin-bottom:32px;">
                <a href="ajouter_montre.php" class="btn btn-gold btn-sm">+ Nouvelle annonce</a>
                <a href="mes_montres.php"    class="btn btn-outline btn-sm">Mes annonces</a>
            </div>

            <h2 style="font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; color:#555; margin-bottom:14px;">Commandes récentes</h2>

            <?php if (empty($recentes)): ?>
                <p style="color:#444; font-size:0.85rem;">Aucune commande pour l'instant.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr><th>#</th><th>Montre</th><th>Acheteur</th><th>Statut</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentes as $r): ?>
                            <tr>
                                <td><?php echo $r["id"]; ?></td>
                                <td><?php echo htmlspecialchars($r["titre"]); ?></td>
                                <td><?php echo htmlspecialchars($r["acheteur"]); ?></td>
                                <td><span class="badge badge-warn"><?php echo $r["statut"]; ?></span></td>
                                <td style="color:#555;"><?php echo $r["date_commande"]; ?></td>
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
