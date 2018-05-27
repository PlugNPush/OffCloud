<?php
if(!isset($_POST['pass']) AND !isset($_POST['mdp'])){
echo'
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>INSCRIPTION</title>
    </head>
    <body>
        <p>Bienvenue chez nous! Plus qu\'une étape avant d\'accéder à votre serveur FTP !</p>
        <form action="inscription.php" method="post">
            <p>
            <input type="email" name="mail" placeholder="Votre mail" required="yes"/><br>
            <input type="text" name="nom" placeholder="Votre nom d\'utilisateur" required="yes"/><br>
            <input type="password" name="mdp" placeholder="Votre mot de passe" required="yes"/>
            <input type="password" name="pass" placeholder="Confirmation du mot de passe" required="yes"/>
            <input type="submit" value="Valider" />
            <br> Vous avez déjà un compte ? <a href=/connexion.php>Connectez-vous !</a>
            </p>
            </form>
    </body>
</html>';
}else{

// Vérification de la validité des informations
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=projet isn a 3;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}


// Insertion
$req = $bdd->prepare('INSERT INTO utilisateur(mail, mdp, nom) VALUES(:mail, :pass, :nom)');

if (isset($_POST['mdp']) AND isset($_POST['pass']) AND $_POST['mdp'] == $_POST['pass']) {
    

  
        
    $pass_hache = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $req->execute(array(
    'mail' => $_POST['mail'],
    'pass' => $pass_hache,
    'nom' => $_POST['nom']
    ));
        
        // Retour à la connexion
header( "refresh:7;url=connexion.php" );
echo '<p><center><b> <font size="6" face="verdana">Veuillez patienter...</font></b><br> Writing new data into the database, this may take up to 10 seconds. You will be soon redirected to the login page.<br><br><br>

<img src="https://blog.pojo.me/wp-content/uploads/sites/140/2016/05/Optimized-WordPress-Installation.gif" ></center></p>';
}
else {
    // L'utilisateur n'a pas correctement saisi la confirmation du mot de passe :
header( "refresh:5;url=inscription.php" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Vos mots de passes n\'ont pas pu être sauvegardés ! Verifiez bien que vous avez saisi correctement la confirmation de mot de passe!</font></b><br><br></h1><p>error: could not check identical password between $mdp and $pass.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}}
?>