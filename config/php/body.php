<?php

// Instanciation des joueurs
$joueur1 = isset($_SESSION['joueur1']) ? $_SESSION['joueur1'] : null;
$joueur2 = isset($_SESSION['joueur2']) ? $_SESSION['joueur2'] : null;

$classes = array('Tireur', 'Mage', 'Support', 'Combattant');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validateClass'])) {
    $joueur1_perso = $_POST['selectedClass_joueur1'];
    $joueur2_perso = $_POST['selectedClass_joueur2'];

        if ($joueur1_perso === 'Combattant') {
            $joueur1 = new Combattant(300, 50, 30, 20); 
        } elseif ($joueur1_perso === 'Mage') {
            $joueur1 = new Mage(250, 40, 80, 30); 
        } elseif ($joueur1_perso === 'Support') {
            $joueur1 = new Support(130, 120, 30, 20); 
        } elseif ($joueur1_perso === 'Tireur') {
            $joueur1 = new Tireur(200, 50, 100, 20);
        }


        if ($joueur2_perso === 'Combattant') {
            $joueur2 = new Combattant(300, 50, 30, 20);
        } elseif ($joueur2_perso === 'Mage') {
            $joueur2 = new Mage(250, 40, 80, 30);
        } elseif ($joueur2_perso === 'Support') {
            $joueur2 = new Support(125, 60, 70, 20);
        } elseif ($joueur2_perso === 'Tireur') {
            $joueur2 = new Tireur(200, 50, 100, 20);
        }

    }

    // Logique d'attaque
    if ($joueur1 && $joueur2) {

        // Attaque du joueur 1
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attaque_joueur1'])) {
            // Calcul des dégâts
            $joueur1->degats($joueur2);
    
            $attaque_joueur1_disabled = 'disabled';
    
            // Vérification si le joueur 2 est mort
            if ($joueur2->getPv() <= 0) {
                $fin_du_jeu = true;
                echo "Joueur 1 a gagné!";
            }
        }
    
        // Si une attaque est effectuée par le joueur 2
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attaque_joueur2'])) {
            // Calcul des dégâts
            $joueur2->degats($joueur1);
    
            $attaque_joueur2_disabled = 'disabled';
    
            // Vérification si le joueur 1 est mort
            if ($joueur1->getPv() <= 0) {
                $fin_du_jeu = true;
                echo "Joueur 2 a gagné!";
            }
        }
        // Si une attaque spéciale est effectuée par le joueur 1
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attaque_speciale_joueur1'])) {
            // Application des dégâts spéciaux au joueur 2
            $attaque_joueur1_disabled = 'disabled';
            $attaque_special_joueur1_disabled = 'disabled';
            $joueur1->attakSpec($joueur2);
    
            // Vérification si le joueur 2 est mort
            if (!$joueur2->estVivant()) {
                $fin_du_jeu = true;
                echo "Joueur 1 a gagné!";
            }
        }
    
        // Si une attaque spéciale est effectuée par le joueur 2
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attaque_speciale_joueur2'])) {
            // Application des dégâts spéciaux au joueur 1
            $attaque_joueur2_disabled = 'disabled';
            $attaque_special_joueur2_disabled = 'disabled';
            $joueur2->attakSpec($joueur1);
    
            // Vérification si le joueur 1 est mort
            if (!$joueur1->estVivant()) {
                $fin_du_jeu = true;
                echo "Joueur 2 a gagné!";
            }
        }
    }

    $_SESSION['joueur1'] = $joueur1;
    $_SESSION['joueur2'] = $joueur2;

?>

<div class="centerDiv">

<h1>Combat au tour par tour</h1>

<!-- Formulaire combat -->

<form method="post">
    <label for="selectedClassjoueur1">Choisir une classe :</label>
    <select name="selectedClass_joueur1" id="selectedClassjoueur1">
        <?php
        foreach ($classes as $class) {
            echo '<option value="' . $class . '">' . $class . '</option>';
        }

        ?>
    </select>

    <label for="selectedClassjoueur2">Choisir une classe :</label>
    <select name="selectedClass_joueur2" id="selectedClassjoueur2">
        <?php
        foreach ($classes as $class) {
            echo '<option value="' . $class . '">' . $class . '</option>';
        }

        ?>
    </select>

    <button type="submit" name="validateClass" class="valid">Valider la classe</button>
</form>

<div>
    <form method="post">
        <button type="submit" name="attaque_joueur1" class="playButton" <?php echo isset($attaque_joueur1_disabled) ? 'disabled' : ''; ?>>Attaquer Joueur 2</button>
        <button type="submit" name="attaque_speciale_joueur1" <?php echo isset($attaque_special_joueur1_disabled) ? 'disabled' : ''; ?>><?php echo isset($_SESSION['joueur1']) ? $joueur1->attakSpecName() : 'Classe non choisie'; ?></button>
    </form>

    <form method="post">
        <button type="submit" name="attaque_joueur2" class="playButton" <?php echo isset($attaque_joueur2_disabled) ? 'disabled' : ''; ?>>Attaquer Joueur 1</button>
        <button type="submit" name="attaque_speciale_joueur2" <?php echo isset($attaque_special_joueur2_disabled) ? 'disabled' : ''; ?>><?php echo isset($_SESSION['joueur2']) ? $joueur2->attakSpecName() : 'Classe non choisie'; ?></button>
    </form>
</div>

<?php
    if (!isset($_SESSION['validateClass']) || $_POST['validateClass']) : ?>

        <div>
            <?php
            // Affichage des points de vie
            echo "<br>";
            echo "PV du joueur 1 : " . ($joueur1 ? $joueur1->pv : "Classe non choisie") . "<br>";
            echo "PV du joueur 2 : " . ($joueur2 ? $joueur2->pv : "Classe non choisie") . "<br>";
            echo "<br>";

            ?>
        </div>
    <?php endif; ?>

    <a href="?reset=true">Réinitialiser le jeu</a>
</div>