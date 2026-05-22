<?php
/** Retourne une montre avec le nom du vendeur (pour la fiche détail). */
function getMontreDetail($conn, $id) {
    $id  = (int)$id;
    $res = mysqli_query($conn,
        "SELECT m.*, u.username as vendeur_nom
         FROM montres m LEFT JOIN users u ON m.vendeur_id=u.id
         WHERE m.id=$id AND m.masquee=0"
    );
    if (mysqli_num_rows($res) == 0) return null;
    return mysqli_fetch_assoc($res);
}

/** Retourne une montre par son id. */
function getMontreById($conn, $id) {
    $id = (int)$id;
    $res = mysqli_query($conn, "SELECT * FROM montres WHERE id=$id");
    return mysqli_fetch_assoc($res);
}

/** Recherche les montres disponibles par texte (titre, marque). */
function getMontresSearch($conn, $search = "") {
    $where = "WHERE statut='disponible' AND masquee=0";
    if ($search !== "") {
        $s      = mysqli_real_escape_string($conn, $search);
        $where .= " AND (titre LIKE '%$s%' OR marque LIKE '%$s%')";
    }
    $res     = mysqli_query($conn, "SELECT * FROM montres $where ORDER BY date_ajout DESC");
    $montres = [];
    while ($row = mysqli_fetch_assoc($res)) $montres[] = $row;
    return $montres;
}

/** Retourne toutes les montres disponibles. */
function getAllMontresDisponibles($conn) {
    $res = mysqli_query($conn, "SELECT * FROM montres WHERE statut='disponible' ORDER BY date_ajout DESC");
    $montres = [];
    while ($row = mysqli_fetch_assoc($res)) $montres[] = $row;
    return $montres;
}

/** Retourne les montres d'un vendeur. */
function getMontresByVendeur($conn, $vendeur_id) {
    $vendeur_id = (int)$vendeur_id;
    $res = mysqli_query($conn, "SELECT * FROM montres WHERE vendeur_id=$vendeur_id ORDER BY date_ajout DESC");
    $montres = [];
    while ($row = mysqli_fetch_assoc($res)) $montres[] = $row;
    return $montres;
}

/** Retourne toutes les montres avec le nom du vendeur. */
function getAllMontres($conn) {
    $res = mysqli_query($conn,
        "SELECT m.*, u.username as vendeur_nom
         FROM montres m LEFT JOIN users u ON m.vendeur_id=u.id
         ORDER BY m.date_ajout DESC"
    );
    $montres = [];
    while ($row = mysqli_fetch_assoc($res)) $montres[] = $row;
    return $montres;
}

/** Retourne les montres groupées par marque. */
function getMontresGroupedByMarque($conn) {
    $res = mysqli_query($conn, "SELECT * FROM montres ORDER BY marque ASC, date_ajout DESC");
    $collections = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $collections[$row["marque"]][] = $row;
        }
    }
    return $collections;
}

/**
 * Retourne les montres (non masquées) avec filtres optionnels, groupées par marque.
 * $search        : recherche texte (titre, marque, description)
 * $filtre_marque : filtrer sur une marque précise
 * $prix_tri      : "asc" | "desc" | ""
 */
function getMontresFiltreesGroupees($conn, $search = "", $filtre_marque = "", $prix_tri = "") {
    $where = "WHERE masquee=0";
    if ($search !== "") {
        $s      = mysqli_real_escape_string($conn, $search);
        $where .= " AND (titre LIKE '%$s%' OR marque LIKE '%$s%' OR description LIKE '%$s%')";
    }
    if ($filtre_marque !== "") {
        $fm     = mysqli_real_escape_string($conn, $filtre_marque);
        $where .= " AND marque='$fm'";
    }
    $order = "ORDER BY marque ASC, date_ajout DESC";
    if ($prix_tri === "asc")  $order = "ORDER BY prix ASC";
    if ($prix_tri === "desc") $order = "ORDER BY prix DESC";

    $res         = mysqli_query($conn, "SELECT * FROM montres $where $order");
    $collections = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $nom = ($row["marque"] !== "") ? $row["marque"] : "Autres";
            $collections[$nom][] = $row;
        }
    }
    return $collections;
}

/** Retourne la liste distincte des marques disponibles (non masquées). */
function getMarquesDisponibles($conn) {
    $res     = mysqli_query($conn, "SELECT DISTINCT marque FROM montres WHERE masquee=0 AND marque != '' ORDER BY marque");
    $marques = [];
    while ($row = mysqli_fetch_assoc($res)) $marques[] = $row["marque"];
    return $marques;
}

/** Vérifie qu'une montre est disponible. Retourne la ligne ou false. */
function getMontreDisponible($conn, $id) {
    $id  = (int)$id;
    $res = mysqli_query($conn, "SELECT id FROM montres WHERE id=$id AND statut='disponible'");
    if (mysqli_num_rows($res) == 0) return false;
    return mysqli_fetch_assoc($res);
}

/** Vérifie qu'une montre appartient à un vendeur. Retourne la ligne ou false. */
function getMontreByVendeur($conn, $montre_id, $vendeur_id) {
    $montre_id  = (int)$montre_id;
    $vendeur_id = (int)$vendeur_id;
    $res = mysqli_query($conn, "SELECT id, statut FROM montres WHERE id=$montre_id AND vendeur_id=$vendeur_id");
    if (mysqli_num_rows($res) == 0) return false;
    return mysqli_fetch_assoc($res);
}

/** Retourne le nombre total de montres. */
function countMontres($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM montres");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Retourne le nombre de montres disponibles. */
function countMontresDisponibles($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM montres WHERE statut='disponible'");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Insère une nouvelle montre. */
function addMontre($conn, $titre, $marque, $prix, $description, $image, $vendeur_id) {
    $titre       = mysqli_real_escape_string($conn, $titre);
    $marque      = mysqli_real_escape_string($conn, $marque);
    $prix        = (float)$prix;
    $description = mysqli_real_escape_string($conn, $description);
    $image       = mysqli_real_escape_string($conn, $image);
    $vendeur_id  = (int)$vendeur_id;
    return mysqli_query($conn,
        "INSERT INTO montres (titre, marque, prix, description, image, statut, vendeur_id, date_ajout)
         VALUES ('$titre', '$marque', $prix, '$description', '$image', 'disponible', $vendeur_id, NOW())"
    );
}

/** Met à jour les informations d'une montre. */
function updateMontre($conn, $montre_id, $titre, $marque, $prix, $description, $reference, $materiau, $mouvement, $couleur, $categorie_id) {
    $montre_id   = (int)$montre_id;
    $titre       = mysqli_real_escape_string($conn, $titre);
    $marque      = mysqli_real_escape_string($conn, $marque);
    $prix        = (float)$prix;
    $description = mysqli_real_escape_string($conn, $description);
    $reference   = mysqli_real_escape_string($conn, $reference);
    $materiau    = mysqli_real_escape_string($conn, $materiau);
    $mouvement   = mysqli_real_escape_string($conn, $mouvement);
    $couleur     = mysqli_real_escape_string($conn, $couleur);
    $cat         = ($categorie_id > 0) ? (int)$categorie_id : "NULL";
    return mysqli_query($conn,
        "UPDATE montres SET
            titre='$titre', marque='$marque', prix=$prix, description='$description',
            reference='$reference', materiau='$materiau', mouvement='$mouvement',
            couleur='$couleur', categorie_id=$cat
         WHERE id=$montre_id"
    );
}

/** Met à jour l'image d'une montre. */
function updateMontreImage($conn, $montre_id, $nom_fichier) {
    $montre_id   = (int)$montre_id;
    $nom_fichier = mysqli_real_escape_string($conn, $nom_fichier);
    return mysqli_query($conn, "UPDATE montres SET image='$nom_fichier' WHERE id=$montre_id");
}

/** Met à jour le statut d'une montre. */
function updateMontreStatut($conn, $montre_id, $statut) {
    $montre_id = (int)$montre_id;
    $statut    = mysqli_real_escape_string($conn, $statut);
    return mysqli_query($conn, "UPDATE montres SET statut='$statut' WHERE id=$montre_id");
}

/** Met à jour la visibilité d'une montre (masquée ou non). */
function updateMontreVisibilite($conn, $montre_id, $masquee) {
    $montre_id = (int)$montre_id;
    $masquee   = (int)$masquee;
    return mysqli_query($conn, "UPDATE montres SET masquee=$masquee WHERE id=$montre_id");
}

/** Met à jour le statut d'authenticité d'une montre. */
function updateMontreAuthentique($conn, $montre_id, $authentique) {
    $montre_id = (int)$montre_id;
    if ($authentique === "1") {
        return mysqli_query($conn, "UPDATE montres SET authentique=1 WHERE id=$montre_id");
    } elseif ($authentique === "0") {
        return mysqli_query($conn, "UPDATE montres SET authentique=0 WHERE id=$montre_id");
    } else {
        return mysqli_query($conn, "UPDATE montres SET authentique=NULL WHERE id=$montre_id");
    }
}

/** Supprime une montre. */
function deleteMontre($conn, $montre_id) {
    $montre_id = (int)$montre_id;
    return mysqli_query($conn, "DELETE FROM montres WHERE id=$montre_id");
}

/** Supprime une montre appartenant à un vendeur précis. */
function deleteMontreByVendeur($conn, $montre_id, $vendeur_id) {
    $montre_id  = (int)$montre_id;
    $vendeur_id = (int)$vendeur_id;
    return mysqli_query($conn, "DELETE FROM montres WHERE id=$montre_id AND vendeur_id=$vendeur_id");
}
?>
