<?php
session_start();
include("../../config/db_connect.php");
include("../../crud/crud_user.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../connexion.php");
    exit();
}

// Actions POST — traitées ici car la page est son propre contrôleur POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action  = $_POST["action"]  ?? "";
    $user_id = (int)($_POST["user_id"] ?? 0);

    if ($action == "supprimer" && $user_id > 0 && $user_id != $_SESSION["id"]) {
        deleteUser($conn, $user_id);
        header("Location: users.php?msg=suppression_ok");
        exit();
    }

    if ($action == "role" && $user_id > 0) {
        $new_role = $_POST["role"] ?? "";
        if (in_array($new_role, ["client", "vendeur", "admin"])) {
            updateUserRole($conn, $user_id, $new_role);
        }
        header("Location: users.php?msg=role_ok");
        exit();
    }

    if ($action == "suspendre" && $user_id > 0) {
        $suspendu = (int)($_POST["suspendu"] ?? 0);
        setSuspenduUser($conn, $user_id, $suspendu);
        header("Location: users.php?msg=suspendu_ok");
        exit();
    }
}

// Données via model
$users = getAllUsers($conn);

// Message retour
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "suppression_ok") { $message = "Utilisateur supprimé."; $message_type = "msg-warn"; }
    if ($_GET["msg"] == "role_ok")        { $message = "Rôle mis à jour."; }
    if ($_GET["msg"] == "suspendu_ok")    { $message = "Statut mis à jour."; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs – Admin CHRONO PRESTIGE</title>
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
            <a href="users.php" class="active">Utilisateurs</a>
            <a href="montres.php">Montres</a>
            <a href="commandes.php">Commandes</a>
            <div class="sidebar-title">Configuration</div>
            <a href="categories.php">Catégories</a>
        </aside>

        <main class="dash-content">
            <h1>Utilisateurs (<?php echo count($users); ?>)</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Login</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="color:#555;"><?php echo $u["id"]; ?></td>
                            <td><?php echo htmlspecialchars($u["username"]); ?></td>
                            <td style="color:#888;"><?php echo htmlspecialchars(($u["prenom"] ?? "") . " " . ($u["nom"] ?? "")); ?></td>
                            <td style="color:#666; font-size:0.82rem;"><?php echo htmlspecialchars($u["email"]); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="role">
                                    <input type="hidden" name="user_id" value="<?php echo $u["id"]; ?>">
                                    <select name="role" onchange="this.form.submit()" style="background:#1a1a1a; color:#ccc; border:1px solid #2a2a2a; padding:4px 8px; font-size:0.8rem;">
                                        <option value="client"  <?php echo $u["role"]=="client"  ? "selected" : ""; ?>>Client</option>
                                        <option value="vendeur" <?php echo $u["role"]=="vendeur" ? "selected" : ""; ?>>Vendeur</option>
                                        <option value="admin"   <?php echo $u["role"]=="admin"   ? "selected" : ""; ?>>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <?php if ($u["suspendu"]): ?>
                                    <span class="badge badge-red">Suspendu</span>
                                <?php else: ?>
                                    <span class="badge badge-ok">Actif</span>
                                <?php endif; ?>
                            </td>
                            <td style="display:flex; gap:6px; align-items:center;">
                                <?php if ($u["id"] != $_SESSION["id"]): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="suspendre">
                                        <input type="hidden" name="user_id" value="<?php echo $u["id"]; ?>">
                                        <input type="hidden" name="suspendu" value="<?php echo $u["suspendu"] ? 0 : 1; ?>">
                                        <button type="submit" class="btn btn-sm" style="background:#1a2a1a; color:#8bc34a; border:1px solid #8bc34a44; padding:5px 10px; font-size:0.72rem; cursor:pointer;">
                                            <?php echo $u["suspendu"] ? "Réactiver" : "Suspendre"; ?>
                                        </button>
                                    </form>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        <input type="hidden" name="action" value="supprimer">
                                        <input type="hidden" name="user_id" value="<?php echo $u["id"]; ?>">
                                        <button type="submit" class="btn btn-red btn-sm">Supprimer</button>
                                    </form>
                                <?php endif; ?>
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
