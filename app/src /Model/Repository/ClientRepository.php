<?php
require_once dirname(__DIR__) . '/Core/Database.php';

class ClientRepository {

    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    public function getAllClient(): array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM client ORDER BY id DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllClientById(int $id): ?array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM client WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id
        ]);
        $client = $stmt->fetch();
        return $client ?: null;
    }

    public function create(Client $client): bool{
        $stmt = $this->pdo->prepare(
            "INSERT INTO client
            (nom, prenom, telephone, email, adresse)
            VALUES
            (:nom, :prenom, :telephone, :email, :adresse)"
        );

        return $stmt->execute([
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
            'adresse' => $client->getAdresse()
        ]);
    }

    public function update(Client $client): bool{
        $stmt = $this->pdo->prepare(
            "UPDATE client
             SET nom = :nom,
                 prenom = :prenom,
                 telephone = :telephone,
                 email = :email,
                 adresse = :adresse
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $client->getId(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail(),
            'adresse' => $client->getAdresse()
        ]);
    }

    public function delete(int $id): bool{
        $stmt = $this->pdo->prepare(
            "DELETE FROM client WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id
        ]);
    }
}