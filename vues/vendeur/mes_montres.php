<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

$uid = (int)$_SESSION["id"];

$res = mysqli_query($conn,
    "SELECT m.*, c.nom as categorie_nom
     FROM montres m LEFT JOIN categories c ON m.categorie_id=c.id
     WHERE m.vendeur_id=$uid ORDER BY m.date_ajout DESC"
);

$montres = [];
while ($m = mysqli_fetch_assoc($res)) $montres[] = $m;

// Message
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "ajout_ok")       { $message = "Montre publiée avec succès."; }
    if ($_GET["msg"] == "modif_ok")       { $message = "Montre modifiée avec succès."; }
    if ($_GET["msg"] == "suppression_ok") { $message = "Montre supprimée."; $message_type = "msg-warn"; }
    if ($_GET["msg"] == "deja_vendu")     { $message = "Impossible de modifier une montre déjà vendue."; $message_type = "msg-error"; }
    if ($_GET["msg"] == "erreur")         { $message = "Une erreur est survenue."; $message_type = "msg-error"; }
}
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes annonces – CHRONO PRESTIGE</title>
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
            <a href="mes_montres.php" class="active">Mes annonces</a>
            <a href="mes_ventes.php">Mes ventes</a>
        </aside>

        <main class="dash-content">
            <h1>Mes annonces (<?php echo count($montres); ?>)</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <a href="ajouter_montre.php" class="btn btn-gold btn-sm" style="margin-bottom:20px; display:inline-block;">+ Nouvelle annonce</a>

            <?php if (empty($montres)): ?>
                <p style="color:#555;">Vous n'avez pas encore publié de montres.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr><th>Titre</th><th>Marque</th><th>Prix</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($montres as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($m["titre"]); ?></td>
                                <td><?php echo htmlspecialchars($m["marque"]); ?></td>
                                <td><?php echo number_format($m["prix"], 2, ',', ' '); ?> €</td>
                                <td>
                                    <?php if ($m["statut"] == "disponible"): ?>
                                        <span class="badge badge-ok">Disponible</span>
                                    <?php elseif ($m["statut"] == "vendu"): ?>
                                        <span class="badge badge-red">Vendu</span>
                                    <?php else: ?>
                                        <span class="badge badge-warn">Suspendu</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:#444; font-size:0.8rem;"><?php echo $m["date_ajout"]; ?></td>
                                <td style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                                    <a href="../detail_montre.php?id=<?php echo $m["id"]; ?>" class="btn btn-outline btn-sm">Voir</a>
                                    <?php if ($m["statut"] != "vendu"): ?>
                                        <a href="modifier_montre.php?id=<?php echo $m["id"]; ?>" class="btn btn-gold btn-sm">Modifier</a>
                                        <form method="POST" action="../../admin/admin_produit.php" onsubmit="return confirm('Supprimer cette annonce ?');">
                                            <input type="hidden" name="action" value="supprimer">
                                            <input type="hidden" name="montre_id" value="<?php echo $m["id"]; ?>">
                                            <button type="submit" class="btn btn-red btn-sm">Supprimer</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
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
