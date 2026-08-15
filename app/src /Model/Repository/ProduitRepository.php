<?php

class ProduitRepository{
    
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits ORDER BY id DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM produits WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $produit = $stmt->fetch();

        return $produit ?: null;
    }

    public function create(Produit $produit): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO produits
            (nom, description, categorie, prix, seuil_alerte)
            VALUES
            (:nom, :description, :categorie, :prix, :seuil_alerte)"
        );

        return $stmt->execute([
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'categorie' => $produit->getCategorie(),
            'prix' => $produit->getPrix(),
            'seuil_alerte' => $produit->getSeuilAlerte()
        ]);
    }

    public function update(Produit $produit): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE produits
            SET nom = :nom,
                description = :description,
                categorie = :categorie,
                prix = :prix,
                seuil_alerte = :seuil_alerte
            WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $produit->getId(),
            'nom' => $produit->getNom(),
            'description' => $produit->getDescription(),
            'categorie' => $produit->getCategorie(),
            'prix' => $produit->getPrix(),
            'seuil_alerte' => $produit->getSeuilAlerte()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM produits WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}