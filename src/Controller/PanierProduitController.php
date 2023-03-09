<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Form\CommandeType;



class PanierProduitController extends AbstractController
{
  #[Route('/panier_produit', name: 'app_panier_produit')]
  public function index(SessionInterface $session, ProduitRepository $produitRepository)
  {
      $panier = $session->get("panier", []);
      $dataPanier = [];
      $total = 0;
  
      foreach ($panier as $id => $quantite) {
          $produit = $produitRepository->find($id);
          $dataPanier[] = [
              "produit" => $produit,
              "quantite" => $quantite
          ];
          $total += $produit->getPrixproduit() * $quantite;
      }
  
      return $this->render('panier_produit/index.html.twig', compact("dataPanier", "total"));
  }
    
    #[Route('/panier_produit/{id}', name: 'panier_produit_add')]
    public function add(Produit $produit,SessionInterface $session)
    {
      $panier=$session->get("panier",[]);
      $id=$produit->getId();
      if(isset($panier[$id])){
        $panier[$id]++;
      }
      else{
        $panier[$id]=1;
      }
      $session->set("panier",$panier);
      $this->addFlash("success", "Le produit a été ajouté au panier");
      return $this->redirectToRoute('app_panier_produit');
    }
    #[Route('/panier_produit_remove/{id}', name: 'panier_produit_remove')]
    public function remove(Produit $produit,SessionInterface $session)
    {
      $panier=$session->get("panier",[]);
      $id=$produit->getId();
      if(!empty($panier[$id])){
        if($panier[$id]>1){
          $panier[$id]--;
        }else{
          unset($panier[$id]);
        }
      }
      $session->set("panier",$panier);
      return $this->redirectToRoute('app_panier_produit');
}
#[Route('/panier_produit_delete/{id}', name: 'panier_produit_delete')]
public function delete(Produit $produit,SessionInterface $session)
{
  $panier=$session->get("panier",[]);
  $id=$produit->getId();
  if(!empty($panier[$id])){
    unset($panier[$id]);
  }
  $session->set("panier",$panier);
  return $this->redirectToRoute('app_panier_produit');
  
}
#[Route('/panier_produit_delete', name: 'panier_produit_delete_all')]
public function deleteAll(SessionInterface $session)
{
  $session->set("panier",[]);
  $this->addFlash("success", "Le panier a été vidé");
  return $this->redirectToRoute('app_panier_produit');

}

}



