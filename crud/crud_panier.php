<?php
/** Retourne tous les articles du panier d'un utilisateur. */
function getPanierByUser($conn, $user_id) {
    $user_id = (int)$user_id;
    $res = mysqli_query($conn,
        "SELECT p.*, m.titre, m.marque, m.prix, m.image, m.statut as montre_statut
         FROM panier p
         JOIN montres m ON p.montre_id = m.id
         WHERE p.acheteur_id=$user_id"
    );
    $items = [];
    while ($row = mysqli_fetch_assoc($res)) $items[] = $row;
    return $items;
}

/** Retourne une ligne du panier si elle existe, sinon false. */
function getPanierItem($conn, $user_id, $montre_id) {
    $user_id   = (int)$user_id;
    $montre_id = (int)$montre_id;
    $res = mysqli_query($conn,
        "SELECT id, quantite FROM panier WHERE acheteur_id=$user_id AND montre_id=$montre_id"
    );
    if (mysqli_num_rows($res) == 0) return false;
    return mysqli_fetch_assoc($res);
}

/** Retourne le nombre d'articles dans le panier. */
function countPanier($conn, $user_id) {
    $user_id = (int)$user_id;
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM panier WHERE acheteur_id=$user_id");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Retourne le total du panier. */
function getTotalPanier($conn, $user_id) {
    $user_id = (int)$user_id;
    $res = mysqli_query($conn,
        "SELECT SUM(m.prix * p.quantite) as total
         FROM panier p
         JOIN montres m ON p.montre_id = m.id
         WHERE p.acheteur_id=$user_id"
    );
    $row = mysqli_fetch_assoc($res);
    return $row["total"] ? (float)$row["total"] : 0;
}

/** Ajoute une montre au panier. */
function addToPanier($conn, $user_id, $montre_id) {
    $user_id   = (int)$user_id;
    $montre_id = (int)$montre_id;
    return mysqli_query($conn,
        "INSERT INTO panier (acheteur_id, montre_id, quantite) VALUES ($user_id, $montre_id, 1)"
    );
}

/** Met à jour la quantité d'un article du panier. */
function updatePanierQuantite($conn, $panier_id, $quantite) {
    $panier_id = (int)$panier_id;
    $quantite  = (int)$quantite;
    return mysqli_query($conn, "UPDATE panier SET quantite=$quantite WHERE id=$panier_id");
}

/** Retire un article du panier. */
function removeFromPanier($conn, $panier_id, $user_id) {
    $panier_id = (int)$panier_id;
    $user_id   = (int)$user_id;
    return mysqli_query($conn, "DELETE FROM panier WHERE id=$panier_id AND acheteur_id=$user_id");
}

/** Vide entièrement le panier d'un utilisateur. */
function viderPanier($conn, $user_id) {
    $user_id = (int)$user_id;
    return mysqli_query($conn, "DELETE FROM panier WHERE acheteur_id=$user_id");
}
?>
