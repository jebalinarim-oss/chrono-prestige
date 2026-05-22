<?php
session_start();
include("../../config/db_connect.php");
include("../../crud/crud_admin.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../connexion.php"); exit();
}

$stats = getDashboardStats($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../../assets/css2/main.css">
    <link rel="stylesheet" href="../../assets/css2/boutique.css">
</head>
<body>

    <header class="site-header">
        <a href="../../index.php" class="logo">CHRONO PRESTIGE</a>
        <span style="font-size:0.7rem; letter-spacing:2px; color:#e53935; margin-left:8px;">ADMIN</span>
        <nav><a href="../../index.php">Accueil</a></nav>
        <div class="header-right">
            <button id="btn-theme" class="btn-theme" onclick="toggleTheme()">🌙</button>
            <a href="../deconnexion.php" class="btn btn-red btn-sm">Déconnexion</a>
        </div>
    </header>

    <div class="dashboard-wrap">
        <aside class="sidebar">
            <div class="sidebar-title">Admin</div>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="users.php">Utilisateurs</a>
            <a href="montres.php">Montres</a>
            <a href="commandes.php">Commandes</a>
            <div class="sidebar-title">Configuration</div>
            <a href="categories.php">Catégories</a>
        </aside>

        <main class="dash-content">
            <h1>Tableau de bord</h1>

            <div class="stats-row">
                <div class="stat-box"><div class="val"><?php echo $stats["nb_users"]; ?></div><div class="lbl">Utilisateurs</div></div>
                <div class="stat-box"><div class="val"><?php echo $stats["nb_dispo"]; ?></div><div class="lbl">Montres en vente</div></div>
                <div class="stat-box"><div class="val"><?php echo $stats["nb_en_cours"]; ?></div><div class="lbl">Commandes actives</div></div>
                <div class="stat-box"><div class="val"><?php echo $stats["nb_livrees"]; ?></div><div class="lbl">Ventes clôturées</div></div>
            </div>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
