<?php   

class Stock {

    private ?int $id;
    private int $produitId;
    private int $quantiteDisponible;
    private ?DateTimeInterface $dateMiseAJour;

    public function __construct(
        int $produitId,
        int $quantiteDisponible = 0,
        ?DateTimeInterface $dateMiseAJour = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->produitId = $produitId;
        $this->quantiteDisponible = $quantiteDisponible;
        $this->dateMiseAJour = $dateMiseAJour;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function setProduitId(int $produitId): void
    {
        $this->produitId = $produitId;
    }

    public function getQuantiteDisponible(): int
    {
        return $this->quantiteDisponible;
    }

    public function setQuantiteDisponible(int $quantiteDisponible): void
    {
        $this->quantiteDisponible = $quantiteDisponible;
    }

    public function getDateMiseAJour(): ?DateTimeInterface
    {
        return $this->dateMiseAJour;
    }

    public function setDateMiseAJour(?DateTimeInterface $dateMiseAJour): void
    {
        $this->dateMiseAJour = $dateMiseAJour;
    }
}