<?php 
$file = $_GET['get'];

if (file_exists($file)){
    
    // Le fichier $file est intercepté, on peut le traiter à volonté ici avant de le recevoir.
    // On peut se servir de cet espace pour déchiffrer le fichier
    // Mais on pourrait imaginer plusieurs autres usages, comme par exemple renommer ou éditer.
    // ATTENTION : NE RIEN AFFICHER ICI (echo) CAR LES HEADERS N'ONT PAS ÉTÉS ENVOYÉS !
    
    // Calcul de la taille du fichier
    
    // Ouverture du fichier pour le téléchargement
    $fh = fopen( $file, 'rb' );
    ob_end_clean( ); ob_start( );
    
    // Calcul de la taille du fichier
    $size = filesize($file);
    
    // On prévient le navigateur qu'il va télécharger le fichier et on lui donne quelques informations dessus.
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $size);
    
    // Téléchargement plus économe en RAM, contrairement au readfile();
    ob_clean( ); flush( ); 
    set_time_limit(  0 );
    
    while ( !feof( $fh ) ) {
   echo  fgets( $fh );
   ob_flush();
   flush();
}
    @fclose( $file );
    
    // Suppression du fichier en TT
unlink($file);
exit;
    }

else {
    echo 'Une erreur est survenue.';
}
?>