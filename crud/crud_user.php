<?php
/** Retourne un utilisateur par son id. */
function getUserById($conn, $id) {
    $id = (int)$id;
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    return mysqli_fetch_assoc($res);
}

/** Retourne un utilisateur par son login. */
function getUserByLogin($conn, $login) {
    $login = mysqli_real_escape_string($conn, $login);
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$login'");
    return mysqli_fetch_assoc($res);
}

/** Retourne tous les utilisateurs. */
function getAllUsers($conn) {
    $res = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
    $users = [];
    while ($row = mysqli_fetch_assoc($res)) $users[] = $row;
    return $users;
}

/** Retourne le nombre total d'utilisateurs. */
function countUsers($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) as nb FROM users");
    return (int)mysqli_fetch_assoc($res)["nb"];
}

/** Vérifie login + mot de passe, retourne la ligne user ou false. */
function loginUser($conn, $login, $mdp) {
    $login = mysqli_real_escape_string($conn, $login);
    $mdp   = mysqli_real_escape_string($conn, $mdp);
    $res   = mysqli_query($conn, "SELECT * FROM users WHERE username='$login' AND password='$mdp'");
    if (mysqli_num_rows($res) == 1) {
        return mysqli_fetch_assoc($res);
    }
    return false;
}

/** Vérifie si un login OU un email est déjà pris. Retourne true si existant. */
function checkLoginOrEmailExists($conn, $login, $email) {
    $login = mysqli_real_escape_string($conn, $login);
    $email = mysqli_real_escape_string($conn, $email);
    $res   = mysqli_query($conn, "SELECT id FROM users WHERE username='$login' OR email='$email'");
    return mysqli_num_rows($res) > 0;
}

/** Vérifie si un email appartient à un autre utilisateur. Retourne true si pris. */
function checkEmailTakenByOther($conn, $email, $current_user_id) {
    $email           = mysqli_real_escape_string($conn, $email);
    $current_user_id = (int)$current_user_id;
    $res = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id != $current_user_id");
    return mysqli_num_rows($res) > 0;
}

/** Insère un nouvel utilisateur, retourne son nouvel id. */
function createUser($conn, $login, $nom, $prenom, $age, $email, $mdp, $role) {
    $login  = mysqli_real_escape_string($conn, $login);
    $nom    = mysqli_real_escape_string($conn, $nom);
    $prenom = mysqli_real_escape_string($conn, $prenom);
    $age    = (int)$age;
    $email  = mysqli_real_escape_string($conn, $email);
    $mdp    = mysqli_real_escape_string($conn, $mdp);
    $role   = mysqli_real_escape_string($conn, $role);
    mysqli_query($conn,
        "INSERT INTO users (username, nom, prenom, age, email, password, role)
         VALUES ('$login', '$nom', '$prenom', $age, '$email', '$mdp', '$role')"
    );
    return mysqli_insert_id($conn);
}

/** Met à jour le profil d'un utilisateur. */
function updateUserProfile($conn, $id, $nom, $prenom, $email, $age) {
    $id     = (int)$id;
    $nom    = mysqli_real_escape_string($conn, $nom);
    $prenom = mysqli_real_escape_string($conn, $prenom);
    $email  = mysqli_real_escape_string($conn, $email);
    $age    = (int)$age;
    return mysqli_query($conn, "UPDATE users SET nom='$nom', prenom='$prenom', email='$email', age=$age WHERE id=$id");
}

/** Met à jour le mot de passe d'un utilisateur. */
function updateUserPassword($conn, $id, $mdp) {
    $id  = (int)$id;
    $mdp = mysqli_real_escape_string($conn, $mdp);
    return mysqli_query($conn, "UPDATE users SET password='$mdp' WHERE id=$id");
}

/** Met à jour le rôle d'un utilisateur. */
function updateUserRole($conn, $id, $role) {
    $id   = (int)$id;
    $role = mysqli_real_escape_string($conn, $role);
    return mysqli_query($conn, "UPDATE users SET role='$role' WHERE id=$id");
}

/** Suspend ou réactive un utilisateur. */
function setSuspenduUser($conn, $id, $suspendu) {
    $id       = (int)$id;
    $suspendu = (int)$suspendu;
    return mysqli_query($conn, "UPDATE users SET suspendu=$suspendu WHERE id=$id");
}

/** Supprime un utilisateur. */
function deleteUser($conn, $id) {
    $id = (int)$id;
    return mysqli_query($conn, "DELETE FROM users WHERE id=$id");
}

?>
