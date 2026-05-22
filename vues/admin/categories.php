<?php
session_start();
include("../../config/db_connect.php");
include("../../crud/crud_admin.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../connexion.php");
    exit();
}

// Actions POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? "";

    if ($action == "ajouter") {
        $nom = trim($_POST["nom"] ?? "");
        if ($nom !== "") addCategorie($conn, $nom);
        header("Location: categories.php?msg=ajout_ok");
        exit();
    }

    if ($action == "modifier") {
        $cat_id = (int)($_POST["cat_id"] ?? 0);
        $nom    = trim($_POST["nom"]    ?? "");
        if ($cat_id > 0 && $nom !== "") updateCategorie($conn, $cat_id, $nom);
        header("Location: categories.php?msg=modif_ok");
        exit();
    }

    if ($action == "supprimer") {
        $cat_id = (int)($_POST["cat_id"] ?? 0);
        if ($cat_id > 0) deleteCategorie($conn, $cat_id);
        header("Location: categories.php?msg=suppression_ok");
        exit();
    }
}

// Données via model
$categories = getAllCategoriesAvecComptage($conn);

$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "ajout_ok")       { $message = "Catégorie ajoutée."; }
    if ($_GET["msg"] == "modif_ok")       { $message = "Catégorie modifiée."; }
    if ($_GET["msg"] == "suppression_ok") { $message = "Catégorie supprimée."; $message_type = "msg-warn"; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégories – Admin CHRONO PRESTIGE</title>
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
            <a href="commandes.php">Commandes</a>
            <div class="sidebar-title">Configuration</div>
            <a href="categories.php" class="active">Catégories</a>
        </aside>

        <main class="dash-content">
            <h1>Catégories</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="form-card" style="max-width:400px; margin-bottom:30px;">
                <h2 style="font-size:0.8rem; letter-spacing:2px; text-transform:uppercase; color:#555; margin-bottom:16px;">Ajouter une catégorie</h2>
                <form method="POST" style="display:flex; gap:10px;">
                    <input type="hidden" name="action" value="ajouter">
                    <div class="form-group" style="flex:1; margin-bottom:0;">
                        <input type="text" name="nom" required placeholder="Ex: Vintage">
                    </div>
                    <button type="submit" class="btn btn-gold">Ajouter</button>
                </form>
            </div>

            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Nom</th><th>Montres</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td style="color:#555;"><?php echo $c["id"]; ?></td>
                            <td>
                                <form method="POST" style="display:flex; gap:8px; align-items:center;">
                                    <input type="hidden" name="action" value="modifier">
                                    <input type="hidden" name="cat_id" value="<?php echo $c["id"]; ?>">
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($c["nom"]); ?>" style="background:#1a1a1a; border:1px solid #2a2a2a; color:#ccc; padding:6px 10px; font-size:0.85rem; width:200px;">
                                    <button type="submit" class="btn btn-gold btn-sm">OK</button>
                                </form>
                            </td>
                            <td style="color:#555;"><?php echo $c["nb_montres"]; ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Supprimer ?');">
                                    <input type="hidden" name="action" value="supprimer">
                                    <input type="hidden" name="cat_id" value="<?php echo $c["id"]; ?>">
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
