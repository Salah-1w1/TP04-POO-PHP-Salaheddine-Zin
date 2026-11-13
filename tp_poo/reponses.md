# Réponses - TP 4 : Programmation Orientée Objet en PHP

## Partie 0

- **0.3** : non, `$v1` et `$v2` ne partagent pas la même valeur de `$marque`. Chaque objet créé avec `new` possède sa propre copie des propriétés : modifier `$v1` ne change pas `$v2`.

## Partie 1

- **1.1** : `echo $p->cin;` provoque `Cannot access private property Personne::$cin`. `echo $p->dateNaissance;` provoque `Cannot access private property Personne::$dateNaissance`. `echo $p->getAge();` provoque `Call to private method Personne::getAge() from global scope`. En revanche `$p->getName()` et `$p->nom` fonctionnent car ils sont publics.
- **1.2** : un `cin` public pourrait être lu et modifié par n'importe quelle partie du programme (fuite de donnée personnelle, valeur invalide ou falsifiée). Le garder privé permet de contrôler l'accès.
- **1.3** : non, `getAge()` n'est pas inutile. Une méthode privée est un outil interne : elle peut être appelée par les autres méthodes de la même classe (par exemple `__toString()` en partie 4).
- **1.4** : avec `protected`, le comportement depuis l'extérieur ne change pas : l'erreur devient `Cannot access protected property Personne::$cin`. La différence concerne seulement les classes filles, qui pourraient y accéder.

## Partie 2

- **2.1.a** : `new Voiture("Noire")` donne la couleur Noire et la vitesse par défaut 0. `new Voiture(120)` : en mode non strict, 120 est converti en chaîne et devient la couleur `"120"` ; avec `declare(strict_types=1)`, on obtiendrait une `TypeError`.
- **2.1.b** : avantage de la promotion de propriétés : moins de code, car on ne déclare plus les attributs séparément et on n'écrit plus les affectations `$this->x = $x`.
- **2.2.a** : avec deux constructeurs, PHP provoque `Fatal error: Cannot redeclare Personne::__construct()`. PHP ne gère pas la surcharge de méthodes : une classe ne peut avoir qu'un seul constructeur.
- **2.2.b** : un seul constructeur avec des valeurs par défaut (`$nom = ""`, `$prenom = ""`) permet la création « vide » et la création avec informations.
- **2.2.c** : une méthode statique de fabrique (named constructor) est une méthode `static` qui crée et retourne un objet, par exemple `Personne::depuisTableau(array $data)`. Elle permet plusieurs façons de construire un objet avec un nom explicite.
- **2.3.a** : avec `unset`, l'ordre est : « Voiture Blanche créée. », « Voiture Blanche détruite. », « Fin du script. ».
- **2.3.b** : sans `unset`, l'ordre est : « créée », « Fin du script. », puis « détruite » (le destructeur est appelé à la fin du script).
- **2.3.c** : à la fin du script, les objets restants sont détruits après le message « Fin du script. ». L'ordre exact est celui affiché par `partie2.php` ; la documentation PHP ne garantit pas d'ordre précis.
- **2.3.d** : le destructeur affiche « Détruire Personne » (via `__CLASS__`) à la destruction de l'objet.

## Partie 3

- **3.1** : résultats vérifiés dans `partie3.php` : `Hachimi`, `bool(true)`, `bool(false)`, puis le message `Propriété telephone inexistante`.
- **3.2** : sans `__set`, `$p->nom = "X"` avec `nom` privé provoque `Cannot access private property`. Avec `__set`, l'affectation est interceptée et c'est le code de `__set` qui décide de l'accepter ou non.
- **3.3** : oui, un `__set` qui accepte tout sans contrôle casse l'encapsulation. Pour valider une valeur, il faut ajouter les contrôles dans `__set` (ou utiliser des setters dédiés) et refuser les valeurs invalides.
- **3.4** : validation ajoutée dans `__set` : si le nom est vide ou fait moins de 2 caractères, on lance `InvalidArgumentException`.

## Partie 4

- **4.1** : `getAge()` est privée mais elle est appelée depuis `__toString()`, qui est une méthode de la même classe. Un membre privé est accessible depuis l'intérieur de la classe.
- **4.2** : si `__toString()` retourne un entier, avec `declare(strict_types=1)` PHP lance une `TypeError` ; sans mode strict, l'entier serait converti en chaîne.
- **4.3** : `__toString()` permet d'utiliser l'objet directement comme une chaîne (`echo $p`, concaténation) sans appel explicite à une méthode. Deux situations pratiques : l'affichage dans une page ou un message, et l'écriture dans un journal (logs) ou un message d'erreur.

## Partie 5

- **5.1** : la chaîne sérialisée contient seulement `nom` et `age` ; le mot de passe n'y figure pas.
- **5.2** : sans `__serialize()`, toutes les propriétés, y compris les privées, sont sérialisées : le mot de passe apparaît en clair dans la chaîne. Risque : fuite d'informations sensibles si la chaîne est stockée ou transmise.
- **5.3** : après `unserialize`, `$motDePasse` vaut `""`. Dans une vraie application, il faudrait redemander le mot de passe ou recharger l'utilisateur depuis la base de données, et ne jamais stocker un mot de passe en clair (le hacher).
- **5.4** : `unserialize()` sur des données de l'utilisateur est dangereux : c'est la faille *PHP Object Injection*. Un attaquant fabrique une chaîne qui instancie des classes existantes dont les méthodes magiques (`__destruct`, `__wakeup`...) s'exécutent, ce qui peut mener à l'exécution de code. Alternative : `json_encode()` / `json_decode()`, qui échangent seulement des données.

## Partie 6

- **6.1.b** : non, `$c->miauler()` provoque `Call to undefined method Chien::miauler()` : `miauler()` appartient à `Chat`, pas à `Chien`.
- **6.2.a** : sans `parent::__construct($nom)`, la propriété `$nom` n'est jamais initialisée : l'appel à `presentation()` provoque `Typed property PersonneSimple::$nom must not be accessed before initialization`.
- **6.2.b** : `$nom` est `protected` pour que les classes filles (`Etudiant`) puissent y accéder. Avec `private`, elle serait invisible pour `Etudiant`.
- **6.3.a** : (1) redéfinir une méthode `final` : `Fatal error: Cannot override final method Base::afficher()`. (2) étendre une classe `final` : `Fatal error: Class SousOutil cannot extend final class Outil`. (3) modifier une propriété `readonly` : `Cannot modify readonly property Produit::$reference`.
- **6.3.b** : `readonly` est pertinent pour un identifiant, une référence produit ou une date de création, qui ne doivent pas changer après la création. `final` est pertinent pour une classe utilitaire ou une méthode critique (calcul de sécurité) qu'on ne veut pas voir redéfinie.
- **6.4.a** : si `getAge()` est `private` dans `Personne`, l'appel `$this->getAge()` depuis `Etudiant` provoque une erreur (`Call to private method`). Il faut la déclarer `protected` (ou `public`) pour que ça fonctionne.
- **6.4.b** : `$this->dateNaissance` est inaccessible depuis `Etudiant` car l'attribut est `private` dans `Personne` : l'héritage ne donne pas accès aux membres privés, même si un `Etudiant` est une `Personne`.
- **6.4.c** : réutiliser `parent::__toString()` évite de dupliquer le code et simplifie la maintenance : une modification dans la classe mère se répercute automatiquement dans la classe fille.

## Partie 7

- **7.1** : erreur de syntaxe dans `un_an()` : il manque l'opérateur `+` dans `$this->montant 1 $this->interet / 100`.
- **7.2** : la formule `montant * (montant + 1 + interet / 100)` est fausse. La bonne formule avec un intérêt de t % est : `montant * (1 + t / 100)`.
- **7.3** : l'ordre `($interet, $montantInitial)` n'est pas cohérent avec l'énoncé (« un montant initial et un intérêt ») : on risque d'inverser les deux valeurs à l'appel sans qu'aucune erreur ne soit signalée. Ordre corrigé : `($montantInitial, $interet)`.
- **7.4** : code corrigé dans `partie7.php` (types `float`, types de retour, `number_format($montant, 2, ',', ' ')`). Résultats après 10 ans : `c1` ≈ 1 037,50 DH et `c2` ≈ 4 072,24 DH.

## Questions de synthèse

1. Une **classe** est le modèle (le plan) qui décrit les propriétés et les méthodes ; un **objet** est une instance de cette classe, créée avec `new`, qui possède ses propres valeurs.
2. Trois avantages de la POO : la réutilisation du code (héritage), l'encapsulation (protection des données), et une meilleure organisation et maintenance du code (modélisation proche du monde réel).
3. On déclare les attributs en `private` ou `protected` pour protéger l'état interne de l'objet : l'accès passe par des méthodes qui peuvent contrôler et valider les valeurs, et on peut changer l'implémentation interne sans casser le reste du programme.
4. `private` : accessible uniquement dans la classe elle-même. `protected` : accessible dans la classe et dans ses classes filles.
5. Oui, une méthode magique doit être déclarée `public` (sinon PHP émet un avertissement). Exceptions tolérées : `__construct()`, `__destruct()` et `__clone()` peuvent être `private` ou `protected` (par exemple pour un singleton).