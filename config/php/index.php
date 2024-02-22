<?php
require_once 'classes/personnage.php';
require_once 'classes/combattant.php';
require_once 'classes/mage.php';
require_once 'classes/support.php';
require_once 'classes/tireur.php';

if (isset($_GET['reset'])) {
    ob_start(); // Démarrez la sortie tamponnée
    echo "Ceci est une sortie avant header()";
    header("Location: index.php"); // Maintenant, cela fonctionne correctement
    exit();
    ob_end_flush(); // Envoyez le contenu du tampon au navigateur
}

session_start();
?>

    <!-- connexion à la bdd -->
    <?php
    require_once 'db.php';
    ?>

    <?php
    include  'header.php';
    ?>

    <?php
    include  'body.php';
    ?>

    <!-- footer -->
    <?php
    include  'footer.php';
    ?>