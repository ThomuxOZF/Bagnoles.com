<?php
include_once("commun/inc.php");
session_start();

$email = (isset($_POST["email"]) && !empty($_POST["email"])) ? htmlspecialchars($_POST["email"]) : "null";
$mdp = (isset($_POST["mdp"]) && !empty($_POST["mdp"])) ? htmlspecialchars($_POST["mdp"]) : "null";
$erreurs = [];

// Vérification de l'email
if ($email && $mdp){

    include_once("commun/inc.php");

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        // Options de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $qry=$pdo->prepare("SELECT * FROM client WHERE email=?");
        $qry->execute(array($email));
        $data_user = $qry->fetch();
        // verification de mdp et email
        if($data_user && password_verify($mdp, $data_user["mdp"])){
            // definition du nom grace à la session
            $_SESSION["nom"]=$data_user["nom"];
            header("location:accueil.php");
            
        }else{
            echo "L'email ou le mot de passe ne sont pas corrects!";
        }

    }catch (PDOException $err) {
            // Gestion des erreurs
            $_SESSION["compte-erreur-sql"] = $err->getMessage();
            header("location:connexion.php");
            exit();
    }

}else{
    echo "Compte introuvable!";
}

?>