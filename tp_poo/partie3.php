<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 3 - surcharge de propriétés (__get, __set, __isset, __unset)
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

class Personne
{
    private string $nom = "";
    private string $prenom = "";
    private string $dateNaissance = "";

    public function __set(string $name, mixed $value): void
    {
        // N'autoriser que les propriétés existantes
        if (!property_exists($this, $name)) {
            echo "Propriété $name inexistante" . PHP_EOL;
            return;
        }

        // 3.4 : validation du nom (non vide et au moins 2 caractères)
        if ($name === "nom") {
            if (!is_string($value) || strlen(trim($value)) < 2) {
                throw new InvalidArgumentException(
                    "Le nom ne doit pas être vide et doit contenir au moins 2 caractères."
                );
            }
        }

        $this->$name = $value;
    }

    public function __get(string $name): mixed
    {
        // Retourne la valeur si la propriété existe, sinon null
        return property_exists($this, $name) ? $this->$name : null;
    }

    public function __isset(string $name): bool
    {
        // Vrai si la propriété existe et n'est pas vide
        return property_exists($this, $name) && $this->$name !== "";
    }

    public function __unset(string $name): void
    {
        // Remet la propriété à "" (chaîne vide)
        if (property_exists($this, $name)) {
            $this->$name = "";
        }
    }
}

$p = new Personne();
$p->nom = "Hachimi";                // -> __set
echo $p->nom . PHP_EOL;             // -> __get
var_dump(isset($p->nom));           // -> __isset : bool(true)
unset($p->nom);                     // -> __unset
var_dump(isset($p->nom));           // -> bool(false)
$p->telephone = "0600000000";       // propriété inexistante -> message

// 3.4 : test de la validation
echo PHP_EOL . "--- Validation ---" . PHP_EOL;
try {
    $p->nom = "X";
} catch (InvalidArgumentException $e) {
    echo "Exception : " . $e->getMessage() . PHP_EOL;
}

// 3.2 : sans __set, écrire dans une propriété privée provoque une erreur
class PersonneSansMagie
{
    private string $nom = "";
}

echo PHP_EOL . "--- 3.2 : sans __set ---" . PHP_EOL;
try {
    $x = new PersonneSansMagie();
    $x->nom = "X";
} catch (Error $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

/*
 * Réponses : voir reponses.md (questions 3.1 à 3.4).
 */