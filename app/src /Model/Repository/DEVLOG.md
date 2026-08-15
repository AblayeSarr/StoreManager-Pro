###  Phase 2 — Repositories
#### Step 2.2 (11h00 - 13h00) : Repositories & SQL Sécurisé

- **Ce qui a été fait** :

  Mise en place de la couche Repository permettant de faire le lien entre les entités POO et la base de données.

  Trois repositories ont été créés :

  - `src/Model/Repository/ProduitRepository.php`
  - `src/Model/Repository/ClientRepository.php`
  - `src/Model/Repository/FournisseurRepository.php`

  Les repositories utilisent la connexion PDO fournie par la classe `Database`.

  Les opérations principales ont été mises en place :

  - récupération de tous les produits, clients et fournisseurs ;
  - recherche d'un élément par son identifiant ;
  - ajout d'un nouvel élément ;
  - modification d'un élément existant ;
  - suppression d'un élément.

  Les requêtes SQL utilisent `PDO::prepare()` et `execute()` avec des paramètres nommés.
