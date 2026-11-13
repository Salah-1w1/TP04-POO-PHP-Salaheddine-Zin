<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 4 - représentation textuelle (__toString)
 * Date : 13/11/2026
 */

declare(strict_types=1);

header('Content-Type: text/plain; charset=UTF-8');

class Personne
{
    public string $nom;
    public string $prenom;
    private string $dateNaissance;
    private string $villeNaissance;
    private string $cin;

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

    // getName() est supprimée : __toString() la remplace.

    private function getAge(): int
    {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();
        return $naissance->diff($aujourdhui)->y;
    }

    // Format : Prénom Nom (âge ans)
    public function __toString(): string
    {
        return $this->prenom . " " . $this->nom . " (" . $this->getAge() . " ans)";
    }
}

$p = new Personne("Hachimi", "Sara", "2000-05-14", "Marrakech", "EE123456");
echo $p . PHP_EOL;

// 4.2 : __toString() qui retourne un entier au lieu d'une chaîne
class PersonneEntier
{
    public function __toString(): string
    {
        return 42;   // entier : incorrect
    }
}

echo PHP_EOL . "--- 4.2 ---" . PHP_EOL;
try {
    echo new PersonneEntier();
} catch (TypeError $e) {
    echo "TypeError : " . $e->getMessage() . PHP_EOL;
}

/*
 * Réponses : voir reponses.md (questions 4.1 à 4.3).
 */