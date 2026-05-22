<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

// Catégories
$res_cat = mysqli_query($conn, "SELECT * FROM categories ORDER BY nom");
$categories = [];
while ($c = mysqli_fetch_assoc($res_cat)) $categories[] = $c;

// Message
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "ajout_ok")    { $message = "Montre publiée avec succès !"; }
    if ($_GET["msg"] == "erreur")      { $message = "Une erreur est survenue."; $message_type = "msg-error"; }
}
if (isset($_GET["erreur"]) && $_GET["erreur"] == "champs") {
    $message = "Veuillez remplir les champs obligatoires.";
    $message_type = "msg-error";
}
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une montre – CHRONO PRESTIGE</title>
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
            <a href="ajouter_montre.php" class="active">+ Ajouter une montre</a>
            <a href="mes_montres.php">Mes annonces</a>
            <a href="mes_ventes.php">Mes ventes</a>
        </aside>

        <main class="dash-content">
            <h1>Publier une annonce</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <div class="form-card" style="max-width:680px;">
                <form method="POST" action="../../admin/admin_produit.php" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="ajouter">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Titre *</label>
                            <input type="text" name="titre" required placeholder="Ex: Submariner Date">
                        </div>
                        <div class="form-group">
                            <label>Marque *</label>
                            <input type="text" name="marque" required placeholder="Ex: Rolex">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Prix (€) *</label>
                            <input type="number" name="prix" step="0.01" min="0.01" required placeholder="9500.00">
                        </div>
                        <div class="form-group">
                            <label>Référence</label>
                            <input type="text" name="reference" placeholder="Ex: 126610LN">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Matériau</label>
                            <input type="text" name="materiau" placeholder="Ex: Acier inoxydable">
                        </div>
                        <div class="form-group">
                            <label>Mouvement</label>
                            <input type="text" name="mouvement" placeholder="Ex: Automatique Cal. 3235">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Couleur</label>
                            <input type="text" name="couleur" placeholder="Ex: Bleu nuit">
                        </div>
                        <div class="form-group">
                            <label>Catégorie</label>
                            <select name="categorie_id">
                                <option value="">— Sélectionner —</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat["id"]; ?>"><?php echo htmlspecialchars($cat["nom"]); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" placeholder="État de la montre, historique, accessoires inclus..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Photo (jpg, jpeg, png, webp)</label>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" style="color:#888;">
                    </div>

                    <button type="submit" class="btn btn-gold">Publier l'annonce</button>
                </form>
            </div>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
