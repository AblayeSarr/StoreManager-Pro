<?php

class VenteService{

    private PDO $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }
    // Ajoute un produit au panier.
    public function ajouterAuPanier(
        array &$panier,
        int $produitId,
        int $quantite,
        float $prixUnitaire
    ): void {
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
    // Calcule le montant total du panier.
    public function calculerTotal(array $panier): float
    {
        $total = 0;

        foreach ($panier as $ligne) {
            $total += $ligne['sous_total'];
        }

        return $total;
    }

    // Récupère tous les produits pour la caisse.
    public function getProduits(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nom, description, categorie, prix
             FROM produits
             ORDER BY nom ASC"
        );

        return $stmt->fetchAll();
    }

    // Enregistre une vente avec ses lignes
    // et décrémente le stock dans une transaction.
    public function enregistrerVente(
        int $utilisateurId,
        int $clientId,
        array $panier
    ): int {
        $total = $this->calculerTotal($panier);
        $this->pdo->beginTransaction();
        try {

            // Création de la vente
            $stmt = $this->pdo->prepare(
                "INSERT INTO ventes
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
                )"
            );

            $stmt->execute([
                'utilisateur_id' => $utilisateurId,
                'client_id' => $clientId,
                'montant_total' => $total
            ]);

            $venteId = (int) $this->pdo->lastInsertId();

            foreach ($panier as $ligne) {
                // Vérification du stock
                $stmt = $this->pdo->prepare(
                    "SELECT quantite_disponible
                     FROM stocks
                     WHERE produit_id = :produit_id"
                );

                $stmt->execute([
                    'produit_id' => $ligne['produit_id']
                ]);

                $stock = $stmt->fetch();
                if (!$stock) {
                    throw new Exception("Stock introuvable.");
                }
                if ($stock['quantite_disponible'] < $ligne['quantite']) {
                    throw new Exception("Stock insuffisant.");
                }
                // Création de la ligne de vente
                $stmt = $this->pdo->prepare(
                    "INSERT INTO ligne_ventes
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

                // Décrémentation du stock
                $stmt = $this->pdo->prepare(
                    "UPDATE stocks
                     SET quantite_disponible =
                         quantite_disponible - :quantite,
                         date_mise_a_jour = CURRENT_DATE
                     WHERE produit_id = :produit_id"
                );
                $stmt->execute([
                    'quantite' => $ligne['quantite'],
                    'produit_id' => $ligne['produit_id']
                ]);
            }
            $this->pdo->commit();
            return $venteId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}