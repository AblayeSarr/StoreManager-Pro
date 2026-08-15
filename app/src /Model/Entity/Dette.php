<?php

class Dette {
    
    private ?int $id;
    private int $venteId;
    private int $clientId;
    private float $montant;
    private float $montantRestant;
    private ?DateTimeInterface $date;
    private string $statut;

    public function __construct(
        int $venteId,
        int $clientId,
        float $montant,
        ?float $montantRestant = null,
        string $statut = 'en_cours',
        ?DateTimeInterface $date = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->venteId = $venteId;
        $this->clientId = $clientId;
        $this->montant = $montant;
        $this->montantRestant = $montantRestant ?? $montant;
        $this->date = $date;
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

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }

    public function getMontantRestant(): float
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(float $montantRestant): void
    {
        $this->montantRestant = $montantRestant;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function appliquerRemboursement(float $montantRembourse): void
    {
        $this->montantRestant = max(
            0,
            $this->montantRestant - $montantRembourse
        );

        $this->statut = $this->montantRestant <= 0
            ? 'soldee'
            : 'partiellement_remboursee';
    }
}