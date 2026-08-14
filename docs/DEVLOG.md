Phase 1 — Conception UML

Objectif

Concevoir le fonctionnement et la structure de StoreManager Pro avant l'implémentation.

Diagramme de cas d'utilisation

Les 4 acteurs identifiés sont :

**Administrateur** : gestion globale du système.
**Chargé de Vente** : ventes, paiements et dettes.
**Chargé de Stock** : approvisionnements, produits, fournisseurs et stocks.
***Inventaire** : consultation des produits, stocks et statistiques.

Les relations `<<include>>` représentent les fonctionnalités obligatoires, tandis que `<<extend>>` représentent les fonctionnalités optionnelles.

Diagramme de classes

Les principales classes sont :

`Utilisateur`, `Produit`, `Stock`, `Client`, `Fournisseur`, `Vente`, `LigneVente`, `Paiement`, `Dette`, `Remboursement`, `Approvisionnement`, `LigneApprovisionnement`, `Livraison`, `Inventaire` et `LigneInventaire`.

Les principales relations concernent :

* les ventes et leurs produits;
* les paiements et les dettes;
* les approvisionnements et les fournisseurs;
* les produits et les stocks;
* les inventaires et les produits.

Conclusion

La conception UML fournit la base nécessaire pour passer à la conception de la base de données et à l'implémentation de StoreManager Pro.
