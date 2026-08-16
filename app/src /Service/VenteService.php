<?php

require_once dirname(__DIR__) . '/Core/Database.php';

class VenteService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->pdo;
    }

    public function ajouterAuPanier(
        array &$panier,
        int $produitId,
        int $quantite,
        float $prixUnitaire
    ): void {
        if ($quantite <= 0) {
            throw new InvalidArgumentException(
                "La quantité doit être supérieure à 0."
            );
        }

        if ($prixUnitaire < 0) {
            throw new InvalidArgumentException(
                "Le prix ne peut pas être négatif."
            );
        }

        if (isset($panier[$produitId])) {
            $panier[$produitId]['quantite'] += $quantite;

            $panier[$produitId]['sous_total'] =
                $panier[$produitId]['quantite'] * $prixUnitaire;
        } else {
            $panier[$produitId] = [
                'produit_id' => $produitId,
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'sous_total' => $quantite * $prixUnitaire
            ];
        }
    }

    public function calculerTotal(array $panier): float
    {
        $total = 0;

        foreach ($panier as $ligne) {
            $total += $ligne['sous_total'];
        }

        return $total;
    }

    public function getProduits(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nom, description, categorie, prix
             FROM produit
             ORDER BY nom ASC"
        );

        return $stmt->fetchAll();
    }

    public function enregistrerVente(
        int $utilisateurId,
        int $clientId,
        array $panier
    ): int {
        if (empty($panier)) {
            throw new Exception("Le panier est vide.");
        }

        $total = $this->calculerTotal($panier);

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO vente
                (
                    utilisateur_id,
                    client_id,
                    montant_total,
                    statut
                )
                VALUES
                (
                    :utilisateur_id,
                    :client_id,
                    :montant_total,
                    'en_cours'
                )
                RETURNING id"
            );

            $stmt->execute([
                'utilisateur_id' => $utilisateurId,
                'client_id' => $clientId,
                'montant_total' => $total
            ]);

            $venteId = (int) $stmt->fetchColumn();

            foreach ($panier as $ligne) {

                $stmt = $this->pdo->prepare(
                    "SELECT quantite_disponible
                     FROM stock
                     WHERE produit_id = :produit_id"
                );

                $stmt->execute([
                    'produit_id' => $ligne['produit_id']
                ]);

                $stock = $stmt->fetch();

                if (!$stock) {
                    throw new Exception("Stock introuvable.");
                }

                if (
                    $stock['quantite_disponible']
                    < $ligne['quantite']
                ) {
                    throw new Exception("Stock insuffisant.");
                }

                $stmt = $this->pdo->prepare(
                    "INSERT INTO ligne_vente
                    (
                        vente_id,
                        produit_id,
                        quantite,
                        prix_unitaire,
                        sous_total
                    )
                    VALUES
                    (
                        :vente_id,
                        :produit_id,
                        :quantite,
                        :prix_unitaire,
                        :sous_total
                    )"
                );

                $stmt->execute([
                    'vente_id' => $venteId,
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'sous_total' => $ligne['sous_total']
                ]);

                $stmt = $this->pdo->prepare(
                    "UPDATE stock
                     SET quantite_disponible =
                         quantite_disponible - :quantite,
                         date_mise_a_jour = CURRENT_TIMESTAMP
                     WHERE produit_id = :produit_id"
                );

                $stmt->execute([
                    'quantite' => $ligne['quantite'],
                    'produit_id' => $ligne['produit_id']
                ]);
            }

            $this->pdo->commit();

            return $venteId;

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}