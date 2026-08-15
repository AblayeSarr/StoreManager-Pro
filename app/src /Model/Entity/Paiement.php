<?php

class Paiement{

    private ?int $id;
    private int $venteId;
    private ?DateTimeInterface $date;
    private float $montant;
    private string $modePaiement;
    private string $statut;

    public function __construct(
        int $venteId,
        float $montant,
        string $modePaiement,
        string $statut = 'en_attente',
        ?DateTimeInterface $date = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->venteId = $venteId;
        $this->date = $date;
        $this->montant = $montant;
        $this->modePaiement = $modePaiement;
        $this->statut = $statut;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getVenteId(): int
    {
        return $this->venteId;
    }

    public function setVenteId(int $venteId): void
    {
        $this->venteId = $venteId;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }

    public function getModePaiement(): string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): void
    {
        $this->modePaiement = $modePaiement;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}