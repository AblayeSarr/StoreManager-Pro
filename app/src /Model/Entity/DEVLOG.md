
## PHASE 2 : SAMEDI — Cœur POO & Ventes POS
### Step 2.1 (09h00 - 11h00) : Entités POO Pure


Cette étape consiste à transformer les principales tables de la base de données en classes PHP afin de commencer la conception orientée objet du projet.
L'objectif est de séparer les données du projet de la partie accès à la base de données. Les classes d'entités représentent donc les objets métier de l'application.

### Entités créées

Les classes suivantes ont été créées :

- `Utilisateur.php` → représente un utilisateur du système.
- `Produit.php` → représente un produit vendu ou stocké.
- `Stock.php` → représente le stock associé à un produit.
- `Client.php` → représente un client.
- `Fournisseur.php` → représente un fournisseur.
- `Vente.php` → représente une vente effectuée par un utilisateur pour un client.
- `LigneVente.php` → représente un produit présent dans une vente.
- `Paiement.php` → représente un paiement associé à une vente.
- `Dette.php` → représente une dette liée à une vente et à un client.
- `Remboursement.php` → représente un remboursement d'une dette.
- `Approvisionnement.php` → représente un approvisionnement effectué auprès d'un fournisseur.
- `LigneApprovisionnement.php` → représente un produit présent dans un approvisionnement.
- `Inventaire.php` → représente une opération d'inventaire.
- `LigneInventaire.php` → représente le détail d'un produit contrôlé lors d'un inventaire.

### Structure des entités

Chaque classe suit une structure POO similaire :

- les propriétés sont déclarées en `private` ;
- les propriétés sont typées avec les types PHP (`int`, `string`, `float`, etc.) ;
- un constructeur permet d'initialiser l'objet ;
- des getters permettent de récupérer les valeurs ;
- des setters permettent de modifier les valeurs ;
- les identifiants des relations sont représentés par des propriétés comme `$clientId`, `$produitId`, `$venteId`, etc.

### Exemple

La table `produits` de la base de données contient notamment :

```text
id
nom
description
categorie
prix
seuil_alerte