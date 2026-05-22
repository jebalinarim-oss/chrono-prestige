<?php
session_start();
include("../../config/db_connect.php");
include("../../crud/crud_montre.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../connexion.php");
    exit();
}

// Actions POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action    = $_POST["action"]    ?? "";
    $montre_id = (int)($_POST["montre_id"] ?? 0);

    if ($action == "supprimer" && $montre_id > 0) {
        deleteMontre($conn, $montre_id);
        header("Location: montres.php?msg=suppression_ok");
        exit();
    }

    if ($action == "statut" && $montre_id > 0) {
        $statut = $_POST["statut"] ?? "";
        if (in_array($statut, ["disponible", "vendu", "suspendu"])) {
            updateMontreStatut($conn, $montre_id, $statut);
        }
        header("Location: montres.php?msg=statut_ok");
        exit();
    }

    if ($action == "masquer" && $montre_id > 0) {
        $masquee = (int)($_POST["masquee"] ?? 0);
        updateMontreVisibilite($conn, $montre_id, $masquee);
        header("Location: montres.php?msg=masquee_ok");
        exit();
    }

    if ($action == "authentique" && $montre_id > 0) {
        $auth = $_POST["authentique"] ?? "NULL";
        updateMontreAuthentique($conn, $montre_id, $auth);
        header("Location: montres.php?msg=auth_ok");
        exit();
    }
}

// Données via model
$montres = getAllMontres($conn);

// Message retour
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "suppression_ok") { $message = "Montre supprimée.";              $message_type = "msg-warn"; }
    if ($_GET["msg"] == "statut_ok")      { $message = "Statut mis à jour."; }
    if ($_GET["msg"] == "masquee_ok")     { $message = "Visibilité mise à jour."; }
    if ($_GET["msg"] == "auth_ok")        { $message = "Authenticité mise à jour."; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montres – Admin CHRONO PRESTIGE</title>
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
            <a href="montres.php" class="active">Montres</a>
            <a href="commandes.php">Commandes</a>
            <div class="sidebar-title">Configuration</div>
            <a href="categories.php">Catégories</a>
        </aside>

        <main class="dash-content">
            <h1>Gestion des montres (<?php echo count($montres); ?>)</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="data-table">
                <thead>
                    <tr><th>Titre</th><th>Vendeur</th><th>Prix</th><th>Statut</th><th>Visible</th><th>Authenticité</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($montres as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m["titre"]); ?><br><span style="color:#555; font-size:0.78rem;"><?php echo htmlspecialchars($m["marque"]); ?></span></td>
                            <td style="color:#888;"><?php echo htmlspecialchars($m["vendeur_nom"] ?? "—"); ?></td>
                            <td><?php echo number_format($m["prix"], 2, ',', ' '); ?> €</td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="statut">
                                    <input type="hidden" name="montre_id" value="<?php echo $m["id"]; ?>">
                                    <select name="statut" onchange="this.form.submit()" style="background:#1a1a1a; color:#ccc; border:1px solid #2a2a2a; padding:4px 8px; font-size:0.78rem;">
                                        <option value="disponible" <?php echo $m["statut"]=="disponible" ? "selected" : ""; ?>>Disponible</option>
                                        <option value="vendu"      <?php echo $m["statut"]=="vendu"      ? "selected" : ""; ?>>Vendu</option>
                                        <option value="suspendu"   <?php echo $m["statut"]=="suspendu"   ? "selected" : ""; ?>>Suspendu</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="masquer">
                                    <input type="hidden" name="montre_id" value="<?php echo $m["id"]; ?>">
                                    <input type="hidden" name="masquee" value="<?php echo $m["masquee"] ? 0 : 1; ?>">
                                    <button type="submit" class="btn btn-sm" style="<?php echo $m["masquee"] ? 'background:#1a0000; color:#e53935; border:1px solid #e5393544;' : 'background:#0a1a0a; color:#4caf50; border:1px solid #4caf5044;'; ?> padding:5px 10px; font-size:0.72rem; cursor:pointer;">
                                        <?php echo $m["masquee"] ? "Masquée" : "Visible"; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="authentique">
                                    <input type="hidden" name="montre_id" value="<?php echo $m["id"]; ?>">
                                    <select name="authentique" onchange="this.form.submit()" style="background:#1a1a1a; color:#ccc; border:1px solid #2a2a2a; padding:4px 8px; font-size:0.78rem;">
                                        <option value="NULL" <?php echo $m["authentique"] === null  ? "selected" : ""; ?>>Non vérifié</option>
                                        <option value="1"    <?php echo $m["authentique"] == "1"    ? "selected" : ""; ?>>Authentique ✓</option>
                                        <option value="0"    <?php echo ($m["authentique"] == "0" && $m["authentique"] !== null) ? "selected" : ""; ?>>Refusé ✗</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="../detail_montre.php?id=<?php echo $m["id"]; ?>" class="btn btn-outline btn-sm">Voir</a>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="montre_id" value="<?php echo $m["id"]; ?>">
                                    <button type="submit" class="btn btn-red btn-sm">Supprimer</button>
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
