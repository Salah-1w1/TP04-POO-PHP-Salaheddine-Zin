<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 1 - visibilité et encapsulation (classe Personne)
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

class Personne
{
    // ----- Attributs publics -----
    public string $nom;
    public string $prenom;

    // ----- Attributs privés -----
    private string $dateNaissance;   // format "AAAA-MM-JJ"
    private string $villeNaissance;
    private string $cin;

    // ----- Constructeur -----
    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->villeNaissance = $villeNaissance;
        $this->cin = $cin;
    }

    // ----- Méthode publique -----
    public function getName(): string
    {
        return $this->prenom . " " . $this->nom;
    }

    // ----- Méthode privée -----
    private function getAge(): int
    {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();
        return $naissance->diff($aujourdhui)->y;
    }
}

// Programme de test
$p = new Personne("Hachimi", "Sara", "2000-05-14", "Marrakech", "EE123456");

echo $p->getName() . PHP_EOL;   // Sara Hachimi
echo $p->nom . PHP_EOL;         // Hachimi

// Expérimentation : chaque accès est tenté dans un try/catch pour noter l'erreur
$experiences = [
    'echo $p->cin' => fn() => $p->cin,
    'echo $p->dateNaissance' => fn() => $p->dateNaissance,
    'echo $p->getAge()' => fn() => $p->getAge(),
];

echo PHP_EOL . "--- Expérimentation ---" . PHP_EOL;
foreach ($experiences as $libelle => $experience) {
    try {
        echo $experience();
    } catch (Error $e) {
        echo $libelle . " -> " . $e->getMessage() . PHP_EOL;
    }
}

/*
 * Réponses : voir reponses.md (questions 1.1 à 1.4).
 */