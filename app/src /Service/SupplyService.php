<?php

class SupplyService{

    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    // Récupère tous les approvisionnements avec leur fournisseur.
    public function getAllApprovisionnements(): array{
        $sql = "
            SELECT
                a.id,
                a.date,
                a.montant_total,
                a.statut,
                f.nom AS fournisseur_nom,
                f.telephone AS fournisseur_telephone
            FROM approvisionnement a
            INNER JOIN fournisseur f
                ON f.id = a.fournisseur_id
            ORDER BY a.date DESC, a.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Récupère un approvisionnement par son ID.
    public function getApprovisionnementById(int $id): ?array{
        $sql = "
            SELECT
                a.id,
                a.utilisateur_id,
                a.fournisseur_id,
                a.date,
                a.montant_total,
                a.statut,
                f.nom AS fournisseur_nom,
                f.telephone AS fournisseur_telephone
            FROM approvisionnement a
            INNER JOIN fournisseur f
                ON f.id = a.fournisseur_id
            WHERE a.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $approvisionnement = $stmt->fetch();
        return $approvisionnement ?: null;
    }

    // Récupère les lignes d'un approvisionnement.
    public function getLignes(int $approvisionnementId): array{
        $sql = "
            SELECT
                la.id,
                la.approvisionnement_id,
                la.produit_id,
                la.quantite,
                la.prix_unitaire,
                la.sous_total,
                p.nom AS produit_nom
            FROM ligne_approvisionnement la
            INNER JOIN produit p
                ON p.id = la.produit_id
            WHERE la.approvisionnement_id = :approvisionnement_id
            ORDER BY la.id ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'approvisionnement_id' => $approvisionnementId
        ]);
        return $stmt->fetchAll();
    }

    // Récupère un approvisionnement avec ses lignes.
    public function getDetails(int $approvisionnementId): ?array{
        $approvisionnement = $this->getApprovisionnementById(
            $approvisionnementId
        );
        if (!$approvisionnement) {
            return null;
        }
        $approvisionnement['lignes'] = $this->getLignes(
            $approvisionnementId
        );
        return $approvisionnement;
    }

    // Réceptionne un approvisionnement et met à jour le stock.
    public function receptionner(
        int $approvisionnementId,
        array $quantitesRecues
    ): bool {
        try {
            $this->pdo->beginTransaction();

            $approvisionnement = $this->getApprovisionnementById(
                $approvisionnementId
            );
            if (!$approvisionnement) {
                throw new Exception(
                    "Approvisionnement introuvable."
                );
            }
            if ($approvisionnement['statut'] === 'receptionnee') {
                throw new Exception(
                    "Cet approvisionnement est déjà réceptionné."
                );
            }
            $lignes = $this->getLignes(
                $approvisionnementId
            );
            foreach ($lignes as $ligne) {
                $produitId = (int) $ligne['produit_id'];
                $quantiteRecue = (int) (
                    $quantitesRecues[$produitId] ?? 0
                );
                if ($quantiteRecue < 0) {
                    throw new Exception(
                        "La quantité reçue ne peut pas être négative."
                    );
                }
                if ($quantiteRecue > 0) {
                    $this->augmenterStock(
                        $produitId,
                        $quantiteRecue
                    );
                }
            }
            $statut = $this->determinerStatutReception(
                $lignes,
                $quantitesRecues
            );
            $this->modifierStatut(
                $approvisionnementId,
                $statut
            );
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    // Ajoute une quantité au stock d'un produit.
    private function augmenterStock(
        int $produitId,
        int $quantite
    ): void {
        $sql = "
            UPDATE stock
            SET
                quantite_disponible =
                    quantite_disponible + :quantite,
                date_mise_a_jour = CURRENT_TIMESTAMP
            WHERE produit_id = :produit_id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'quantite' => $quantite,
            'produit_id' => $produitId
        ]);

        // Si le produit n'existe pas encore dans stock,
        // on crée sa ligne.
        if ($stmt->rowCount() === 0) {
            $sql = "
                INSERT INTO stock (
                    produit_id,
                    quantite_disponible,
                    date_mise_a_jour
                )
                VALUES (
                    :produit_id,
                    :quantite,
                    CURRENT_TIMESTAMP
                )
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'produit_id' => $produitId,
                'quantite' => $quantite
            ]);
        }
    }

    // Détermine si la réception est complète ou partielle.
    private function determinerStatutReception(
        array $lignes,
        array $quantitesRecues
    ): string {
        $totalCommande = 0;
        $totalRecu = 0;
        foreach ($lignes as $ligne) {
            $produitId = (int) $ligne['produit_id'];
            $totalCommande += (int) $ligne['quantite'];
            $totalRecu += (int) (
                $quantitesRecues[$produitId] ?? 0
            );
        }

        if ($totalRecu === 0) {
            return 'en_attente';
        }
        if ($totalRecu < $totalCommande) {
            return 'receptionnee_partielle';
        }
        return 'receptionnee';
    }

    // Modifie le statut d'un approvisionnement.
    private function modifierStatut(
        int $approvisionnementId,
        string $statut
    ): bool {
        $sql = "
            UPDATE approvisionnement
            SET statut = :statut
            WHERE id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'statut' => $statut,
            'id' => $approvisionnementId
        ]);
    }

    // Calcule le coût total des approvisionnements.
    public function getTotalApprovisionnements(): float{
        $sql = "
            SELECT COALESCE(SUM(montant_total), 0)
            FROM approvisionnement
            WHERE statut != 'annulee'
        ";
        return (float) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    // Compte les approvisionnements réceptionnés.
    public function getNombreReceptions(): int{
        $sql = "
            SELECT COUNT(*)
            FROM approvisionnement
            WHERE statut = 'receptionnee'
        ";
        return (int) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }

    // Compte les fournisseurs actifs.
    public function getNombreFournisseurs(): int{
        $sql = "
            SELECT COUNT(DISTINCT fournisseur_id)
            FROM approvisionnement
            WHERE statut != 'annulee'
        ";
        return (int) $this->pdo
            ->query($sql)
            ->fetchColumn();
    }
}