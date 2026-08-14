## Phase 1 — Database Fallback

### Step 1.3 — Singleton Database & Fallback Automatique

**Objectif :**
Mettre en place une classe `Database` permettant de gérer la connexion à la base de données PostgreSQL et d'utiliser automatiquement SQLite comme solution de secours si PostgreSQL n'est pas disponible.

**Travail réalisé :**

* Création de la classe `Database` dans `src/Core/Database.php`.
* Mise en place de la connexion PDO à PostgreSQL.
* Configuration de PostgreSQL comme base de données principale.
* Ajout d'un `try/catch` afin de détecter une erreur de connexion PostgreSQL.
* Mise en place d'un fallback automatique vers la base SQLite `erp.db`.
* Configuration de PDO avec `PDO::FETCH_ASSOC` comme mode de récupération par défaut.
* Centralisation de la connexion à la base de données afin que les autres parties de l'application utilisent le même objet PDO.

**Fonctionnement :**

```text
Application
    │
    ▼
Database
    │
    ▼
Connexion PostgreSQL
    │
    ├── Succès ──────► PostgreSQL
    │
    └── Échec
           │
           ▼
       SQLite
       erp.db
```

**Résultat :**

L'application peut fonctionner avec PostgreSQL lorsque le serveur est disponible. En cas d'échec de connexion, elle bascule automatiquement vers SQLite, ce qui permet de continuer le développement et les tests sans dépendre obligatoirement du serveur PostgreSQL.

**Fichiers concernés :**

* `src/Core/Database.php`
* `erp.db`

**Commit :**

```text
feat(database): add PostgreSQL connection with SQLite fallback
```
