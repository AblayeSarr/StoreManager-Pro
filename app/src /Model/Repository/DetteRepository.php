<?php

require_once dirname(__DIR__, 2) . '/Core/Database.php';

class DetteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->pdo;
    }

    /**
     * Récupère toutes les dettes avec les informations du client.
     */
    public function getAllDettes(): array
    {
        $sql = "
            SELECT
                d.id,
                d.vente_id,
                d.client_id,
                d.montant,
                d.montant_restant,
                d.date,
                d.statut,
                c.nom,
                c.prenom,
                c.telephone
            FROM dette d
            INNER JOIN client c ON c.id = d.client_id
            ORDER BY d.date DESC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Récupère une dette par son ID.
     */
    public function getDetteId(int $id): ?array
    {
        $sql = "
            SELECT
                d.*,
                c.nom,
                c.prenom,
                c.telephone
            FROM dette d
            INNER JOIN client c ON c.id = d.client_id
            WHERE d.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $dette = $stmt->fetch();

        return $dette ?: null;
    }

    /**
     * Récupère les dettes d'un client.
     */
    public function getClientDetteById(int $clientId): array
    {
        $sql = "
            SELECT *
            FROM dette
            WHERE client_id = :client_id
            ORDER BY date DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'client_id' => $clientId
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Crée une nouvelle dette.
     */
    public function create(
        int $venteId,
        int $clientId,
        float $montant
    ): int {
        $sql = "
            INSERT INTO dette (
                vente_id,
                client_id,
                montant,
                montant_restant,
                statut
            )
            VALUES (
                :vente_id,
                :client_id,
                :montant,
                :montant_restant,
                'en_cours'
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'vente_id' => $venteId,
            'client_id' => $clientId,
            'montant' => $montant,
            'montant_restant' => $montant
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Enregistre un remboursement.
     */
    public function addRemboursement(
        int $detteId,
        float $montant
    ): int {
        $sql = "
            INSERT INTO remboursement (
                dette_id,
                montant
            )
            VALUES (
                :dette_id,
                :montant
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'dette_id' => $detteId,
            'montant' => $montant
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour le montant restant et le statut.
     */
    public function updateMontantRestant(
        int $detteId,
        float $montantRestant,
        string $statut
    ): bool {
        $sql = "
            UPDATE dette
            SET
                montant_restant = :montant_restant,
                statut = :statut
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'montant_restant' => $montantRestant,
            'statut' => $statut,
            'id' => $detteId
        ]);
    }

    /**
     * Récupère tous les remboursements d'une dette.
     */
    public function getAllRemboursements(int $detteId): array
    {
        $sql = "
            SELECT *
            FROM remboursement
            WHERE dette_id = :dette_id
            ORDER BY date DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'dette_id' => $detteId
        ]);

        return $stmt->fetchAll();
    }
}