<?php 

// Démarrage de la session
session_start();

if (isset($_SESSION['id']) && $_SESSION['id'] != ''){
    echo '<script type="text/javascript">
    window.location.href = \'/home.php\';
    </script>';
    }
    
else {
    // On détruit la séssion si l'utilisateur est déconnecté.
$_SESSION = array();
session_destroy();
    // On défini des cookies vides.
setcookie('login', '');
setcookie('pass_hache', '');
    echo '<script type="text/javascript">
    window.location.href = \'/connexion.php\';
    </script>';
}



?>