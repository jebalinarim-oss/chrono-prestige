<?php
// ========== PHP ==========
session_start();
include("../../config/db_connect.php");

if (!isset($_SESSION["id"]) || $_SESSION["role"] != "vendeur") {
    header("Location: ../connexion.php");
    exit();
}

$uid = (int)$_SESSION["id"];

// Commandes à expédier (statut en_cours)
$res = mysqli_query($conn,
    "SELECT c.*, m.titre, u.username as acheteur_nom, u.email as acheteur_email
     FROM commandes c JOIN montres m ON c.montre_id=m.id JOIN users u ON c.acheteur_id=u.id
     WHERE m.vendeur_id=$uid AND c.statut='en_cours'
     ORDER BY c.date_commande ASC"
);
$a_expedier = [];
while ($row = mysqli_fetch_assoc($res)) $a_expedier[] = $row;

// Commandes déjà expédiées
$res2 = mysqli_query($conn,
    "SELECT c.*, m.titre, u.username as acheteur_nom
     FROM commandes c JOIN montres m ON c.montre_id=m.id JOIN users u ON c.acheteur_id=u.id
     WHERE m.vendeur_id=$uid AND c.statut='expediee'
     ORDER BY c.date_commande DESC"
);
$expediees = [];
while ($row = mysqli_fetch_assoc($res2)) $expediees[] = $row;

// Message
$message      = "";
$message_type = "msg-ok";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "expedition_ok") { $message = "Commande marquée comme expédiée."; }
    if ($_GET["msg"] == "erreur")        { $message = "Erreur."; $message_type = "msg-error"; }
}
?>
<!-- ========== HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expéditions – CHRONO PRESTIGE</title>
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
            <a href="mes_montres.php">Mes annonces</a>
            <a href="mes_ventes.php">Mes ventes</a>
            <a href="expeditions.php" class="active">Expéditions</a>
        </aside>

        <main class="dash-content">
            <h1>Gestion des expéditions</h1>

            <?php if ($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <h2 style="font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; color:#c9a84c; margin-bottom:14px;">
                À expédier (<?php echo count($a_expedier); ?>)
            </h2>

            <?php if (empty($a_expedier)): ?>
                <p style="color:#555; margin-bottom:30px;">Aucune commande en attente d'expédition.</p>
            <?php else: ?>
                <?php foreach ($a_expedier as $c): ?>
                    <div style="background:#111; border:1px solid #1e1e1e; padding:20px; margin-bottom:12px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <span style="color:#c9a84c; font-size:0.85rem;">Commande #<?php echo $c["id"]; ?></span>
                            <span class="badge badge-warn">En cours</span>
                        </div>
                        <div style="color:#e8e0d0; margin-bottom:4px;"><?php echo htmlspecialchars($c["titre"]); ?></div>
                        <div style="color:#666; font-size:0.82rem; margin-bottom:4px;">
                            Acheteur : <?php echo htmlspecialchars($c["acheteur_nom"]); ?> — <?php echo htmlspecialchars($c["acheteur_email"]); ?>
                        </div>
                        <div style="color:#555; font-size:0.82rem; margin-bottom:14px;">
                            Adresse : <?php echo htmlspecialchars($c["adresse_livraison"] ?? "Non renseignée"); ?>
                        </div>
                        <form method="POST" action="../../admin/admin_expedition.php" style="display:flex; gap:10px; align-items:flex-end;">
                            <input type="hidden" name="commande_id" value="<?php echo $c["id"]; ?>">
                            <div class="form-group" style="margin-bottom:0; flex:1;">
                                <label>Numéro de suivi</label>
                                <input type="text" name="numero_suivi" placeholder="Ex: FR123456789FR">
                            </div>
                            <button type="submit" class="btn btn-gold btn-sm">Marquer expédiée</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <h2 style="font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; color:#555; margin-top:30px; margin-bottom:14px;">
                Expédiées (<?php echo count($expediees); ?>)
            </h2>

            <?php foreach ($expediees as $c): ?>
                <div style="background:#0d0d0d; border:1px solid #1a1a1a; padding:16px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span style="color:#555; font-size:0.8rem;">#<?php echo $c["id"]; ?></span>
                        <span style="color:#ccc; margin-left:12px;"><?php echo htmlspecialchars($c["titre"]); ?></span>
                        <span style="color:#666; font-size:0.8rem; margin-left:12px;"><?php echo htmlspecialchars($c["acheteur_nom"]); ?></span>
                        <?php if (!empty($c["numero_suivi"])): ?>
                            <span style="color:#c9a84c; font-size:0.8rem; margin-left:12px;">Suivi: <?php echo htmlspecialchars($c["numero_suivi"]); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="badge badge-gold">Expédiée</span>
                </div>
            <?php endforeach; ?>
        </main>
    </div>

    <footer class="site-footer">© 2026 CHRONO PRESTIGE</footer>
    <script src="../../assets/js/main.js"></script>
</body>
</html>
