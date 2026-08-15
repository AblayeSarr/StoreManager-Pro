<?php

namespace App\Model\Entity;

class Produit
{
    private ?int $id;
    private string $nom;
    private ?string $description;
    private string $categorie;
    private float $prix;
    private int $seuilAlerte;

    public function __construct(
        string $nom,
        string $categorie,
        float $prix,
        int $seuilAlerte = 5,
        ?string $description = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->categorie = $categorie;
        $this->prix = $prix;
        $this->seuilAlerte = $seuilAlerte;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getSeuilAlerte(): int
    {
        return $this->seuilAlerte;
    }

    public function setSeuilAlerte(int $seuilAlerte): void
    {
        $this->seuilAlerte = $seuilAlerte;
    }
}