<?php

class Inventaire {
    
    private ?int $id;
    private int $utilisateurId;
    private ?DateTimeInterface $date;
    private string $statut;

    public function __construct(
        int $utilisateurId,
        string $statut = 'en_cours',
        ?DateTimeInterface $date = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->utilisateurId = $utilisateurId;
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

    public function getUtilisateurId(): int
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(int $utilisateurId): void
    {
        $this->utilisateurId = $utilisateurId;
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
}