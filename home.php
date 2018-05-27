<?php 
// Connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=projet isn a 3;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
// Démarrage de la session
session_start();

if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
    // Récupération des données sur l'utilisateur
    $req = $bdd->prepare('SELECT * FROM utilisateur WHERE id = ?;');
$req->execute(array($_SESSION['id']));
$test = $req->fetch();
    
    // Zone de menu statique
echo '<h1><b><font size="7" face="verdana">Bonjour ', $test['nom'], ' ! </font></b></h1>';
echo '<p align="right"> <a href=/moncompte.php?view>Mon compte</a> | <a href=/logout.php>Se déconnecter.</a></p>';
  // Préparation de l'environement FTP
if (isset($test['adressFTP']) && $test['adressFTP'] != ''){
define('FTP_HOST', $test['adressFTP']);
define('FTP_USER', $test['userFTP']);
define('FTP_PASS', $test['mdpFTP']);

include('ftp.php');
// On fait appel à la classe FTP
$ftpObj = new FTPClient();
// Connexion FTP
$ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS);
if($ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS)){
// print_r($ftpObj -> getMessages()); // Diagnostic de connexion positif
echo '<p>Pour changer de dossier, entrez le nom du dossier ci-dessous.</p>
<form action="home.php" method="post">
<input type="text" name="dir" placeholder="Nom du dossier" />
<input type="submit" value="Acceder au dossier" />
</form>';
    // On s'assure d'avoir défini un dossier FTP à consulter
if (isset($_POST['dir'])){
$_SESSION['dir'] = $_POST['dir'];
}
else if (isset($_GET['dir'])){
    $_SESSION['dir'] = $_GET['dir'];
    }
else {
if (!isset($_SESSION['dir'])){
$_SESSION['dir'] = '/';
}
}
    // Confirmation du fonctionnement du dossier FTP et proposition d'y envoyer un fichier
echo '<h3>Vous êtes actuellement dans le dossier '.$_SESSION['dir'].'</h3>';
echo '<form method="post" action="home.php" enctype="multipart/form-data">
<label for="file">Envoyer un fichier sur le serveur FTP :</label><br />
<input type="file" name="file" id="file" /><br />
<input type="submit" name="submit" value="Envoyer" />
</form>';
    // On propose aussi de créer un dossier
echo '<br/><form action="home.php" method="post">
<input type="text" name="creadir" placeholder="Nom du dossier" />
<input type="submit" value="Créer dossier" />
</form>';

    if (isset ($_POST['creadir'])){
    $ftpObj -> makeDir($_SESSION['dir'].'/'.$_POST['creadir']);
    echo 'Le dossier a bien été créé !';
    }
    
    
    // Méthode d'envoi du fichier
if (isset ($_FILES['file']))
{
    // Identification du fichier en TT
$nom = basename($_FILES["file"]["name"]);
echo "Mise en place du Transit Temporaire. Veuillez patienter.<br/>";
    // Le fichier temporaire est déplacé en tant que fichier permanent à la racine pour pouvoir le traiter
$resultat = move_uploaded_file($_FILES['file']['tmp_name'],$nom);
if ($resultat){ 

    // Ici nous devions chiffrer le fichier, mais par manque de temps et de moyens,
    // nous n'avons pas réussi à développer le script de chiffrement.
    
    // Nous avions développé un premier script qui utilisait une fonction non mise-à-jour
    // depuis 2007 et impossible à installer sur le Mac avec MAMP.
    
    // Le script :
    /*
    $cle = md5(uniqid(rand(), true)); 
    $checksum = md5_file ($nom);
	encrypt_file($nom, $nom, $cle);
	$reqfiles = $bdd->prepare('INSERT INTO donnees (id, checksum, nomdufichier, crypt) VALUES (:id,:checksum, :nomdufichier, :crypt'));
	$reqfiles->execute(array(
	'id' => $_SESSION['id'],
	'checksum' => $checksum,
	'nom du fichier' => $nom,
	'crypt' => $cle));
    
    public function encrypt_file($file, $destination, $passphrase) { 
    $handle = fopen($file, "rb") or die("Erreur lors de l'ouverture du fichier."); 
    $contents = fread($handle, filesize($file));
    fclose($handle); 
 
    $iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
    $key = substr(md5("\x2D\xFC\xD8".$passphrase, true) . md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
    $opts = array('iv'=>$iv, 'key'=>$key);
    $fp = fopen($destination, 'wb') or die("Impossible de lire le fichier (permissions incorrectes ?).");
        
    // Le stream Mcrypt n'existe plus depuis 2007 !
    stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts); 
    fwrite($fp, $contents) or die("Impossible d'écrire le fichier.");
    fclose($fp); 
 
}
    */
    
    
    // Transfert du fichier au serveur FTP
$ftpObj -> uploadFile($nom, $_SESSION['dir']."/".$nom);
    echo 'Fichier envoyé ! Suppression du fichier en Transit Temporaire...';
    // Suppression du fichier en TT
unlink($nom);
    echo '<script type="text/javascript">
    window.location.href = \'/home.php\';
    </script>';
} else {
    echo 'Erreur lors de la procédure de Transit Temporaire.';
}
}
else{
    // Lister le contenu du dossier
$ftpObj -> listall($_SESSION['dir']);
}

    // Télécharger un fichier.
    if (isset($_GET['get'])){
    echo "Mise en place du Transit Temporaire. Veuillez patienter.<br/>";    
    // Télécharger un fichier
    $ftpObj -> downloadFile($_SESSION['dir']."/".$_GET['get'], $_GET['get']);
        
        // Ici nous devions déchiffrer le fichier, mais nous n'avons pas pu le faire
        // Se référer à la ligne 82.
        
    echo '<script type="text/javascript">
    window.location.href = \'/download.php/?get='.$_GET['get'].'\';
    </script>';
    }
    
    
    
}else{
echo 'Une erreur est survenue.<br/> ';
print_r($ftpObj -> getMessages());	
}
       }else{
    // Demander à l'utilisateur de renseigner ses infromations de connexion au serveur FTP
	  if(!isset($_POST['adressFTP']) && !isset($_POST['userFTP']) && !isset($_POST['mdpFTP'])){
	 
	  echo '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>VOTRE SERVEUR FTP</title>
    </head>
    <body>
        <p>Merci de renseigner vos coordonnées FTP</p>
        <form action="home.php" method="post">
            <p>
            <input type="text" name="adressFTP" placeholder="Adresse FTP" />
           <input type="text" name="userFTP" placeholder="Nom d\'utilisateur FTP" />
		    <input type="password" name="mdpFTP" placeholder=\'Mot de passe FTP\'/>
            <input type="submit" value="Valider" />
    
            </p>
        
      
            </form>
    </body>
</html>';
	  } else { 
	  try
{
	$bdd = new PDO('mysql:host=localhost;dbname=projet isn a 3;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}


// Insertion des données du serveur FTP dans la BDD
$req = $bdd->prepare('UPDATE utilisateur SET adressFTP = ?, userFTP = ?, mdpFTP = ? WHERE id = ?' );


   
    $req->execute(array(
    $_POST['adressFTP'],
    $_POST['userFTP'],
    $_POST['mdpFTP'],
	$test['id']
    ));
        
        // Confirmation positive
header( "refresh:7;url=backhome.php" );
echo '<p><center><b> <font size="6" face="verdana">Veuillez patienter...</font></b><br> Writing new data into the database, this may take up to 10 seconds. You will be soon redirected to the login page.<br><br><br>

<img src="https://blog.pojo.me/wp-content/uploads/sites/140/2016/05/Optimized-WordPress-Installation.gif" ></center></p>';

	 
 }
 }}
    
else {
    // On détruit la séssion si l'utilisateur est déconnecté.
$_SESSION = array();
session_destroy();
    // On défini des cookies vides.
setcookie('login', '');
setcookie('pass_hache', '');
    // On renvoie sur la page de connexion.
header( "refresh:5;url=connexion.php" );
echo '<html><body bgcolor="#CC0033">
        <center>
        <h1><b><font size="35" style="font-family:verdana;" style="text-align:center;" style="vertical-align:middle;" color="white">Erreur ! Vous n\'êtes pas connecté !</font></b><br><br></h1><p>error: could not check session variable.</p>
      
<img src="https://i.pinimg.com/originals/45/41/38/454138b3dad33d8fc66082083e090d06.gif" >
        </center></body></html>';
}



?>