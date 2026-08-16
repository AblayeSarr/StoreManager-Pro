## Phase 2 — Interface POS

### Objectif
Transformer l’interface HTML du POS en une vue PHP dynamique tout en conservant le design existant.

### Travail réalisé
- Création de `POSController.php`.
- Création de `views/pos/index.php`.
- Intégration de l’interface POS HTML/CSS existante dans la vue PHP.
- Remplacement des données statiques par des données provenant de la base de données.
- Affichage dynamique des produits avec `$products`.
- Affichage dynamique des clients avec `$clients`.
- Affichage dynamique des fournisseurs avec `$suppliers`.
- Génération dynamique de la liste des fournisseurs dans les formulaires.
- Utilisation des identifiants réels des produits et fournisseurs au lieu de valeurs codées en dur.
- Conversion des formulaires de démonstration en formulaires `POST`.
- Préparation du traitement des actions POS via le champ caché `action`.
- Conservation du design et de la structure HTML/CSS fournis initialement.

### Architecture

```text
Utilisateur
    ↓
Interface POS
    ↓
views/pos/index.php
    ↓
POSController.php
    ↓
Database.php
    ↓
PostgreSQL / SQLite