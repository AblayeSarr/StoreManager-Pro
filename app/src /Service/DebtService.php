<?php

class DebtService
{
    private DetteRepository $detteRepository;

    public function __construct()
    {
        $this->detteRepository = new DetteRepository();
    }

    public function getAllDettes(): array
    {
        return $this->detteRepository->getAllDettes();
    }

    public function getDetteById(int $id): ?array
    {
        return $this->detteRepository->getId($id);
    }

    public function getDettesByClientId(int $clientId): array
    {
        return $this->detteRepository->getClientById($clientId);
    }

    public function rembourser(
        int $detteId,
        float $montant
    ): bool {
        // 1. Récupérer la dette
        $dette = $this->detteRepository->getId($detteId);

        if (!$dette) {
            throw new Exception("Dette introuvable.");
        }

        // 2. Vérifier que le montant est positif
        if ($montant <= 0) {
            throw new Exception(
                "Le montant du remboursement doit être supérieur à 0."
            );
        }

        // 3. Vérifier que le remboursement ne dépasse pas la dette
        if ($montant > $dette['montant_restant']) {
            throw new Exception(
                "Le remboursement ne peut pas dépasser le montant restant."
            );
        }

        // 4. Calculer le nouveau montant restant
        $nouveauMontantRestant =
            $dette['montant_restant'] - $montant;

        // 5. Déterminer le nouveau statut
        if ($nouveauMontantRestant == 0) {
            $statut = 'soldee';
        } else {
            $statut = 'partiellement_remboursee';
        }

        // 6. Enregistrer le remboursement
        $this->detteRepository->addRemboursement(
            $detteId,
            $montant
        );

        // 7. Mettre à jour la dette
        return $this->detteRepository->updateMontantRestant(
            $detteId,
            $nouveauMontantRestant,
            $statut
        );
    }

    public function getRemboursements(
        int $detteId
    ): array {
        return $this->detteRepository->getRemboursements(
            $detteId
        );
    }
}