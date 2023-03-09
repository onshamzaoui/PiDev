<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class FactureController extends AbstractController
{
    /**
     * @Route("/factures", name="liste_factures")
     */
    public function listeFactures(ManagerRegistry $doctrine): Response
    { 
        $em=$doctrine->getManager();
        $factures = $em->getRepository(Facture::class)->findAll();
       


        return $this->render('facture/liste_factures.html.twig', [
            'factures' => $factures,
        ]);
    }

    /**
     * @Route("/factures/{id}", name="details_facture")
     */
    public function detailsFacture($id,ManagerRegistry $doctrine): Response
    {    $em=$doctrine->getManager();
        $facture =$em->getRepository(Facture::class)->find($id);

        return $this->render('facture/details_facture.html.twig', [
            'facture' => $facture,
        ]);
    }

    /**
     * @Route("/factures/{id}/supprimer", name="supprimer_facture")
     */
    public function supprimerFacture($id, ManagerRegistry $doctrine): Response
{
    $em = $doctrine->getManager();
    $facture = $em->getRepository(Facture::class)->find($id);

    if (!$facture) {
        throw $this->createNotFoundException('Facture non trouvée');
    }

    // Delete associated 'produit' records first
    $produits = $facture->getCommande()->getProduits();
    foreach ($produits as $produit) {
        $em->remove($produit);
    }

    // Delete 'facture' record
    $em->remove($facture);
    $em->flush();

    return $this->redirectToRoute('liste_factures');
}


    /**
     * @Route("/factures/{id}/modifier", name="modifier_facture")
     */
    public function modifierFacture(Request $request, $id, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $facture = $em->getRepository(Facture::class)->find($id);

        if (!$facture) {
            throw $this->createNotFoundException('Facture non trouvée');
        }

        $form = $this->createForm(FactureType::class, $facture);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('details_facture', ['id' => $facture->getId()]);
        }

        return $this->render('facture/modifier_facture.html.twig', [
            'form' => $form->createView(),
            'facture' => $facture,
        ]);
    }
}
