<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 6 - héritage (extends, parent::, final, readonly)
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

// ---------- 6.1 Animal, Chien, Chat ----------
class Animal
{
    public function manger(): void
    {
        echo "Je mange." . PHP_EOL;
    }
}

class Chien extends Animal
{
    public function aboyer(): void
    {
        echo "Wouf !" . PHP_EOL;
    }
}

// 6.1.a : classe Chat
class Chat extends Animal
{
    public function miauler(): void
    {
        echo "Miaou !" . PHP_EOL;
    }
}

echo "--- 6.1 ---" . PHP_EOL;
$c = new Chien();
$c->manger();    // héritée d'Animal
$c->aboyer();    // propre à Chien

$chat = new Chat();
$chat->manger();
$chat->miauler();

// 6.1.b : un Chien ne peut pas miauler
try {
    $c->miauler();
} catch (Error $e) {
    echo "6.1.b -> " . $e->getMessage() . PHP_EOL;
}

// ---------- 6.2 parent:: et redéfinition ----------
class PersonneSimple
{
    public function __construct(protected string $nom)
    {
    }

    public function presentation(): string
    {
        return "Je suis " . $this->nom;
    }
}

class EtudiantSimple extends PersonneSimple
{
    public function __construct(string $nom, private string $filiere)
    {
        parent::__construct($nom);   // appelle le constructeur du parent
    }

    public function presentation(): string
    {
        return parent::presentation() . ", étudiant en " . $this->filiere;
    }
}

echo PHP_EOL . "--- 6.2 ---" . PHP_EOL;
$e1 = new EtudiantSimple("Ahmed", "Informatique");
echo $e1->presentation() . PHP_EOL;

// 6.2.a : sans parent::__construct($nom)
class EtudiantSansParent extends PersonneSimple
{
    public function __construct(string $nom, private string $filiere)
    {
        // parent::__construct($nom) volontairement oublié
    }
}

try {
    $e2 = new EtudiantSansParent("Ahmed", "Informatique");
    echo $e2->presentation() . PHP_EOL;
} catch (Error $e) {
    echo "6.2.a -> " . $e->getMessage() . PHP_EOL;
}

// ---------- 6.3 final et readonly ----------
class Base
{
    final public function afficher(): void
    {
        echo "Méthode finale" . PHP_EOL;
    }
}

final class Outil
{
    public const VERSION = "1.0";
}

class Produit
{
    public function __construct(public readonly string $reference)
    {
    }
}

echo PHP_EOL . "--- 6.3 ---" . PHP_EOL;
$base = new Base();
$base->afficher();
echo "Outil version " . Outil::VERSION . PHP_EOL;

$prod = new Produit("REF-001");
echo $prod->reference . PHP_EOL;

/*
 * 6.3.a (1) class Fille extends Base { public function afficher() ... }
 *     -> Fatal error: Cannot override final method Base::afficher()
 *     (2) class SousOutil extends Outil {}
 *     -> Fatal error: Class SousOutil cannot extend final class Outil
 *     Ces deux erreurs sont fatales à la compilation : je ne les écris pas ici
 *     pour ne pas arrêter le script. Il suffit de les tester une par une dans un
 *     fichier à part.
 */

// (3) modification d'une propriété readonly
try {
    $prod->reference = "REF-002";
} catch (Error $e) {
    echo "6.3.a (3) -> " . $e->getMessage() . PHP_EOL;
}

// ---------- 6.4 Exercice principal : Etudiant extends Personne ----------
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

    // protected : utilisable par les classes filles (voir question 6.4.a)
    protected function getAge(): int
    {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();
        return $naissance->diff($aujourdhui)->y;
    }

    public function __toString(): string
    {
        return $this->prenom . " " . $this->nom . " (" . $this->getAge() . " ans)";
    }
}

class Etudiant extends Personne
{
    private string $cne;

    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin,
        string $cne
    ) {
        parent::__construct($nom, $prenom, $dateNaissance, $villeNaissance, $cin);
        $this->cne = $cne;
    }

    public function getCne(): string
    {
        return $this->cne;
    }

    // Accès à la méthode protégée de la classe mère
    public function getAgeEtudiant(): int
    {
        return $this->getAge();
    }

    public function __toString(): string
    {
        return parent::__toString() . " - CNE : " . $this->cne;
    }
}

$etudiants = [];
$etudiants[] = new Etudiant("Hachimi", "Sara", "2003-05-14", "Marrakech", "EE123456", "G130001");
$etudiants[] = new Etudiant("Benali", "Omar", "2002-11-02", "Casablanca", "AB234567", "G130002");
$etudiants[] = new Etudiant("Alaoui", "Salma", "2004-01-20", "Rabat", "CD345678", "G130003");
$etudiants[] = new Etudiant("Drissi", "Yassine", "2003-08-30", "Fès", "EF456789", "G130004");
$etudiants[] = new Etudiant("Chraibi", "Lina", "2002-03-09", "Agadir", "GH567890", "G130005");

echo PHP_EOL . "--- 6.4 ---" . PHP_EOL;
foreach ($etudiants as $e) {
    echo $e . PHP_EOL;
}
echo "Âge du premier étudiant (via méthode protégée) : "
    . $etudiants[0]->getAgeEtudiant() . " ans" . PHP_EOL;

// 6.4.a et 6.4.b : que se passe-t-il quand la classe mère utilise private ?
class PersonneTest
{
    private string $dateNaissance = "2000-01-01";

    private function getAge(): int
    {
        return 26;
    }
}

class EtudiantTest extends PersonneTest
{
    public function essayerGetAge(): string
    {
        try {
            return (string) $this->getAge();
        } catch (Error $e) {
            return $e->getMessage();
        }
    }

    public function essayerDateNaissance(): string
    {
        return isset($this->dateNaissance)
            ? "accessible"
            : "inaccessible (private dans la classe mère)";
    }
}

$test = new EtudiantTest();
echo PHP_EOL . "6.4.a -> " . $test->essayerGetAge() . PHP_EOL;
echo "6.4.b -> dateNaissance : " . $test->essayerDateNaissance() . PHP_EOL;