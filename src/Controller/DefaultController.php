<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceRepository;

#[Route('/default')]
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'produits' => $produitRepository->findAll(), 'categories' => $categorieRepository->findAll(),
        ]);
    }
    // /**
    //  * @Route("indexcontact", name="index_contact")
    //  */
    // public function indexContact(): Response
    // {
    //     return $this->render('default/indexcontact.html.twig');
    // }
    /**
     *  @Route("aboutus", name="aboutus")
     */
    public function indexAbout(): Response
    {
        return $this->render('default/aboutus.html.twig');
    }
    /**
     *  @Route("contactus", name="contactus")
     */
    public function indexContact(): Response
    {
        return $this->render('default/contactus.html.twig');
    }
    // /**
    //  * @Route("produitindex", name="produitindex")
    //  */
    // public function indexService(): Response
    // {
    //     return $this->render('default/indexservice.html.twig');
    // }
    /**
     * @Route("/produits/{nomcategorie?}", name="product", defaults={"nomcategorie"=1})
     */
    public function indexProduit(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, $nomcategorie): Response
    {
        // $produits = $produitRepository->findBy(['nomproduit' => $nomcategorie_id]);

        // $categories = $categorieRepository->findAll();

        // return $this->render('default/product.html.twig', [
        //     'produits' => $produits,
        //     'categories' => $categories,
        // ]);
        // Get the customer object you want to find orders for
        $categorie = $categorieRepository->find($nomcategorie);

        // Find all orders belonging to that customer
        $produit = $produitRepository->findBy(['nomcategorie' => $categorie]);
        //var $produit;
        return $this->render('default/product.html.twig', [
            'produits' => $produit,
            'categories' => $categorie,
        ]);
    }
    /**
     * @Route("product1", name="product1")
     */
    public function indexProduit1(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, $nomcategorie): Response
    {
        $categorie = $categorieRepository->findOneBy(['nomcategorie' => $nomcategorie]);

        if (!$categorie) {
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        }

        $produits = $produitRepository->findBy(['categorie' => $categorie]);

        return $this->render('default/product1.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("product2", name="product2")
     */
    public function indexProduit2(ProduitRepository $produitRepository): Response
    {
        return $this->render('default/product2.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    /**
      * @Route("service", name="service")
      */
      public function indexService(ServiceRepository $serviceRepository): Response
      {
          return $this->render('default/service.html.twig', [
              'services' => $serviceRepository->findAll(),
          ]);
      }
}
