## 2. Autopsie de 3 Méthodes Clés

### 🔍 Méthode 1 : `DetteRepository::addRemboursement()`

**Fichier :** `src/Repository/DetteRepository.php`

Cette méthode permet d'enregistrer un remboursement effectué par un client sur une dette existante.

```php
public function addRemboursement(
    int $detteId,
    float $montant
): int {
    $sql = "
        INSERT INTO remboursements (
            dette_id,
            montant
        )
        VALUES (
            :dette_id,
            :montant
        )
        RETURNING id
    ";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'dette_id' => $detteId,
        'montant' => $montant
    ]);

    return (int) $stmt->fetchColumn();
}

Pourquoi cette méthode est importante ?

Elle permet de conserver un historique de chaque remboursement dans la table
remboursements.

Le remboursement n'est donc pas simplement ajouté au montant restant de la
dette : une trace est conservée en base de données.

Fonctionnement:

La méthode reçoit l'identifiant de la dette.
Elle reçoit le montant versé par le client.
Elle insère ces informations dans remboursements.
PostgreSQL retourne l'identifiant du remboursement créé.
Cet identifiant est retourné par la méthode

Choix technique:
J'utilise une requête préparée avec des paramètres nommés :
:dette_id
:montant

Cela permet d'éviter d'insérer directement les valeurs reçues dans la requête
SQL et rend la requête plus sûre.

Méthode 2 : DebtService::rembourser()

Fichier : src/Service/DebtService.php

Cette méthode contient la logique métier du remboursement d'une dette.

Contrairement au Repository, le Service ne se contente pas d'exécuter une
requête SQL. Il vérifie que l'opération est cohérente avant de modifier les
données.

Fonctionnement:

Lorsqu'un utilisateur veut effectuer un remboursement :

Le Service récupère la dette.
Il vérifie que la dette existe.
Il vérifie que le montant est supérieur à zéro.
Il vérifie que le montant ne dépasse pas le reste dû.
Il enregistre le remboursement.
Il calcule le nouveau montant restant.
Il détermine le nouveau statut de la dette.
Il met à jour la dette.

Exemple de logique :

$nouveauMontantRestant = $dette['montant_restant'] - $montant;
if ($nouveauMontantRestant == 0) {
    $statut = 'soldee';
} elseif ($nouveauMontantRestant < $dette['montant']) {
    $statut = 'partiellement_remboursee';
} else {
    $statut = 'en_cours';
}

Pourquoi utiliser un Service ?

Cette logique ne doit pas être placée directement dans la vue ou dans le
Repository.
Le Repository est responsable de l'accès aux données.
Le Service est responsable des règles métier.
La séparation permet donc d'obtenir une architecture plus propre :

Vue
 ↓
Controller
 ↓
Service
 ↓
Repository
 ↓
Base de données

Cette séparation facilite également les tests et les modifications futures.


Méthode 3 : SupplyService::receiveSupply()

Fichier : src/Service/SupplyService.php

Cette méthode permet de réceptionner un approvisionnement et de mettre à jour
automatiquement le stock.

Elle intervient lorsqu'un Bordereau de Livraison (BL) arrive chez le
commerçant.

Fonctionnement:

Lors de la réception d'un BL :

Le système récupère l'approvisionnement.
Il vérifie que l'approvisionnement existe.
Il récupère les lignes de l'approvisionnement.
Il récupère les quantités réellement reçues.
Il compare les quantités commandées et reçues.
Il augmente le stock des produits concernés.
Il met à jour le statut de l'approvisionnement.
Les changements sont enregistrés en base.

Exemple de logique :

$stockRepository->increaseQuantity(
    $ligne['produit_id'],
    $quantiteRecue
);

Puis le statut peut être déterminé selon la réception :

Quantité reçue = quantité commandée
        ↓
RECEPTIONNEE

Quantité reçue < quantité commandée
        ↓
RECEPTIONNEE_PARTIELLE


Pourquoi cette méthode est importante ?
Elle relie deux fonctionnalités importantes de l'application :

Approvisionnement
       ↓
Réception du BL
       ↓
Mise à jour du stock


Bilan de l'autopsie:

Ces trois méthodes montrent les trois niveaux principaux de l'architecture
de StoreManager Pro :

| Méthode              | Couche     | Responsabilité                 |
| -------------------- | ---------- | ------------------------------ |
| `addRemboursement()` | Repository | Enregistrer les données        |
| `rembourser()`       | Service    | Appliquer les règles métier    |
| `receiveSupply()`    | Service    | Gérer la réception et le stock |
