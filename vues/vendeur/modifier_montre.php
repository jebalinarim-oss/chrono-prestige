<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: mes_montres.php");
    exit();
}

$uid       = (int)$_SESSION["id"];
$montre_id = (int)$_GET["id"];

// Récupère la montre
$res = mysqli_query($conn, "SELECT * FROM montres WHERE id=$montre_id AND vendeur_id=$uid");
if (mysqli_num_rows($res) == 0) {
    header("Location: mes_montres.php?msg=erreur");
    exit();
}
$m = mysqli_fetch_assoc($res);

if ($m["statut"] == "vendu") {
    header("Location: mes_montres.php?msg=deja_vendu");
    exit();
}

// Catégories
$res_cat = mysqli_query($conn, "SELECT * FROM categories ORDER BY nom");
$categories = [];
while ($c = mysqli_fetch_assoc($res_cat)) $categories[] = $c;
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier – CHRONO PRESTIGE</title>
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
            <h1>Modifier l'annonce</h1>

            <div class="form-card" style="max-width:680px;">
                <form method="POST" action="../../admin/admin_modifier_montre.php" enctype="multipart/form-data">
                    <input type="hidden" name="montre_id" value="<?php echo $montre_id; ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Titre *</label>
                            <input type="text" name="titre" required value="<?php echo htmlspecialchars($m["titre"]); ?>">
                        </div>
                        <div class="form-group">
                            <label>Marque *</label>
                            <input type="text" name="marque" required value="<?php echo htmlspecialchars($m["marque"]); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Prix (€) *</label>
                            <input type="number" name="prix" step="0.01" min="0.01" required value="<?php echo $m["prix"]; ?>">
                        </div>
                        <div class="form-group">
                            <label>Référence</label>
                            <input type="text" name="reference" value="<?php echo htmlspecialchars($m["reference"] ?? ""); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Matériau</label>
                            <input type="text" name="materiau" value="<?php echo htmlspecialchars($m["materiau"] ?? ""); ?>">
                        </div>
                        <div class="form-group">
                            <label>Mouvement</label>
                            <input type="text" name="mouvement" value="<?php echo htmlspecialchars($m["mouvement"] ?? ""); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Couleur</label>
                            <input type="text" name="couleur" value="<?php echo htmlspecialchars($m["couleur"] ?? ""); ?>">
                        </div>
                        <div class="form-group">
                            <label>Catégorie</label>
                            <select name="categorie_id">
                                <option value="">— Sélectionner —</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat["id"]; ?>" <?php echo $m["categorie_id"] == $cat["id"] ? "selected" : ""; ?>>
                                        <?php echo htmlspecialchars($cat["nom"]); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"><?php echo htmlspecialchars($m["description"] ?? ""); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Nouvelle photo (laisser vide = inchangée)</label>
                        <?php if (!empty($m["image"])): ?>
                            <p style="color:#555; font-size:0.8rem; margin-bottom:6px;">Photo actuelle : <?php echo htmlspecialchars($m["image"]); ?></p>
                        <?php endif; ?>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" style="color:#888;">
                    </div>

                    <div style="display:flex; gap:10px;">
                        <button type="submit" class="btn btn-gold">Sauvegarder</button>
                        <a href="mes_montres.php" class="btn btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
