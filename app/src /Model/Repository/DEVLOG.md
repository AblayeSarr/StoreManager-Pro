## PHASE 2 : Repositories & Interface POS

### Step 2.1 : Création des Repositories

**Objectif :**  
Mettre en place la couche Repository afin de séparer l'accès aux données de la logique métier de l'application.

**Travail réalisé :**
- Création des repositories pour accéder aux données de la base.
- Utilisation de PDO pour exécuter les requêtes SQL.
- Utilisation de requêtes préparées pour sécuriser les paramètres.
- Récupération des données sous forme de tableaux associatifs.
- Mise en place des méthodes CRUD nécessaires aux fonctionnalités du projet.
- Séparation de l'accès aux données de la logique métier.

**Repositories concernés :**
- `ProduitRepository.php`
- `ClientRepository.php`
- `VenteRepository.php`
- `DetteRepository.php`
- `FournisseurRepository.php`
- `ApprovisionnementRepository.php`

**Principales responsabilités :**
- `ProduitRepository` : récupération et gestion des produits et du stock.
- `ClientRepository` : récupération et gestion des clients.
- `VenteRepository` : création et récupération des ventes et lignes de vente.
- `DetteRepository` : récupération des dettes et gestion des remboursements.
- `FournisseurRepository` : récupération et gestion des fournisseurs.
- `ApprovisionnementRepository` : gestion des approvisionnements et de leurs lignes.

**Architecture utilisée :**

Controller
↓
Service
↓
Repository
↓
Database / PDO
↓
PostgreSQL ou SQLite

**Résultat :**
La couche Repository permet maintenant de centraliser les accès à la base de données et de rendre le code plus organisé, maintenable et réutilisable.