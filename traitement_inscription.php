<?php
include_once("commun/inc.php");

session_start();

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user,$db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : "";
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : "";
    $ddn = isset($_POST['ddn']) ? htmlspecialchars(trim($_POST['ddn'])) : "";
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : "";
    $pseudo = isset($_POST['pseudo']) ? htmlspecialchars(trim($_POST['pseudo'])) : "";
    $mdp = isset($_POST['mdp']) ? htmlspecialchars(trim($_POST['mdp'])) : "";
    
    $erreurs = [];

    // Validation des champs avec expressions régulières
    if (!preg_match("/^[A-Za-zÀ-ÿ0-9' ,.-]{1,255}$/", $nom)) {
        $erreurs["nom"] = "Le format du nom est invalide";
    }
    if (!preg_match("/^[A-Za-zÀ-ÿ0-9' ,.-]{1,255}$/", $prenom)) {
        $erreurs["prenom"] = "Le format du prénom est invalide";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs["email"] = "Le format de l'adresse mail est invalide";
    }
    // if (!preg_match("/^[A-Za-zÀ-ÿ0-9' ,.-]{1,255}$/", $pseudo)) {
    //     $erreurs["pseudo"] = "Le format du pseudo est invalide";
    // }
    if (!preg_match("/^[A-Za-z0-9$]{8,}$/", $mdp)) {
        $erreurs["mdp"] = "Le format du mot de passe n'est pas valide";
    }


    // Vérification et traitement de l'image
    if (isset($_FILES['profil_img']) && $_FILES['profil_img']['error'] == 0) { // Vérifie si un fichier a été téléchargé et s'il n'y a pas eu d'erreur
    $dossier = 'imageprofil/'; // Dossier de destination pour les images téléchargées
    $fichier = basename($_FILES['profil_img']['name']); // Récupère le nom de base du fichier téléchargé
    $taille_maxi = 2000000; // Taille maximale de l'image en octets (2MB)
    $taille = filesize($_FILES['profil_img']['tmp_name']); // Récupère la taille du fichier téléchargé
    $extensions = array('.png', '.gif', '.jpg', '.jpeg'); // Extensions autorisées pour les fichiers image
    $extension = strrchr($fichier, '.'); // Récupère l'extension du fichier

    // Vérifications des conditions de l'image
    if (!in_array($extension, $extensions)) { // Vérifie si l'extension n'est pas autorisée
        $erreurs["profil_img"] = "Vous devez uploader un fichier de type PNG, GIF, JPG, JPEG."; // Ajoute un message d'erreur pour une extension non autorisée
    }
    if ($taille > $taille_maxi) { // Vérifie si la taille du fichier dépasse la taille maximale autorisée
        $erreurs["profil_img"] = "Le fichier est trop gros."; // Ajoute un message d'erreur pour un fichier trop gros
    }
    if (empty($erreurs["profil_img"])) { // Vérifie s'il n'y a pas d'erreurs d'image
        // On formate le nom du fichier ici pour éviter les problèmes de caractères spéciaux
        $fichier = strtr($fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåçèéêëìíîïðñòóôõöøùúûüýþÿ',
            'AAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaceeeeiiiionoooooouuuuyby');
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier); // Remplace les caractères non autorisés par des tirets
        if (move_uploaded_file($_FILES['profil_img']['tmp_name'], $dossier . $fichier)) { // Déplace le fichier téléchargé vers le dossier de destination
            $profil_img = $dossier . $fichier; // Définit le chemin de l'image si le téléchargement a réussi
        } else {
            $erreurs["profil_img"] = "Échec de l'upload de l'image."; // Ajoute un message d'erreur en cas d'échec du déplacement du fichier
        }
    }
} else {
    $erreurs["profil_img"] = "Erreur lors du téléchargement de l'image."; // Ajoute un message d'erreur si une erreur s'est produite lors du téléchargement
}

    // Affichage des erreurs s'il y en a
    if (count($erreurs) > 0) {
        $_SESSION["donnees"] = $_POST;
        $_SESSION["erreurs"] = $erreurs;
        echo "Désolé, les champs ne sont pas corrects";
        echo "<a href='inscription.php'>Retour à la page d'inscription</a>";
        exit();
    }

    // Préparation des données pour l'insertion
    $tableauDonnees = [
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':ddn' => $ddn,
        ':email' => $email,
        ':pseudo' => $pseudo,
        ':mdp' => $mdp,
        ':profil_img' => $profil_img
    ];

    // Cryptage du mot de passe
    $tableauDonnees[":mdp"] = password_hash($mdp, PASSWORD_BCRYPT);

    // Insertion des données dans la base
    $sql = "INSERT INTO client (nom, prenom, ddn, email, pseudo, mdp, profil_img) 
            VALUES (:nom, :prenom, :ddn, :email, :pseudo, :mdp, :profil_img)";
    $qry = $pdo->prepare($sql);
    $qry->execute($tableauDonnees);

    echo ('<script type="text/javascript">
    alert("Vous êtes bien inscrit");
    window.location.href="connexion.php";
    </script>');

} catch (PDOException $err) {
    // Gestion des erreurs
    $_SESSION["compte-erreur-sql"] = $err->getMessage();

    echo ('<script type="text/javascript">
    alert("Les champs ne sont pas valides");
    window.location.href="inscription.php";
    </script>');
    
    exit();
} finally {
    $pdo = null; // Fermer la connexion
}
?>