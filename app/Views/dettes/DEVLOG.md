## PHASE 3 : DIMANCHE — Dettes, Approvisionnements, Rôles & Clôture

### Step 3.1 : Gestion des Dettes & Remboursements

**Objectif :**  
Mettre en place la gestion des ventes à crédit, le suivi des dettes clients et l'enregistrement des remboursements.

**Travail réalisé :**
- Création de `DetteRepository.php`.
- Création de `DebtService.php`.
- Création de la vue `views/dettes/index.php`.
- Affichage de la liste des dettes avec :
  - Identifiant de la dette.
  - Date de création.
  - Client concerné.
  - Montant initial.
  - Montant déjà payé.
  - Montant restant dû.
  - Statut de la dette.
- Ajout de la recherche des dettes par client.
- Affichage des remboursements déjà effectués.
- Affichage des produits associés à chaque vente à crédit.
- Ajout du formulaire d'enregistrement d'un remboursement.
- Calcul automatique du montant restant après remboursement.
- Gestion des statuts :
  - `en_cours`
  - `partiellement_remboursee`
  - `soldee`
- Utilisation des relations entre `dettes`, `clients`, `remboursements`, `ventes` et `ligne_ventes`.

**Base de données concernée :**
- `dettes`
- `remboursements`
- `clients`
- `ventes`
- `ligne_ventes`
- `produits`

**Livrables :**
- `DetteRepository.php`
- `DebtService.php`
- `views/dettes/index.php`

**Résultat :**
La gestion des dettes permet désormais de consulter les créances clients, de visualiser leur historique de paiement et d'enregistrer les remboursements jusqu'à leur solde complet.