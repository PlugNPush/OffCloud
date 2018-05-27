<?php 

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=projet isn a 3;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}



session_start();

if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
    
    // Récupération des données sur l'utilisateur
    $req = $bdd->prepare('SELECT * FROM utilisateur WHERE id = ?;');
$req->execute(array($_SESSION['id']));
$test = $req->fetch();

    if (isset($_GET['edit'])){
    if (isset($_POST['mot_de_passe'])){    
$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{
    if (isset($_POST['conf_mot_de_passe']) AND isset($_POST['nouv_mot_de_passe'])) {
    if ($_POST['nouv_mot_de_passe'] == $_POST['conf_mot_de_passe'] AND $_POST['nouv_mot_de_passe'] != ''){
    $pass_hache = password_hash($_POST['conf_mot_de_passe'], PASSWORD_DEFAULT);
    $change = $bdd->prepare('UPDATE utilisateur SET mdp = ? WHERE id = ?');
    $change->execute(array($pass_hache, $test['id']));
    }
    }
    if (isset($_POST['nouveau_email']) AND $_POST['nouveau_email'] != ''){
    $changem = $bdd->prepare('UPDATE utilisateur SET mail = ? WHERE id = ?');
    $changem->execute(array($_POST['nouveau_email'], $test['id']));
    }
    if (isset($_POST['nouveau_nom']) AND $_POST['nouveau_nom'] != ''){
    $changep = $bdd->prepare('UPDATE utilisateur SET nom = ? WHERE id = ?');
    $changep->execute(array($_POST['nouveau_nom'], $test['id']));
    }
    if (isset($_POST['nouveau_serveur']) AND $_POST['nouveau_serveur'] != ''){
    $changesales = $bdd->prepare('UPDATE utilisateur SET adressFTP = ? WHERE id = ?');
    $changesales->execute(array($_POST['nouveau_serveur'], $test['id']));
    }
    if (isset($_POST['nouveau_user']) AND $_POST['nouveau_user'] != ''){
    $changebuys = $bdd->prepare('UPDATE utilisateur SET userFTP = ? WHERE id = ?');
    $changebuys->execute(array($_POST['nouveau_user'], $test['id']));
    }
    if (isset($_POST['nouveau_mdpFTP']) AND $_POST['nouveau_mdpFTP'] != ''){
    $changeblockchain = $bdd->prepare('UPDATE utilisateur SET mdpFTP = ? WHERE id = ?');
    $changeblockchain->execute(array($_POST['nouveau_mdpFTP'], $test['id']));
    }
    $_SESSION = array();
session_destroy();

// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');
    header( "refresh:10;url=connexion.php" );
    echo '<center><h1><b><font size="7" face="verdana">Veuillez patienter...</font></b></h1><p><font size="5" face="verdana">Nous appliquons les changements de votre compte.</font><br>Updating data in the database, this might take up to 15 seconds.</p><img src="https://assets.materialup.com/uploads/53454721-b218-43dc-85ca-cc338ac1915d/webview.gif"></center>';
}
else
{
    header( "refresh:5;url=moncompte.php?edit" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Identifiant ou mot de passe incorrect !</font></b><br><br></h1><p>error: could not check identical password between pass and hash.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}

}
        else {
        echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>MODIFICATION D\'INFORMATIONS</title>
    </head>
    <body>
        <p>Merci de saisir les informations à modifier..</p>
        <form action="moncompte.php?edit" method="post">
            <p>
                <br>Obligatoire pour n\'importe quelle modification : <br>
            <input type="password" name="mot_de_passe" placeholder=\'Mot de passe actuel\' required="yes"/>
            <br> Entrez ici uniquement les valeurs à changer : <br>
            <input type="password" name="nouv_mot_de_passe" placeholder=\'Nouveau mot de passe\'/>
            <input type="password" name="conf_mot_de_passe" placeholder=\'Confirmation du nouveau mot de passe\'/>
            <br><input type="text" name="nouveau_nom" placeholder="Nouveau nom" />
            <br><input type="email" name="nouveau_email" placeholder="Nouvelle adresse mail" />
            <br><br>
            <p>Informations sur le serveur FTP</p>
            <br><input type="text" name="nouveau_serveur" placeholder="Nouveau serveur FTP" />
            <br><input type="text" name="nouveau_user" placeholder="Nouveau nom d\'utilisateur FTP" />
            <br><input type="text" name="nouveau_mdpFTP" placeholder="Nouveau mot de passe FTP" />
            <br><input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';
        }
    }
    
    if (isset($_GET['delete'])){
    if (isset($_POST['mot_de_passe'])){    
$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{
    $delete = $bdd->prepare('DELETE FROM utilisateur WHERE id = ?');
    $delete->execute(array($test['id']));
    
    $deletea = $bdd->prepare('DELETE FROM donnees WHERE id = ?');
    $deletea->execute(array($test['id']));
    
    $deleteb = $bdd->prepare('DELETE FROM partage WHERE id = ?');
    $deleteb->execute(array($test['id']));
    
    $deletec = $bdd->prepare('DELETE FROM partage WHERE idreceveur = ?');
    $deletec->execute(array($test['id']));
    
    $_SESSION = array();
session_destroy();

// Suppression des cookies de connexion automatique
setcookie('login', '');
setcookie('pass_hache', '');
    
    
    
    header( "refresh:10;url=index.php" );
    echo '<center><h1><b><font size="7" face="verdana">Suppression du compte...</font></b></h1><p><font size="5" face="verdana">Toutes vos données et les données associées à ce compte seront supprimés.</font><br>Removing data to the database, this might take up to 15 seconds.</p><img src=https://assets.materialup.com/uploads/53454721-b218-43dc-85ca-cc338ac1915d/webview.gif ></center>';
    
}
else
{
    header( "refresh:5;url=moncompte.php?delete" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Mot de passe incorrect !</font></b><br><br></h1><p>error: could not check identical password between pass and hash.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}}
        else {
        echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>SUPPRIMER VOTRE COMPTE</title>
    </head>
    <body>
        <p>Voulez-vous vraiment définitivement supprimer votre compte ?</p>
        <form action="moncompte.php?delete" method="post">
            <p>
            <input type="password" name="mot_de_passe" placeholder=\'Mot de passe\'/>
            <br><input type="checkbox" name="confirmation" value="yes" required="yes" /> Je confirme vouloir supprimer mon compte et toutes les données associées à celui-ci, et je comprends que cette opération est irréversible.<br>
            <input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';}
    }    
    
    if (isset($_GET['view'])){
if (isset($_POST['mot_de_passe'])){
    
    
$verify = password_verify($_POST['mot_de_passe'], $test['mdp']);
if ($verify)
{
    
    echo '<h1><b>Vos données confidentielles.</b></h1>';
    echo '<p><a href=/home.php>Retour à l\'accueil</a></p>';
    echo '<br><p>Nom : ', $test['nom'];
    echo '<br>Adresse e-mail : ', $test['mail'];
    echo '<br>Identifiant d\'utilisateur unique : ', $test['id'];
    echo '<br>Hash du mot de passe : ', $test['mdp'];
    echo '<br>Adresse du serveur FTP : ', $test['adressFTP'];
    echo '<br>Nom d\'utilisateur FTP : ', $test['userFTP'];
    echo '<br>Mot de passe FTP : ', $test['mdpFTP'];
    echo '<br><a href=/moncompte.php?edit>Modifier des informations</a></p>';
    echo '<br><br><a href=/moncompte.php?delete>Supprimer définitivement le compte</a></p>';
    
}
    else {
    header( "refresh:5;url=moncompte.php?view" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Mot de passe incorrect !</font></b><br><br></h1><p>error: could not verify given pass with hash.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
    }} else {
    echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>MON COMPTE</title>
    </head>
    <body>
        <p>Vous devez vous authentifier pour continuer.</p>
        <form action="moncompte.php?view" method="post">
            <p>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required="yes"/>
            <input type="submit" value="Valider" />
            </p>
            </form>
    </body>
</html>';
}}}
else {
$_SESSION = array();
session_destroy();
setcookie('login', '');
setcookie('pass_hache', '');
header( "refresh:5;url=connexion.php" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Vous n\'êtes pas connecté !</font></b><br><br></h1><p>error: could not check session variable.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}

?>
