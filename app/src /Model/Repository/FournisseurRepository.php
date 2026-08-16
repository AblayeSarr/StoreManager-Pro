<?php

require_once dirname(__DIR__) . '/Core/Database.php';
require_once dirname(__DIR__) . '/Entity/Fournisseur.php';

class FournisseurRepository
{
    private PDO $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    public function getAllFournisseur(): array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM fournisseur ORDER BY id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllFournisseurById(int $id): ?array{
        $stmt = $this->pdo->prepare(
            "SELECT * FROM fournisseur WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id
        ]);
        $fournisseur = $stmt->fetch();
        return $fournisseur ?: null;
    }

    public function create(Fournisseur $fournisseur): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO fournisseur
            (nom, telephone, email, adresse)
            VALUES
            (:nom, :telephone, :email, :adresse)"
        );

        return $stmt->execute([
            'nom' => $fournisseur->getNom(),
            'telephone' => $fournisseur->getTelephone(),
            'email' => $fournisseur->getEmail(),
            'adresse' => $fournisseur->getAdresse()
        ]);
    }

    public function update(Fournisseur $fournisseur): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE fournisseur
             SET nom = :nom,
                 telephone = :telephone,
                 email = :email,
                 adresse = :adresse
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $fournisseur->getId(),
            'nom' => $fournisseur->getNom(),
            'telephone' => $fournisseur->getTelephone(),
            'email' => $fournisseur->getEmail(),
            'adresse' => $fournisseur->getAdresse()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM fournisseur
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}