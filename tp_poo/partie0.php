<?php
/*
 * Auteur : Salaheddine Zin
 * Objectif : TP 4, Partie 0 - la première classe (classe, objet, $this)
 * Date : 12/11/2026
 */

// Permet de voir les retours à la ligne aussi bien en console qu'au navigateur
header('Content-Type: text/plain; charset=UTF-8');

class Voiture
{
    public $marque;
    public $couleur;

    public function setMarque($m)
    {
        $this->marque = $m;      // $this = l'objet qui appelle la méthode
    }

    // Exercice 0.2 : nouvelle propriété et son setter
    public function setCouleur($c)
    {
        $this->couleur = $c;
    }

    // Exercice 0.2 : afficher() affiche aussi la couleur
    public function afficher()
    {
        echo "Marque : " . $this->marque . " | Couleur : " . $this->couleur . PHP_EOL;
    }
}

$v1 = new Voiture();
$v1->setMarque("Toyota");
$v1->setCouleur("Blanche");
$v1->afficher();

// Exercice 0.1 : second objet de marque Renault
$v2 = new Voiture();
$v2->setMarque("Renault");
$v2->setCouleur("Rouge");
$v2->afficher();

// Exercice 0.3 : on modifie $v1 puis on réaffiche les deux objets
echo PHP_EOL . "Après modification de \$v1 :" . PHP_EOL;
$v1->setMarque("Peugeot");
$v1->afficher();
$v2->afficher();

/*
 * Réponse 0.3 : non, $v1 et $v2 ne partagent pas la même valeur de $marque.
 * Chaque objet créé avec new possède sa propre copie des propriétés ;
 * modifier $v1 ne change pas $v2.
 */