<?php include("commun/header.php");  ?>  
    <main>
    <form action="traitement_inscription.php" class="for" method="post" enctype="multipart/form-data">
          <h3 id="titre">Inscription</h3>
            <div class="formulaire">
                <div class="ecrire">
                  <label for="nom">Nom</label>
                  <input type="text" name="nom" class="text" pattern="[A-Za-zÀ-ÿ0-9' ,.-]{1,255}">
                </div>
                <div class="ecrire"> 
                  <label for="prenom">Prénom</label>
                  <input type="text" name="prenom" class="text" pattern="[A-Za-zÀ-ÿ0-9' ,.-]{1,255}">
                </div>
                <div class="ecrire">
                  <label for="ddn">Date de Naissance</label>
                  <input type="date" name="ddn" class="text">
                </div>
                <div class="ecrire">
                  <label for="email">Adresse mail</label>
                  <input type="email" name="email" class="text">
                </div>
                <div class="ecrire">
                  <label for="pseudo">Pseudonyme</label>
                  <input type="text" name="pseudo" class="text">
                </div>
                <div class="ecrire">
                  <label for="mdp">Mot de passe</label>
                  <input type="password" name="mdp" class="text" pattern="[A-Za-z0-9_$]{8,}">
                </div>
                <div class="ecrire">
                  <label for="profil_img">Choisir une image de profil</label>
                  <input type="file" name="profil_img" class="text">
                </div>
                <div class="ecrire valider">
                  <button class="button_menu">Valider Inscription</button>
                </div>
            </div>
        </form>
    </main>
    <?php include("commun/footer.php");  ?>  