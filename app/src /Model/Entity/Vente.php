<?php

class Vente {
    private ?int $id;
    private int $utilisateurId;
    private int $clientId;
    private ?DateTimeInterface $date;
    private float $montantTotal;
    private string $statut;

    public function __construct(
        int $utilisateurId,
        int $clientId,
        float $montantTotal = 0,
        string $statut = 'en_cours',
        ?DateTimeInterface $date = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->utilisateurId = $utilisateurId;
        $this->clientId = $clientId;
        $this->date = $date;
        $this->montantTotal = $montantTotal;
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

    public function getUtilisateurId(): int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(int $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getMontantTotal(): float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(float $montantTotal): void
    {
        $this->montantTotal = $montantTotal;
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