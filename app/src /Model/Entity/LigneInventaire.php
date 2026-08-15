<?php

class LigneInventaire {

    private ?int $id;
    private int $inventaireId;
    private int $produitId;
    private int $quantiteTheorique;
    private int $quantiteReelle;
    private int $ecart;

    public function __construct(
        int $inventaireId,
        int $produitId,
        int $quantiteTheorique,
        int $quantiteReelle,
        int $ecart,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->inventaireId = $inventaireId;
        $this->produitId = $produitId;
        $this->quantiteTheorique = $quantiteTheorique;
        $this->quantiteReelle = $quantiteReelle;
        $this->ecart = $ecart;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getInventaireId(): int
    {
        return $this->inventaireId;
    }

    public function setInventaireId(int $inventaireId): void
    {
        $this->inventaireId = $inventaireId;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function setProduitId(int $produitId): void
    {
        $this->produitId = $produitId;
    }

    public function getQuantiteTheorique(): int
    {
        return $this->quantiteTheorique;
    }

    public function setQuantiteTheorique(int $quantiteTheorique): void
    {
        $this->quantiteTheorique = $quantiteTheorique;
    }

    public function getQuantiteReelle(): int
    {
        return $this->quantiteReelle;
    }

    public function setQuantiteReelle(int $quantiteReelle): void
    {
        $this->quantiteReelle = $quantiteReelle;
    }

    public function getEcart(): int
    {
        return $this->ecart;
    }

    public function setEcart(int $ecart): void
    {
        $this->ecart = $ecart;
    }
}