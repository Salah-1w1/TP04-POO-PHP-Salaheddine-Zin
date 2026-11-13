<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 7 - mise en pratique : le compte bancaire (code corrigé)
 * Date : 13/11/2026
 */

header('Content-Type: text/plain; charset=UTF-8');

/*
 * Problèmes du code de l'étudiant :
 * 7.1 Erreur de syntaxe dans un_an() : il manque l'opérateur "+" entre
 *     $this->montant et 1 : "$this->montant 1 $this->interet / 100".
 * 7.2 Formule fausse : montant * (montant + 1 + interet / 100).
 *     Bonne formule avec un intérêt de t % : montant * (1 + t / 100).
 * 7.3 L'ordre des paramètres ($interet, $montantInitial) ne suit pas l'énoncé
 *     ("un montant initial et un intérêt") : on risque d'inverser les valeurs à
 *     l'appel, sans aucune erreur. Ordre corrigé : ($montantInitial, $interet).
 * 7.4 Types float ajoutés, types de retour ajoutés, affichage avec number_format().
 *
 * Les noms un_an() et get_montant() sont ceux imposés par l'énoncé.
 */
class Compte
{
    private float $montant;
    private float $interet;   // taux annuel, en pourcentage

    public function __construct(float $montantInitial, float $interet)
    {
        $this->montant = $montantInitial;
        $this->interet = $interet;
    }

    // Montant au bout d'un an : montant * (1 + t / 100)
    public function un_an(): void
    {
        $this->montant = $this->montant * (1 + $this->interet / 100);
    }

    public function get_montant(): float
    {
        return $this->montant;
    }
}

$c1 = new Compte(400, 10);     // 400 dirhams à 10 %
$c2 = new Compte(2500, 5);     // 2 500 dirhams à 5 %

for ($i = 1; $i <= 10; $i++) {
    $c1->un_an();
    $c2->un_an();
}

echo "Valeur du compte c1 = " . number_format($c1->get_montant(), 2, ',', ' ') . " dirhams" . PHP_EOL;
echo "Valeur du compte c2 = " . number_format($c2->get_montant(), 2, ',', ' ') . " dirhams" . PHP_EOL;

// Résultats attendus après 10 ans : c1 ≈ 1 037,50 DH et c2 ≈ 4 072,24 DH