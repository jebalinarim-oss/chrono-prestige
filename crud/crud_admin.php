<?php
/** Retourne les stats du dashboard. */
function getDashboardStats($conn) {
    $r1 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM users");
    $r2 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM montres WHERE statut='disponible'");
    $r3 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes WHERE statut='en_cours'");
    $r4 = mysqli_query($conn, "SELECT COUNT(*) as nb FROM commandes WHERE statut='livree'");

    return [
        "nb_users"    => (int)mysqli_fetch_assoc($r1)["nb"],
        "nb_dispo"    => (int)mysqli_fetch_assoc($r2)["nb"],
        "nb_en_cours" => (int)mysqli_fetch_assoc($r3)["nb"],
        "nb_livrees"  => (int)mysqli_fetch_assoc($r4)["nb"],
    ];
}

/** Retourne toutes les catégories avec le nombre de montres associées. */
function getAllCategoriesAvecComptage($conn) {
    $res = mysqli_query($conn,
        "SELECT c.*, COUNT(m.id) as nb_montres
         FROM categories c
         LEFT JOIN montres m ON c.id = m.categorie_id
         GROUP BY c.id ORDER BY c.nom"
    );
    $categories = [];
    while ($row = mysqli_fetch_assoc($res)) $categories[] = $row;
    return $categories;
}

/** Retourne toutes les catégories. */
function getAllCategories($conn) {
    $res = mysqli_query($conn, "SELECT * FROM categories ORDER BY nom ASC");
    $categories = [];
    while ($row = mysqli_fetch_assoc($res)) $categories[] = $row;
    return $categories;
}

/** Insère une nouvelle catégorie. */
function addCategorie($conn, $nom) {
    $nom = mysqli_real_escape_string($conn, $nom);
    return mysqli_query($conn, "INSERT IGNORE INTO categories (nom) VALUES ('$nom')");
}

/** Met à jour le nom d'une catégorie. */
function updateCategorie($conn, $id, $nom) {
    $id  = (int)$id;
    $nom = mysqli_real_escape_string($conn, $nom);
    return mysqli_query($conn, "UPDATE categories SET nom='$nom' WHERE id=$id");
}

/** Supprime une catégorie. */
function deleteCategorie($conn, $id) {
    $id = (int)$id;
    return mysqli_query($conn, "DELETE FROM categories WHERE id=$id");
}
?>
