<?php

class POSController{

    private VenteService $venteService;
    public function __construct(){
        $this->venteService = new VenteService();
    }

     //Affiche la page de caisse.
    
    public function index(): void{
        // Récupération du panier depuis la session
        $panier = $_SESSION['panier'] ?? [];
        // Récupération des produits depuis la base de données
        $produits = $this->venteService->getProduits();
        // Chargement de la vue POS
        require dirname(__DIR__, 2) . '/views/pos/index.php';
    }

     //Ajoute un produit au panier.
    
    public function ajouterAuPanier(): void{

        $produitId = (int) $_POST['produit_id'];
        $quantite = (int) $_POST['quantite'];
        $prixUnitaire = (float) $_POST['prix_unitaire'];

        // Initialise le panier s'il n'existe pas
        $_SESSION['panier'] ??= [];

        // Ajout du produit au panier
        $this->venteService->ajouterAuPanier(
            $_SESSION['panier'],
            $produitId,
            $quantite,
            $prixUnitaire
        );
        // Retour à la caisse
        header('Location: /pos');
        exit;
    }

    //Valide et enregistre la vente.

    public function validerVente(): void{

        $utilisateurId = (int) $_POST['utilisateur_id'];
        $clientId = (int) $_POST['client_id'];
        // Récupération du panier
        $panier = $_SESSION['panier'] ?? [];
        // Enregistrement de la vente
        $venteId = $this->venteService->enregistrerVente(
            $utilisateurId,
            $clientId,
            $panier
        );
        // Suppression du panier après validation
        unset($_SESSION['panier']);
        // Retour à la caisse
        header('Location: /pos');
        exit;
    }
}