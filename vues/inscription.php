<?php
// ========== PHP ==========
session_start();

$erreur = "";
$msg    = "";

if (isset($_GET["erreur"])) {
    if ($_GET["erreur"] == "role")   $erreur = "Rôle incorrect.";
    if ($_GET["erreur"] == "existe") $erreur = "Ce login ou email est déjà utilisé.";
}

if (isset($_GET["success"])) {
    $msg = "Compte créé avec succès ! Vous pouvez vous connecter.";
}
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription – CHRONO PRESTIGE</title>
    <link rel="stylesheet" href="../assets/css2/main.css">
    <link rel="stylesheet" href="../assets/css2/boutique.css">

</head>
<body>

    <header class="site-header">
        <a href="../index.php" class="logo">CHRONO PRESTIGE</a>
        <nav>
            <a href="../index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
        </nav>
        <div class="header-right">
            <a href="connexion.php" class="btn btn-outline btn-sm">Connexion</a>
            <a href="inscription.php" class="btn btn-gold btn-sm active">Inscription</a>
        </div>
    </header>

    <main class="page-main" style="max-width:540px;">
        <h1 class="page-title">Créer un compte</h1>

        <?php if ($msg): ?>
            <div class="msg msg-ok"><?php echo $msg; ?></div>
        <?php endif; ?>

        <?php if ($erreur): ?>
            <div class="msg msg-error"><?php echo $erreur; ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="../admin/admin_inscription.php">

                <div class="form-row">
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="prenom" required placeholder="Sophie">
                    </div>
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="nom" required placeholder="Martin">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Âge *</label>
                        <input type="number" name="age" required min="16" max="120" placeholder="28">
                    </div>
                    <div class="form-group">
                        <label>Type de compte *</label>
                        <select name="role" required>
                            <option value="client">Acheteur</option>
                            <option value="vendeur">Vendeur</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Identifiant *</label>
                    <input type="text" name="login" required placeholder="sophie_martin">
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required placeholder="sophie@exemple.fr">
                </div>

                <div class="form-group">
                    <label>Mot de passe *</label>
                    <input type="password" name="mdp" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;">Créer mon compte</button>
            </form>

            <p style="text-align:center; margin-top:20px; color:#666; font-size:0.85rem;">
                Déjà inscrit ? <a href="connexion.php">Se connecter</a>
            </p>
        </div>
    </main>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE — Tous droits réservés</footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
