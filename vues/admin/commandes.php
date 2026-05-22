<?php
session_start();
include("../../config/db_connect.php");
include("../../crud/crud_commande.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../connexion.php"); exit();
}

$commandes = getAllCommandes($conn);

$statut_labels = [
    "en_cours" => ["En cours", "badge-warn"],
    "expediee" => ["Expédiée", "badge-gold"],
    "livree"   => ["Livrée",   "badge-ok"],
    "annulee"  => ["Annulée",  "badge-red"],
];

$message = "";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "ok")     $message = "Statut mis à jour.";
    if ($_GET["msg"] == "erreur") $message = "Erreur lors de la mise à jour.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes – Admin CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../../assets/css2/main.css">
    <link rel="stylesheet" href="../../assets/css2/boutique.css">
</head>
<body>

    <header class="site-header">
        <a href="../../index.php" class="logo">CHRONO PRESTIGE</a>
        <span style="font-size:0.7rem; letter-spacing:2px; color:#e53935; margin-left:8px;">ADMIN</span>
        <nav><a href="../../index.php">Accueil</a></nav>
        <div class="header-right">
            <a href="../deconnexion.php" class="btn btn-red btn-sm">Déconnexion</a>
        </div>
    </header>

    <div class="dashboard-wrap">
        <aside class="sidebar">
            <div class="sidebar-title">Admin</div>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Utilisateurs</a>
            <a href="montres.php">Montres</a>
            <a href="commandes.php" class="active">Commandes</a>
            <div class="sidebar-title">Configuration</div>
            <a href="categories.php">Catégories</a>
        </aside>

        <main class="dash-content">
            <h1>Commandes (<?php echo count($commandes); ?>)</h1>

            <?php if ($message): ?>
                <div class="msg msg-ok" style="margin-bottom:16px;"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Montre</th>
                        <th>Acheteur</th>
                        <th>Prix</th>
                        <th>Adresse</th>
                        <th>Date</th>
                        <th>Statut actuel</th>
                        <th>Modifier statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $c): ?>
                        <?php $label = $statut_labels[$c["statut"]] ?? [$c["statut"], "badge-warn"]; ?>
                        <tr>
                            <td style="color:#555;">#<?php echo $c["id"]; ?></td>
                            <td>
                                <?php echo htmlspecialchars($c["titre"]); ?><br>
                                <span style="color:#555; font-size:0.78rem;"><?php echo htmlspecialchars($c["marque"]); ?></span>
                            </td>
                            <td style="color:#888;"><?php echo htmlspecialchars($c["acheteur_nom"]); ?></td>
                            <td><?php echo number_format($c["prix_achat"], 2, ',', ' '); ?> €</td>
                            <td style="font-size:0.78rem; color:#666; max-width:180px;">
                                <?php echo htmlspecialchars($c["adresse_livraison"] ?? "—"); ?>
                            </td>
                            <td style="color:#444; font-size:0.8rem;"><?php echo $c["date_commande"]; ?></td>
                            <td><span class="badge <?php echo $label[1]; ?>"><?php echo $label[0]; ?></span></td>
                            <td>
                                <form method="POST" action="../../admin/admin_statut_commande.php"
                                      style="display:flex; flex-direction:column; gap:6px;">
                                    <input type="hidden" name="commande_id" value="<?php echo $c["id"]; ?>">
                                    <select name="statut" style="font-size:0.8rem; padding:4px 6px;">
                                        <option value="en_cours" <?php echo $c["statut"]=="en_cours" ? "selected" : ""; ?>>En cours</option>
                                        <option value="expediee" <?php echo $c["statut"]=="expediee" ? "selected" : ""; ?>>Expédiée</option>
                                        <option value="livree"   <?php echo $c["statut"]=="livree"   ? "selected" : ""; ?>>Livrée</option>
                                        <option value="annulee"  <?php echo $c["statut"]=="annulee"  ? "selected" : ""; ?>>Annulée</option>
                                    </select>
                                    <input type="text" name="numero_suivi"
                                           placeholder="N° suivi (si expédiée)"
                                           value="<?php echo htmlspecialchars($c["numero_suivi"] ?? ""); ?>"
                                           style="font-size:0.78rem; padding:4px 6px;">
                                    <button type="submit" class="btn btn-gold btn-sm">Valider</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
