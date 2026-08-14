# Phase 1 — Conception UML

## Objectif

Concevoir le modèle fonctionnel et structurel de **StoreManager Pro** avant de commencer l'implémentation.
Cette phase permet d'identifier les différents acteurs du système, leurs fonctionnalités ainsi que les principales classes métier et leurs relations.

---

## 1. Diagramme de cas d'utilisation

Le diagramme de cas d'utilisation permet d'identifier les fonctionnalités principales du système et les responsabilités de chaque acteur.

### Acteurs identifiés

Le système comporte quatre acteurs :

* **Administrateur**
* **Chargé de Vente**
* **Chargé de Stock**
* **Inventaire**

### Fonctionnalités principales

#### Administrateur

L'administrateur dispose d'un accès global au système :

* Se connecter
* Consulter le tableau de bord
* Gérer les ventes
* Gérer les dettes
* Gérer les approvisionnements
* Gérer le catalogue
* Consulter les stocks
* Consulter les statistiques

#### Chargé de Vente

Le chargé de vente peut :

* Se connecter
* Consulter le tableau de bord
* Gérer les ventes
* Créer une vente
* Ajouter des produits au panier
* Enregistrer les paiements
* Gérer les dettes
* Consulter les dettes
* Enregistrer les remboursements
* Consulter les statistiques

#### Chargé de Stock

Le chargé de stock peut :

* Se connecter
* Consulter le tableau de bord
* Gérer les approvisionnements
* Créer un approvisionnement
* Réceptionner une livraison
* Gérer le catalogue
* Gérer les produits
* Gérer les fournisseurs
* Consulter les stocks

#### Inventaire

L'acteur Inventaire peut :

* Se connecter
* Consulter le tableau de bord
* Consulter les produits
* Consulter les stocks
* Consulter les statistiques

### Relations entre les cas d'utilisation

Les relations `<<include>>` sont utilisées lorsqu'une fonctionnalité nécessite obligatoirement une autre fonctionnalité.

Exemples :

* Gérer les ventes `<<include>>` Créer une vente
* Créer une vente `<<include>>` Ajouter un produit au panier
* Créer une vente `<<include>>` Enregistrer le paiement
* Gérer les dettes `<<include>>` Consulter les dettes
* Gérer les approvisionnements `<<include>>` Créer un approvisionnement
* Gérer le catalogue `<<include>>` Gérer les produits
* Gérer le catalogue `<<include>>` Gérer les clients
* Gérer le catalogue `<<include>>` Gérer les fournisseurs

Les relations `<<extend>>` représentent une fonctionnalité optionnelle ou conditionnelle.

Exemples :

* Enregistrer un remboursement `<<extend>>` Consulter les dettes
* Réceptionner une livraison `<<extend>>` Créer un approvisionnement

---

## 2. Diagramme de classes

Le diagramme de classes représente la structure métier de **StoreManager Pro**.
Les principales classes identifiées sont :

* `Utilisateur`
* `Produit`
* `Stock`
* `Client`
* `Fournisseur`
* `Vente`
* `LigneVente`
* `Paiement`
* `Dette`
* `Remboursement`
* `Approvisionnement`
* `LigneApprovisionnement`
* `Livraison`
* `Inventaire`
* `LigneInventaire`

### Relations principales

#### Gestion des utilisateurs

Un utilisateur peut effectuer plusieurs ventes, créer plusieurs approvisionnements et réaliser plusieurs inventaires.

```text
Utilisateur 1 ─── 0..* Vente
Utilisateur 1 ─── 0..* Approvisionnement
Utilisateur 1 ─── 0..* Inventaire
```

#### Gestion des ventes

Une vente contient une ou plusieurs lignes de vente. Chaque ligne de vente concerne un produit.

```text
Vente 1 ─── 1..* LigneVente
LigneVente * ─── 1 Produit
```

Une vente peut également recevoir un ou plusieurs paiements et peut générer une dette.

```text
Vente 1 ─── 1..* Paiement
Vente 1 ─── 0..1 Dette
```

#### Gestion des dettes

Une dette appartient à un client et peut contenir plusieurs remboursements.
```text
Dette 1 ─── 0..* Remboursement
Dette * ─── 1 Client
```

#### Gestion du stock

Chaque produit possède un stock permettant de suivre sa quantité disponible.
```text
Produit 1 ─── 1 Stock
```

#### Gestion des approvisionnements

Un fournisseur peut fournir plusieurs approvisionnements.
Chaque approvisionnement contient une ou plusieurs lignes d'approvisionnement, chaque ligne étant associée à un produit.

```text
Fournisseur 1 ─── 0..* Approvisionnement
Approvisionnement 1 ─── 1..* LigneApprovisionnement
LigneApprovisionnement * ─── 1 Produit
```

Un approvisionnement peut donner lieu à une livraison.
```text
Approvisionnement 1 ─── 0..1 Livraison
```

Gestion de l'inventaire

Un inventaire contient une ou plusieurs lignes d'inventaire. Chaque ligne permet de contrôler un produit et de comparer la quantité théorique avec la quantité réellement constatée.

```
Inventaire 1 ─── 1..* LigneInventaire
LigneInventaire * ─── 1 Produit
```
3. Cohérence entre les diagrammes

Le diagramme de classes a été construit à partir des fonctionnalités identifiées dans le diagramme de cas d'utilisation.
Les principaux domaines fonctionnels sont :

**Ventes** → `Vente`, `LigneVente`, `Paiement`
**Dettes** → `Dette`, `Remboursement`
**Clients** → `Client`
**Produits et stocks** → `Produit`, `Stock`
**Approvisionnements** → `Approvisionnement`, `LigneApprovisionnement`, `Livraison`
**Fournisseurs** → `Fournisseur`
**Inventaire** → `Inventaire`, `LigneInventaire`
**Utilisateurs** → `Utilisateur`

4. Conclusion

La phase de conception UML permet de disposer d'une vision claire de l'architecture fonctionnelle et métier de StoreManager Pro.
Le diagramme de cas d'utilisation définit les fonctionnalités accessibles aux différents acteurs, tandis que le diagramme de classes définit les principales données métier et leurs relations.
Ces modèles serviront de base pour la prochaine étape :la conception et la mise en place de la base de données PostgreSQL et SQLite.
