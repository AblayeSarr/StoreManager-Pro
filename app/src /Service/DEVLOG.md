### Phase 2 — VenteService
#### Step 2.3 (14h00 - 17h00) : Service Métier Vente POS & Transaction SQL


- **Ce qui a été fait** :
  Mise en place du service métier `VenteService.php` afin de centraliser la logique nécessaire à la réalisation d'une vente.
  Le rôle du `VenteService` est différent de celui des entités et des repositories. Les entités comme `Vente` et `LigneVente` représentent les données et les objets métier, tandis que le Repository s'occupe principalement de communiquer avec la base de données. Le Service permet ici de coordonner plusieurs opérations nécessaires pour réaliser une vente complète.

  #### 1. Gestion du panier

  Une première fonctionnalité permet d'ajouter des produits dans un panier.
  Le panier est représenté par un tableau PHP contenant les informations nécessaires pour chaque produit :

  - l'identifiant du produit ;
  - la quantité ;
  - le prix unitaire ;
  - le sous-total.

  Lorsqu'un produit est ajouté plusieurs fois au panier, sa quantité est augmentée plutôt que de créer inutilement une nouvelle ligne pour le même produit.
  Le sous-total d'une ligne est calculé à partir de :
  `quantité × prix unitaire`
  Une méthode permet également de calculer le montant total du panier en additionnant les sous-totaux de toutes les lignes.

  #### 2. Vérification du stock

  Avant d'enregistrer définitivement une ligne de vente, le stock disponible du produit est récupéré depuis la table `stocks`.
  La quantité demandée est ensuite comparée à la quantité disponible.
  Si le stock disponible est insuffisant, la vente ne peut pas continuer.
  Cette vérification permet d'éviter qu'une vente entraîne une quantité négative dans le stock.

  #### 3. Création de la vente

  Après le calcul du montant total, une ligne est créée dans la table `ventes`.
  La vente contient notamment :

  - l'utilisateur qui réalise la vente ;
  - le client concerné ;
  - le montant total ;
  - le statut de la vente.

  L'identifiant généré par la base de données est ensuite récupéré afin de pouvoir associer les différentes lignes à cette vente.

  #### 4. Création des lignes de vente

  Pour chaque produit présent dans le panier, une entrée est ajoutée dans la table `ligne_ventes`.
  Chaque ligne contient :

  - `vente_id` ;
  - `produit_id` ;
  - `quantite` ;
  - `prix_unitaire` ;
  - `sous_total`.

  Cela permet de conserver le détail des produits vendus pour chaque vente.

  #### 5. Décrémentation du stock

  Une fois qu'une ligne de vente est créée, la quantité correspondante est retirée du stock.
  Par exemple, si un produit possède 20 unités disponibles et que le client en achète 3, le stock passe à 17.
  La mise à jour est effectuée directement en SQL avec une requête préparée.

  #### 6. Utilisation d'une transaction PDO

  La partie importante de ce service est l'utilisation d'une transaction SQL.
  La vente ne consiste pas en une seule opération. Plusieurs opérations doivent être réalisées :

  1. créer la vente ;
  2. créer les lignes de vente ;
  3. vérifier le stock ;
  4. décrémenter le stock.

  Ces opérations doivent rester cohérentes entre elles.
  Pour cela, la transaction commence avec :

  ```php
  $this->pdo->beginTransaction();