# Phase 1 — Schéma BDD

## Objectif

Mettre en place le schéma de la base de données à partir du modèle UML de StoreManager Pro.

## Schéma de la base de données

Deux versions du schéma ont été préparées :

- `schema.sql` : version **PostgreSQL**
- `schema_sqlite.sql` : version **SQLite**

Les deux schémas reprennent les principales entités du diagramme de classes :

- Utilisateur
- Produit
- Stock
- Client
- Fournisseur
- Vente
- LigneVente
- Paiement
- Dette
- Remboursement
- Approvisionnement
- LigneApprovisionnement
- Livraison
- Inventaire
- LigneInventaire

Les clés primaires, clés étrangères, relations et contraintes nécessaires ont été définies afin de respecter le modèle UML.

## Conclusion
Le schéma BDD est basé sur le diagramme de classes et permet de préparer l'implémentation de la base de données de StoreManager Pro.