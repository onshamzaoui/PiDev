<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Form\CommandeType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Entity\Facture;
use Doctrine\ORM\EntityManagerInterface;




class CommandeController extends AbstractController
{
    #[Route('/commande/saisie', name: 'commande_saisie')]
    public function passerCommande(Request $request, ProduitRepository $produitRepository,SessionInterface $session,ManagerRegistry $doctrine): Response
{
    // Récupérer le panier de l'utilisateur depuis la session
    // $panier = $this->get('session')->get('panier');
    $panier = $session->get('panier');

    // Si le panier est vide, rediriger vers la page d'accueil
    if (empty($panier)) {
        return $this->redirectToRoute('default');
    }
   $em=$doctrine->getManager();
    // Créer une nouvelle commande
    $commande = new Commande();

    // Calculer le total de la commande et récupérer les produits ajoutés dans le panier
    $total = 0;
    foreach ($panier as $id => $quantite) {
        $produit = $produitRepository->find($id);

        // Ajouter le prix du produit multiplié par sa quantité au total de la commande
        $total += $produit->getPrixproduit() * $quantite;

        // Ajouter le produit à la liste des produits associés à la commande
        $commande->addProduit($produit);
    }

    // Enregistrer le total de la commande
    $commande->setTotal($total);

    // Créer un formulaire pour la saisie de l'adresse et la date de la commande
    $form = $this->createForm(CommandeType::class, $commande);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer l'adresse et la date de la commande
        $commande->setAdressecommande($form->get('adressecommande')->getData());
        $commande->setDatecommande($form->get('datecommande')->getData());

        // Enregistrer la commande dans la base de données
    
        $em->persist($commande);
        $em->flush();
        // Créer une nouvelle instance de la classe Facture
$facture = new Facture();

// Récupérer la commande correspondante
$commande = $em->getRepository(Commande::class)->find($commande->getId());

// Associer la facture à la commande
$facture->setCommande($commande);

// Calculer le montant total de la facture
$montanttotal = $commande->getTotal() * 1.2; // Ajouter 20% de TVA
$facture->setMontantTotal($montanttotal);

// Enregistrer la date de facturation
$datefacturation = new \DateTime();
$facture->setDateFacturation($datefacturation);

// Enregistrer la facture dans la base de données
$em->persist($facture);
$em->flush();

        

        // Vider le panier de l'utilisateur
        $panier = $session->set('panier', []);

        // Ajouter un message de confirmation
        $this->addFlash('success', 'La commande a été enregistrée avec succès.');

        // Rediriger vers la page de confirmation de commande
        return $this->redirectToRoute('app_confirmation', ['id' => $commande->getId()]);
    }

    // Afficher le formulaire de saisie de l'adresse et la date de la commande
    return $this->render('commande/saisie.html.twig', [
        'form' => $form->createView(),
        'dataPanier' => $panier, // Ajouter le panier à la vue
        'produits' => $commande->getProduits() // Ajouter la liste des produits associés à la commande à la vue
    ]);
}

    #[Route('/confirmation/{id}', name: 'app_confirmation')]
    public function confirmation(int $id, CommandeRepository $commandeRepository): Response
    {
        // Récupérer la commande correspondante à l'identifiant passé en paramètre
        $commande = $commandeRepository->find($id);
    
        // Si la commande n'existe pas, rediriger vers la page d'accueil
        if (!$commande) {
            return $this->redirectToRoute('default');
        }
    
        // Afficher la page de confirmation de commande
        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande
        ]);
    }    




#[Route('/commandes', name: 'commandes')]
public function afficherCommandes(CommandeRepository $commandeRepository): Response
{
    // Récupérer toutes les commandes enregistrées en base de données
    $commandes = $commandeRepository->findAll();

    // Afficher les commandes dans une vue Twig
    return $this->render('commande/index.html.twig', [
        'commandes' => $commandes,
    ]);
}


#[Route('/commandes/details/{id}', name: 'commande_details')]
public function detailsCommande($id,ManagerRegistry $doctrine)
{    
    $em=$doctrine->getManager();
    // Récupérer la commande depuis la base de données
    $commande = $em->getRepository(Commande::class)->find($id);

    // Si la commande n'existe pas, rediriger vers la liste des commandes
    if (!$commande) {
        return $this->redirectToRoute('commandes');
    }

    // Afficher les détails de la commande
    return $this->render('commande/details.html.twig', [
        'commande' => $commande
    ]);
}

    #[Route('/commandes/delete/{id}', name: 'commande_delete')]
    public function deleteCommande($id, ManagerRegistry $managerRegistry): Response
    {
        // Récupérer la commande depuis la base de données
        $entityManager = $managerRegistry->getManager();
        $commande = $entityManager->getRepository(Commande::class)->find($id);
    
        // Si la commande n'existe pas, rediriger vers la liste des commandes
        if (!$commande) {
            return $this->redirectToRoute('commandes');
        }
    
        // Supprimer les détails de commande associés à la commande
        foreach ($commande->getProduits() as $produit) {
            $entityManager->remove($produit);
        }
    
        // Supprimer les factures associées à la commande
        foreach ($commande->getFactures() as $facture) {
            $entityManager->remove($facture);
        }
    
        // Supprimer la commande elle-même
        $entityManager->remove($commande);
        $entityManager->flush();
    
        // Ajouter un message de confirmation
        $this->addFlash('success', 'La commande a été supprimée avec succès.');
    
        // Rediriger vers la liste des commandes
        return $this->redirectToRoute('commandes');
    }
    
   

}



