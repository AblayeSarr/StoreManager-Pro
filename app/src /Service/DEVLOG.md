### Phase 2 — VenteService
#### Step 2.3 Service Métier Vente POS & Transaction SQL


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




  ## Phase 3 — Approvisionnements & Réception BL

### Step 3.2  Approvisionnements & Réception BL

**Travail réalisé :**

- Création du `SupplyService` pour centraliser la logique métier des approvisionnements.
- Récupération des approvisionnements avec les informations des fournisseurs.
- Récupération des lignes d'un approvisionnement avec les produits concernés.
- Affichage des détails d'un BL :
  - fournisseur ;
  - produits ;
  - quantités commandées ;
  - prix unitaires ;
  - montant total.
- Mise en place du volet de réception des Bons de Livraison (BL).
- Possibilité de saisir les quantités réellement reçues pour chaque produit.
- Vérification des quantités reçues afin d'éviter de dépasser les quantités commandées.
- Mise à jour automatique du stock lors de la réception.
- Gestion des réceptions partielles et complètes.
- Mise à jour du statut de l'approvisionnement :
  - `en_attente`
  - `receptionnee_partielle`
  - `receptionnee`
- Utilisation d'une transaction PDO pour garantir la cohérence entre la réception du BL et la mise à jour du stock.
- Ajout automatique d'une ligne dans `stocks` si le produit ne possède pas encore de stock.

**Résultat :**

Le module d'approvisionnement permet maintenant de consulter les Bons de Livraison, d'afficher leurs lignes et de réceptionner les marchandises afin de mettre à jour automatiquement les quantités disponibles en stock.

**Fichiers concernés :**
- `src/Services/SupplyService.php`
- `views/pos/index.php`


## Phase 3 — Authentification & Contrôle des Rôles

### Step 3.3 (14h30 - 16h30) : AuthManager & Contrôle des Rôles

**Livrables :**
- `src/Service/AuthManager.php`
- `AuthController.php`
- Filtrage des accès selon le rôle utilisateur

**Travail réalisé :**

- Création de `AuthManager` pour centraliser la gestion de l'authentification.
- Mise en place de la connexion utilisateur avec vérification de l'email et du mot de passe.
- Utilisation de `password_verify()` pour vérifier les mots de passe sécurisés.
- Mise en place de la session utilisateur après une connexion réussie.
- Ajout de la récupération des informations de l'utilisateur connecté.
- Ajout de la déconnexion avec destruction de la session.
- Création de méthodes de contrôle d'accès :
  - `isAuthenticated()`
  - `hasRole()`
  - `hasAnyRole()`
  - `requireAuth()`
  - `requireRole()`
  - `requireAnyRole()`
- Création de `AuthController` pour gérer les actions de connexion et de déconnexion.
- Mise en place du contrôle des accès selon les rôles définis dans la base de données :
  - `administrateur`
  - `charge_vente`
  - `charge_stock`
  - `inventaire`
- L'administrateur dispose d'un accès complet.
- Le rôle `charge_vente` peut accéder aux fonctionnalités liées aux ventes et au POS.
- Le rôle `charge_stock` peut accéder à la gestion du stock et aux approvisionnements.
- Le rôle `inventaire` peut accéder aux fonctionnalités d'inventaire.
- Ajout d'une réponse HTTP `403` lorsqu'un utilisateur authentifié tente d'accéder à une fonctionnalité qui ne correspond pas à son rôle.

**Résultat :**

L'application dispose maintenant d'un système d'authentification basé sur les sessions et d'un contrôle d'accès par rôle. Les utilisateurs ne peuvent accéder qu'aux fonctionnalités correspondant à leurs permissions.

**Fichiers concernés :**
- `src/Service/AuthManager.php`
- `AuthController.php`
- Contrôleurs nécessitant une protection par rôle