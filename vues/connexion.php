<?php
session_start();

$erreur = "";
if (isset($_GET["erreur"])) {
    if ($_GET["erreur"] == 1)          $erreur = "Identifiants incorrects.";
    if ($_GET["erreur"] == "suspendu") $erreur = "Votre compte est suspendu. Contactez l'administration.";
    if ($_GET["erreur"] == "role")     $erreur = "Rôle inconnu.";
}

$success = isset($_GET["success"]) ? "Compte créé avec succès. Connectez-vous." : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="collection.php">Collections</a>
        </nav>
        <div class="header-right">
            <a href="connexion.php" class="btn btn-outline btn-sm active">Connexion</a>
            <a href="inscription.php" class="btn btn-gold btn-sm">Inscription</a>
        </div>
    </header>

    <main class="page-main">
        <h1 class="page-title">Connexion</h1>

        <?php if ($erreur): ?>
            <div class="msg msg-error"><?php echo $erreur; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="msg msg-ok"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="../admin/admin_connexion.php">

                <div class="form-group">
                    <label>Identifiant</label>
                    <input type="text" name="login" placeholder="Votre identifiant" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" placeholder="Votre mot de passe" required>
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;">Se connecter</button>

            </form>

            <p style="text-align:center; margin-top:20px; font-size:0.85rem; color:var(--text-muted);">
                Pas encore de compte ? <a href="inscription.php">Créer un compte</a>
            </p>
        </div>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
