<?php include("commun/header.php");  ?>  
    <main>
    <form action="traitement_connexion.php" class="for" method="post">
          <h3 id="titre">Connexion</h3>
            <div class="formulaire">
                <div class="ecrire">
                  <label for="email">Adresse mail</label>
                  <input type="email" name="email" class="text">
                </div>
                <div class="ecrire">
                  <label for="mdp">Mot de passe</label>
                  <input type="password" name="mdp" class="text" pattern="[A-Za-z0-9_$]{8,}">
                </div>
                <div class="ecrire valider">
                  <button class="button_menu">Valider</button>
                </div>
            </div>
        </form>
    </main>
    <?php include("commun/footer.php");  ?>  