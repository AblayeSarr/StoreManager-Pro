<?php

require_once __DIR__ . '/Core/Database.php';

class DetteRepository {
    
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->pdo;
    }

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
            FROM dettes d
            INNER JOIN clients c ON c.id = d.client_id
            ORDER BY d.date DESC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    public function getId(int $id): ?array
    {
        $sql = "
            SELECT
                d.*,
                c.nom,
                c.prenom,
                c.telephone
            FROM dettes d
            INNER JOIN clients c ON c.id = d.client_id
            WHERE d.id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);

        $dette = $stmt->fetch();

        return $dette ?: null;
    }

    public function getClientById(int $clientId): array
    {
        $sql = "
            SELECT *
            FROM dettes
            WHERE client_id = :client_id
            ORDER BY date DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'client_id' => $clientId
        ]);

        return $stmt->fetchAll();
    }

    public function create(
        int $venteId,
        int $clientId,
        float $montant
    ): int {
        $sql = "
            INSERT INTO dettes (
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
            RETURNING id
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'vente_id' => $venteId,
            'client_id' => $clientId,
            'montant' => $montant,
            'montant_restant' => $montant
        ]);

        return (int) $stmt->fetchColumn();
    }

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

    public function updateMontantRestant(
        int $detteId,
        float $montantRestant,
        string $statut
    ): bool {
        $sql = "
            UPDATE dettes
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

    public function getRemboursements(int $detteId): array
    {
        $sql = "
            SELECT *
            FROM remboursements
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