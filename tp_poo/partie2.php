<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 2 - constructeurs et destructeur
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

// ---------- 2.1 Constructeur avec valeurs par défaut ----------
class Voiture
{
    public string $couleur;
    public int $vitesse;

    public function __construct(string $couleur = "Blanche", int $vitesse = 0)
    {
        $this->couleur = $couleur;
        $this->vitesse = $vitesse;
    }

    public function afficher(): void
    {
        echo "Couleur : {$this->couleur} | Vitesse : {$this->vitesse} km/h" . PHP_EOL;
    }
}

echo "--- 2.1 ---" . PHP_EOL;
$v1 = new Voiture();               // valeurs par défaut
$v2 = new Voiture("Rouge", 50);    // valeurs personnalisées
$v1->afficher();
$v2->afficher();

// 2.1.a : new Voiture("Noire") -> vitesse prend la valeur par défaut (0)
$v3 = new Voiture("Noire");
$v3->afficher();

// new Voiture(120) : en mode non strict, l'entier 120 est converti en chaîne "120"
// et devient la couleur. Avec declare(strict_types=1), ce serait une TypeError.
$v4 = new Voiture(120);
$v4->afficher();

// 2.1.b : promotion de propriétés (PHP 8)
class VoiturePromotion
{
    public function __construct(
        public string $couleur = "Blanche",
        public int $vitesse = 0
    ) {
    }

    public function afficher(): void
    {
        echo "Couleur : {$this->couleur} | Vitesse : {$this->vitesse} km/h" . PHP_EOL;
    }
}

$v5 = new VoiturePromotion("Verte", 80);
$v5->afficher();

// ---------- 2.2 Le piège des deux constructeurs ----------
/*
 * 2.2.a : écrire deux méthodes __construct() dans la même classe provoque :
 * Fatal error: Cannot redeclare Personne::__construct()
 * PHP n'a pas de surcharge de méthodes : une classe n'a qu'un seul constructeur.
 * (Je ne l'écris pas dans ce fichier, car l'erreur fatale arrêterait tout le script.)
 */

// 2.2.b : un seul constructeur avec des valeurs par défaut
class PersonneSouple
{
    public function __construct(
        public string $nom = "",
        public string $prenom = ""
    ) {
        if ($nom === "" && $prenom === "") {
            echo "Création " . __CLASS__ . " (vide)" . PHP_EOL;
        }
    }

    // 2.2.c : méthode statique de fabrique (named constructor)
    public static function depuisTableau(array $data): static
    {
        return new static($data['nom'] ?? "", $data['prenom'] ?? "");
    }
}

echo PHP_EOL . "--- 2.2 ---" . PHP_EOL;
$pVide = new PersonneSouple();
$pComplet = new PersonneSouple("Hachimi", "Sara");
$pTableau = PersonneSouple::depuisTableau(['nom' => 'Benali', 'prenom' => 'Omar']);
echo $pComplet->prenom . " " . $pComplet->nom . PHP_EOL;
echo $pTableau->prenom . " " . $pTableau->nom . PHP_EOL;

// ---------- 2.3 Le destructeur ----------
class VoitureDestruct
{
    public function __construct(public string $couleur)
    {
        echo "Voiture {$this->couleur} créée." . PHP_EOL;
    }

    public function __destruct()
    {
        echo "Voiture {$this->couleur} détruite." . PHP_EOL;
    }
}

echo PHP_EOL . "--- 2.3.a (avec unset) ---" . PHP_EOL;
$maVoiture = new VoitureDestruct("Blanche");
unset($maVoiture);                 // appelle __destruct()
echo "Fin du test a." . PHP_EOL;

echo PHP_EOL . "--- 2.3.b (sans unset) ---" . PHP_EOL;
$sansUnset = new VoitureDestruct("Noire");
echo "Fin du test b : la voiture Noire sera détruite à la fin du script." . PHP_EOL;

echo PHP_EOL . "--- 2.3.c (deux objets, sans unset) ---" . PHP_EOL;
$a = new VoitureDestruct("A");
$b = new VoitureDestruct("B");

// 2.3.d : destructeur dans une classe Personne
class PersonneDestruct
{
    public function __construct(public string $nom)
    {
    }

    public function __destruct()
    {
        echo "Détruire " . __CLASS__ . PHP_EOL;
    }
}

echo PHP_EOL . "--- 2.3.d ---" . PHP_EOL;
$pDestruct = new PersonneDestruct("Hachimi");

echo PHP_EOL . "Fin du script." . PHP_EOL;
// Les objets restants ($sansUnset, $a, $b, $pDestruct) sont détruits APRÈS ce message.
// Noter l'ordre affiché : PHP ne garantit pas d'ordre précis (voir reponses.md).