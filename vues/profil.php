<?php
// ========== PHP ==========
session_start();
include("../config/db_connect.php");

if (!isset($_SESSION["id"])) {
    header("Location: connexion.php");
    exit();
}

$uid = (int)$_SESSION["id"];

// Données utilisateur
$res  = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
$user = mysqli_fetch_assoc($res);

// Compteurs

$r2 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes WHERE acheteur_id=$uid");
$nb_commandes = mysqli_fetch_assoc($r2)["nb"];

// Message
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "update_ok") { $message = "Profil mis à jour avec succès."; }
}
if (isset($_GET["erreur"])) {
    if ($_GET["erreur"] == "email") { $message = "Cet email est déjà utilisé."; $message_type = "msg-error"; }
}
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil – CHRONO PRESTIGE</title>
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
            <a href="panier.php">🛒 Panier</a>
        </nav>
        <div class="header-right">
            <a href="profil.php" class="btn btn-outline btn-sm active"><?php echo htmlspecialchars($_SESSION["login"]); ?></a>
        </div>
    </header>

    <main class="page-main">
        <h1 class="page-title">Mon Profil</h1>

        <?php if ($message): ?>
            <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="profil-layout">
            <!-- Résumé -->
            <div>
                <div class="profil-card">
                    <div class="pc-avatar">👤</div>
                    <div class="pc-name"><?php echo htmlspecialchars($user["username"]); ?></div>
                    <div class="pc-email"><?php echo htmlspecialchars($user["email"]); ?></div>
                    <span class="badge badge-<?php echo $user["role"]; ?>"><?php echo strtoupper($user["role"]); ?></span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:14px;">
                    <div class="stat-box">
                        <div class="val"><?php echo $nb_commandes; ?></div>
                        <div class="lbl">Commandes</div>
                    </div>
                </div>

                <div style="margin-top:16px; display:flex; flex-direction:column; gap:8px;">
                    <a href="mes_commandes.php" class="btn btn-outline btn-sm" style="text-align:center;">Suivre mes commandes</a>
                    <a href="panier.php"        class="btn btn-outline btn-sm" style="text-align:center;">Mon panier</a>
                    <?php if ($user["role"] == "vendeur"): ?>
                        <a href="vendeur/dashboard.php" class="btn btn-gold btn-sm" style="text-align:center;">Espace vendeur</a>
                    <?php endif; ?>
                    <?php if ($user["role"] == "admin"): ?>
                        <a href="admin/dashboard.php" class="btn btn-gold btn-sm" style="text-align:center;">Espace admin</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulaire modification -->
            <div>
                <div class="form-card" style="max-width:100%;">
                    <h2 style="font-size:0.9rem; letter-spacing:2px; text-transform:uppercase; color:#888; margin-bottom:22px;">Modifier mes informations</h2>
                    <form method="POST" action="../admin/admin_profil.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="prenom" value="<?php echo htmlspecialchars($user["prenom"] ?? ""); ?>">
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user["nom"] ?? ""); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Âge</label>
                            <input type="number" name="age" min="16" max="120" value="<?php echo (int)($user["age"] ?? 0); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($user["email"]); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe <span style="color:#555;">(laisser vide = inchangé)</span></label>
                            <input type="password" name="mdp" placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-gold">Sauvegarder</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
