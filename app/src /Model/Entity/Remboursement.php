<?php

class Remboursement {

    private ?int $id;
    private int $detteId;
    private float $montant;
    private ?DateTimeInterface $date;

    public function __construct(
        int $detteId,
        float $montant,
        ?DateTimeInterface $date = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->detteId = $detteId;
        $this->montant = $montant;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDetteId(): int
    {
        return $this->detteId;
    }

    public function setDetteId(int $detteId): void
    {
        $this->detteId = $detteId;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}