<?php

class LigneApprovisionnement{
    
    private ?int $id;
    private int $approvisionnementId;
    private int $produitId;
    private int $quantite;
    private float $prixUnitaire;
    private float $sousTotal;

    public function __construct(
        int $approvisionnementId,
        int $produitId,
        int $quantite,
        float $prixUnitaire,
        float $sousTotal,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->approvisionnementId = $approvisionnementId;
        $this->produitId = $produitId;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
        $this->sousTotal = $sousTotal;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getApprovisionnementId(): int
    {
        return $this->approvisionnementId;
    }

    public function setApprovisionnementId(int $approvisionnementId): void
    {
        $this->approvisionnementId = $approvisionnementId;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function setProduitId(int $produitId): void
    {
        $this->produitId = $produitId;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): void
    {
        $this->prixUnitaire = $prixUnitaire;
    }

    public function getSousTotal(): float
    {
        return $this->sousTotal;
    }

    public function setSousTotal(float $sousTotal): void
    {
        $this->sousTotal = $sousTotal;
    }
}