<?php
/** Retourne les commandes d'un acheteur. */
function getCommandesByUser($conn, $user_id) {
    $user_id = (int)$user_id;
    $res = mysqli_query($conn,
        "SELECT c.*, m.titre, m.marque, m.prix, m.image
         FROM commandes c
         JOIN montres m ON c.montre_id = m.id
         WHERE c.acheteur_id=$user_id
         ORDER BY c.date_commande DESC"
    );
    $commandes = [];
    while ($row = mysqli_fetch_assoc($res)) $commandes[] = $row;
    return $commandes;
}

/** Retourne les ventes d'un vendeur. */
function getVentesByVendeur($conn, $vendeur_id) {
    $vendeur_id = (int)$vendeur_id;
    $res = mysqli_query($conn,
        "SELECT c.*, m.titre, m.marque, m.prix, u.username as acheteur_nom
         FROM commandes c
         JOIN montres m ON c.montre_id = m.id
         JOIN users u ON c.acheteur_id = u.id
         WHERE m.vendeur_id=$vendeur_id
         ORDER BY c.date_commande DESC"
    );
    $ventes = [];
    while ($row = mysqli_fetch_assoc($res)) $ventes[] = $row;
    return $ventes;
}

/** Retourne toutes les commandes. */
function getAllCommandes($conn) {
    $res = mysqli_query($conn,
        "SELECT c.*, m.titre, m.marque, m.prix, u.username as acheteur_nom
         FROM commandes c
         JOIN montres m ON c.montre_id = m.id
         JOIN users u ON c.acheteur_id = u.id
         ORDER BY c.date_commande DESC"
    );
    $commandes = [];
    while ($row = mysqli_fetch_assoc($res)) $commandes[] = $row;
    return $commandes;
}

/** Vérifie qu'une commande appartient à un vendeur. Retourne true si oui. */
function checkCommandeAppartientVendeur($conn, $commande_id, $vendeur_id) {
    $commande_id = (int)$commande_id;
    $vendeur_id  = (int)$vendeur_id;
    $res = mysqli_query($conn,
        "SELECT c.id FROM commandes c
         JOIN montres m ON c.montre_id=m.id
         WHERE c.id=$commande_id AND m.vendeur_id=$vendeur_id"
    );
    return mysqli_num_rows($res) > 0;
}

/** Retourne le nombre total de commandes. */
function countCommandes($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Retourne le nombre de commandes en cours. */
function countCommandesEnCours($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes WHERE statut='en_cours'");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Retourne le nombre de commandes livrées. */
function countCommandesLivrees($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes WHERE statut='livree'");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Insère une nouvelle commande. */
function createCommande($conn, $acheteur_id, $montre_id, $adresse, $prix) {
    $acheteur_id = (int)$acheteur_id;
    $montre_id   = (int)$montre_id;
    $adresse     = mysqli_real_escape_string($conn, $adresse);
    $prix        = (float)$prix;
    return mysqli_query($conn,
        "INSERT INTO commandes (acheteur_id, montre_id, statut, adresse_livraison, prix_achat)
         VALUES ($acheteur_id, $montre_id, 'en_cours', '$adresse', $prix)"
    );
}

/** Met à jour le statut d'une commande. */
function updateCommandeStatut($conn, $commande_id, $statut) {
    $commande_id = (int)$commande_id;
    $statut      = mysqli_real_escape_string($conn, $statut);
    return mysqli_query($conn, "UPDATE commandes SET statut='$statut' WHERE id=$commande_id");
}

/** Marque une commande comme expédiée avec numéro de suivi. */
function updateExpedition($conn, $commande_id, $numero_suivi) {
    $commande_id  = (int)$commande_id;
    $numero_suivi = mysqli_real_escape_string($conn, $numero_suivi);
    return mysqli_query($conn,
        "UPDATE commandes SET statut='expediee', numero_suivi='$numero_suivi' WHERE id=$commande_id"
    );
}
?>
