<?php

// Classe Utils : Contient des méthodes utilitaires.
class Utils {
    // Génère un nombre aléatoire entre min et max.
    public static function generateRandomNumber($min, $max) {
        return rand($min, $max);
    }
}

// Classe Character : Classe de base pour les personnages du jeu.
class Character {
    public $nom;
    public $billes;

    // Constructeur : Initialise un personnage avec un nom et un nombre de billes.
    public function __construct($nom, $billes) {
        $this->nom = $nom;
        $this->billes = $billes;
    }

    // Augmente le nombre de billes du personnage.
    public function gagner($nombre) {
        $this->billes += $nombre;
    }

    // Réduit le nombre de billes du personnage, sans descendre en dessous de zéro.
    public function perdre($nombre) {
        $this->billes = max($this->billes - $nombre, 0);
    }
}

// Classe Hero : Héritée de Character, représente un héros avec des bonus et malus.
class Hero extends Character {
    public $bonus;
    public $malus;

    // Constructeur : Initialise un héros avec des caractéristiques spécifiques.
    public function __construct($nom, $billes, $bonus, $malus) {
        parent::__construct($nom, $billes);
        $this->bonus = $bonus;
        $this->malus = $malus;
    }

    // Choisit aléatoirement entre 'pair' et 'impair'.
    public function choisirPairImpair() {
        return Utils::generateRandomNumber(0, 1) == 0 ? "pair" : "impair";
    }

    // Vérifie si un nombre est pair ou impair.
    public function verifierPairImpair($nombre) {
        return ($nombre % 2 == 0) ? "pair" : "impair";
    }
}

// Classe Ennemi : Héritée de Character, représente un ennemi du jeu.
class Ennemi extends Character {
    public $age;

    // Constructeur : Initialise un ennemi avec un nom, un âge et un nombre aléatoire de billes.
    public function __construct($nom, $age) {
        $billes = Utils::generateRandomNumber(1, 20);
        parent::__construct($nom, $billes);
        $this->age = $age;
    }
}

// Classe Jeu : Gère la logique du jeu.
class Jeu {
    private $heros; // Tableau des héros disponibles.
    private $ennemis; // Tableau des ennemis.
    private $hero; // Le héros sélectionné pour jouer.

    // Constructeur : Initialise le jeu avec des héros et génère des ennemis.
    public function __construct() {
        // Crée des héros
        $this->heros = [
            new Hero("Seong Gi-hun", 15, 1, 2),
            new Hero("Kang Sae-byeok", 25, 2, 1),
            new Hero("Cho Sang-woo", 35, 3, 0)
        ];

        // Génère des ennemis
        $this->ennemis = [];
        for ($i = 0; $i < 20; $i++) {
            $this->ennemis[] = new Ennemi("Ennemi " . ($i + 1), Utils::generateRandomNumber(20, 60));
        }
    }

    // Méthode pour démarrer et gérer le jeu.
    public function demarrerJeu() {
        // Sélectionne le premier héros par défaut pour jouer
        $this->hero = $this->heros[0]; 

        // Boucle de jeu : Continue tant que le héros a des billes et qu'il reste des ennemis.
        while (!empty($this->ennemis) && $this->hero->billes > 0) {
            $ennemi = array_pop($this->ennemis); // Sélectionne le prochain ennemi.
            
            // Affiche les détails de la rencontre.
            echo "Rencontre avec {$ennemi->nom} qui a {$ennemi->billes} billes.\n";
            $choix = $this->hero->choisirPairImpair(); // Le héros fait un choix.
            $resultat = $this->hero->verifierPairImpair($ennemi->billes); // Vérifie le résultat.

            echo "Le héros pense que l'ennemi a un nombre de billes : {$choix}.\n";

            // Gère le résultat du combat.
            if ($choix == $resultat) {
                $this->hero->gagner($ennemi->billes + $this->hero->bonus);
                echo "Victoire ! Le héros gagne " . ($ennemi->billes + $this->hero->bonus) . " billes.\n";
            } else {
                $this->hero->perdre($ennemi->billes + $this->hero->malus);
                echo "Défaite. Le héros perd " . ($ennemi->billes + $this->hero->malus) . " billes.\n";
            }

            // Affiche le nombre de billes restant au héros.
            echo "Billes restants du héros : " . $this->hero->billes . "\n\n";
        }

        // Affiche le résultat final du jeu.
        if ($this->hero->billes > 0) {
            echo "Victoire finale !";
        } else {
            echo "Défaite finale.";
        }
    }
}

// Création et lancement du jeu
$jeu = new Jeu();
$jeu->demarrerJeu();
