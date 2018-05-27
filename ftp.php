<?php
// Déclaration de la classe.
Class FTPClient
{public function __construct() { }
private $connnectionId;
private $loginOk = false;
private $messageArray = array();
private function logMessage($message)
{
$this->messageArray[] = $message;
}
public function getMessages()
{
return $this->messageArray;
}
// Fonction de connexion au serveur FTP
public function connect ($server, $ftpUser, $ftpPassword, $isPassive = false)
{
$this->connectionId = ftp_connect($server);
$loginResult = ftp_login($this->connectionId, $ftpUser, $ftpPassword);
ftp_pasv($this->connectionId, $isPassive);
if ((!$this->connectionId) || (!$loginResult)) {
$this->logMessage('La connexion au serveur FTP a échoué.');
$this->logMessage('Nous avons tenté de nous connecter sur ' . $server . ' en tant que ' . $ftpUser, true);
return false;
} else {
$this->logMessage('La connexion avec ' . $server . 'a été établie en tant que ' . $ftpUser);
$this->loginOk = true;
return true;
}
}
 // La fonction liste l'intégralité des contenus d'un dossier
public function listall ($dir){
$contents = ftp_mlsd($this->connectionId, $dir); // Fonction qui exporte en Array le contenu, nécéssite PHP > 7.2 !
echo '<pre>';
echo '<table style=\'border-collapse: collapse\' width="100%">
<thead>
<tr>
<th style=\'text-align: left;\'>Nom</th>
<th style=\'text-align: left;\'>Type</th>
<th style=\'text-align: left;\'>Dernière modification</th>
<th style=\'text-align: left;\'>Taille</th>
</tr>
</thead>
<tbody>';
foreach($contents as $file) { // On affiche proprement les résultats en prenant soin de distinguer dans l'URL si on veut télécharger un fichier ou ouvrir un dossier.
echo '<tr>
<td><a href="home.php'.($file['type'] == 'file' ? '?get='.$file['name'] : '?dir='.$dir.'/'.$file['name']).'">'.$file['name'].'</a></td>
<td>'.$file['type'].'</td>
<td>'.$file['modify'].'</td>
<td>'.(isset($file['size']) ? $file['size'] : 'Inconnu').'</td>
</tr>';
}
echo '</tbody>
</table>';
echo '</pre>';
}
 // Fonction pour créer un dossier
public function makeDir($directory)
{
if (ftp_mkdir($this->connectionId, $directory)) {
$this->logMessage('Le dossier "' . $directory . '" a bien été créé.');
return true;
} else {
$this->logMessage('Une erreur est survenue lors de la création du dossier "' . $directory . '".');
return false;
}
}
 
 // Fonction pour envoyer un fichier
public function uploadFile ($fileFrom, $fileTo)
{
    // On définit les extensions qui doivent être envoyées en ASCII
$asciiArray = array('txt', 'csv');
$extension = end(explode('.', $fileFrom));
if (in_array($extension, $asciiArray)) {
$mode = FTP_ASCII;      
} else {
$mode = FTP_BINARY;
}
    // On envoie le fichier
$upload = ftp_put($this->connectionId, $fileTo, $fileFrom, $mode);
    // On vérifie que tout s'est déroulé correctement.
if (!$upload) {
$this->logMessage('Echec lors de la transmission.');
return false;
} else {
$this->logMessage('Le fichier "' . $fileFrom . '" a bien été envoyé vers "' . $fileTo);
return true;
}
}
public function downloadFile ($fileFrom, $fileTo)
{
 
    // On définit les extensions qui doivent être téléchargées en ASCII
    $asciiArray = array('txt', 'csv');
    $extension = end(explode('.', $fileFrom));
    if (in_array($extension, $asciiArray)) {
        $mode = FTP_ASCII;      
    } else {
        $mode = FTP_BINARY;
    }
 
    // On démarre le téléchargement et vérifie que tout s'est déroulé correctement.
    if (ftp_get($this->connectionId, $fileTo, $fileFrom, $mode, 0)) {
 
        return true;
        $this->logMessage('Le fichier "' . $fileTo . '" a bien été téléchargé.');
    } else {
 
        return false;
        $this->logMessage('Erreur inconnue lors du téléchargement de "' . $fileFrom . '" vers "' . $fileTo . '"');
    }
 
}

public function __deconstruct()
{
    if ($this->connectionId) {
        ftp_close($this->connectionId);
    }
}

}

?>