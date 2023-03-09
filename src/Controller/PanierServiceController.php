<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ServiceRepository;
use App\Entity\Service;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierServiceController extends AbstractController
{
    #[Route('/panier_service', name: 'app_panier_service')]
    public function index(SessionInterface $session, ServiceRepository $serviceRepository)
    {
        $panier=$session->get("panier",[]);
        $dataPanier=[];
        $total=0;
        foreach($panier as $id =>$quantite){
            $service=$serviceRepository->find($id);
            $dataPanier[]=[
                "service"=>$service,
            ];
            $total+=$service->getPrixservice();
        
        }
        return $this->render('panier_service/index.html.twig', compact("dataPanier","total"));
         
    }
    
    #[Route('/panier_service/{id}', name: 'panier_service_add')]
    public function add(Service $service,SessionInterface $session)
    {
      $panier=$session->get("panier",[]);
      $id=$service->getId();
      if(!empty($panier[$id])){
        $panier[$id]++;
      }
      else{
        $panier[$id]=1;
      }
      $session->set("panier",$panier);
      return $this->redirectToRoute('app_panier_service');
    }
    #[Route('/panier_service_delete/{id}', name: 'panier_service_delete')]
public function delete(Service $service,SessionInterface $session)
{
  $panier=$session->get("panier",[]);
  $id=$service->getId();
  if(!empty($panier[$id])){
    unset($panier[$id]);
  }
  $session->set("panier",$panier);
  return $this->redirectToRoute('app_panier_service');
}
#[Route('/panier_service_delete', name: 'panier_service_delete_all')]
public function deleteAll(SessionInterface $session)
{
  $session->set("panier",[]);
  return $this->redirectToRoute('app_panier_service');
}



}
