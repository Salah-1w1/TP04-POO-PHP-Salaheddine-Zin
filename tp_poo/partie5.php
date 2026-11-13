<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 5 - sérialisation (__serialize, __unserialize)
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

class Utilisateur
{
    public string $nom;
    public int $age;
    private string $motDePasse;

    public function __construct(string $nom, int $age, string $motDePasse)
    {
        $this->nom = $nom;
        $this->age = $age;
        $this->motDePasse = $motDePasse;
    }

    public function __serialize(): array
    {
        return [
            'nom' => $this->nom,
            'age' => $this->age,
            // $motDePasse est volontairement exclu pour des raisons de sécurité
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->nom = $data['nom'];
        $this->age = $data['age'];
        $this->motDePasse = "";   // à réinitialiser
    }
}

$u = new Utilisateur("Alice", 25, "secret123");
$serialise = serialize($u);
echo "Sérialisé : $serialise" . PHP_EOL;

$restaure = unserialize($serialise);
var_dump($restaure);

// 5.2 : même classe SANS __serialize() : le mot de passe apparaît dans la chaîne
class UtilisateurSansSerialize
{
    public string $nom;
    public int $age;
    private string $motDePasse;

    public function __construct(string $nom, int $age, string $motDePasse)
    {
        $this->nom = $nom;
        $this->age = $age;
        $this->motDePasse = $motDePasse;
    }
}

$x = new UtilisateurSansSerialize("Alice", 25, "secret123");
// Les caractères NUL (\0) entourant le nom des propriétés privées sont rendus visibles
echo PHP_EOL . "--- 5.2 : sans __serialize() ---" . PHP_EOL;
echo str_replace("\0", "\\0", serialize($x)) . PHP_EOL;

/*
 * Réponses : voir reponses.md (questions 5.1 à 5.4).
 */