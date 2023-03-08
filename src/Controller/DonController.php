<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DonRepository;
use App\Entity\Don;
use App\Entity\Bonus;
use App\Form\DonType;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerInterface;






#[Route('/don')]
class DonController extends AbstractController
{

    #[Route('/', name: 'app_don_index', methods: ['GET'])]
    public function index(DonRepository $donRepository): Response
     {
         return $this->render('don/index.html.twig', [
            'dons' => $donRepository->findAll(),
         ]);
     }
    

    #[Route('/new', name: 'app_don_new', methods: ['GET', 'POST'])]
   public function new(Request $request, DonRepository $donRepository, EntityManagerInterface $entityManager): Response
{
    // Vérifie si l'utilisateur est authentifié
    if (!$this->getUser()) {
        return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion
    }
    $don = new Don();
    $form = $this->createForm(DonType::class, $don);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        //  if ($captcha->check($request->get('captcha'))) {
        //     // Le Captcha est valide, vous pouvez traiter le formulaire ici
        //  } else {
        //     $this->addFlash('error', 'Le code Captcha est invalide');
        // }
        $user = $this->getUser();
        $bonusPoints = 0;
        $quantiteDon = $don->getQuantitedon();
        if ($quantiteDon >= 10) {
            $bonusPoints = intdiv($quantiteDon, 10) * 5;
        }
        $bonus = new Bonus();
        $bonus->setPoints($bonusPoints);
        $bonus->setDateAttribution(new \DateTime());
        $user->addBonus($bonus);

        $entityManager->persist($don);
        $entityManager->flush();
        $this->addFlash('success', 'Le don a été ajouté avec succès!');

        return $this->redirectToRoute('app_don_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('don/new.html.twig', [
        'don' => $don,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, DonRepository $donRepository): Response
    {
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $donRepository->save($don, true);

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, DonRepository $donRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$don->getId(), $request->request->get('_token'))) {
            $donRepository->remove($don, true);
        }

        return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
    }

  
     #[Route('/export/csv', name:'don_export_csv')]
    public function exportToCSV(DonRepository $donRepository, ExportService $exportService): Response
    {
        $data = $donRepository->findAll();

        $csv = $exportService->exportToCSV($data);

        $response = new Response($csv);

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="don_export.csv"');

        return $response;
    }
#[Route('/AllDons', name: 'list')]
    public function getDons(DonRepository $repo, SerializerInterface $serializer)
{
    $dons = $repo->findAll();
   // $donsNormalises = $normalizer->normalize($dons, 'json' , ['groups' => "dons"]);
  //  $json= json_encode($donsNormalises);
  $json = $serializer->serialize($dons, 'json', ['groups' => "dons"]);
    return new Response($json);

}

#[Route('/don/{id}', name: 'don')]
 public function DonId($id, NormalizerInterface $normalizer, DonRepository $repo)
  {
    $don = $repo->find($id);
     $donNormalises - $normalizer->normalize ($don, 'json', ['groups' => "dons"]);
     return new Response (ison_encode($studentNormalises));
  }

  #[Route('addDonJSON/new', name: 'addDonJSON')]
 public function addDonJSON(Request $req, NormalizerInterface $Normalizer)
 {
     $em = $this->getDoctrine()->getManager();
    $don = new Don();
     $don->setQuantitedon($req->get('quantitedon'));
    $don->setDescription($req->get('description'));
    $don->setDatedon($req->get('datedon'));
    $don->setTypeDechet($req->get('TypeDechet'));


    $em->persist($don);
     $em->flush();
     $jsonContent= $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
     return new Response (json_encode ($jsonContent));

 }


  #[Route('updateDonJSON/{id}', name:'updateDonJSON')]
  public function updateDonJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
    $em= $this->getDoctrine()->getManager();
    $don = $em->getRepository(Don::class)->find($id);
    $don->setQuantitedon($req->get('quantitedon'));
    $don->setDescription($req->get('description'));
    $don->setDatedon($req->get('datedon'));
    $don->setTypeDechet($req->get('TypeDechet'));
    $em->flush();

    $jsonContent = $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
    return new Response ("Don updated successfully" . json_encode ($jsonContent));

}
#[Route('deleteDonJSON/{id}', name: 'deleteDonJSON')]
public function deleteDonJSON(Request $req, $id, NormalizerInterface $Normalizer)
{
    $em = $this->getDoctrine()->getManager();
    $don = $em->getRepository(Don::class)->find($id);
    $em->remove($don);
    $em->flush();
    $jsonContent = $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
    return new Response ("Don deleted successfully" . json_encode ($jsonContent));
}



#[Route('/chart/{id}',name:'app_don_chart')]
public function googlechart(DonRepository $donRepository): Response
    {
        $dons=$donRepository->findAll();
        if (!$dons) {
            throw $this->createNotFoundException('Le don n\'existe pas');
        }
        


        $data = array();
        $data[] = array('Type de déchet', 'Quantité de dons' ,'Description' ,'DateDon','Typedechet');

        foreach ($dons as $don) {
            $data[] = array($don->getId(), $don->getQuantitedon(), $don->getDescription(), $don->getDatedon(),$don->getTypedechet());
        }

        // Création du graphique
        $chart = new BarChart();
        $chart->getData()->setArrayToDataTable($data);
        $chart->getOptions()->setTitle('Quantité de dons par type de déchet');
        $chart->getOptions()->setHeight(300);
        $chart->getOptions()->setWidth(500);
        $chart->getOptions()->getTitleTextStyle()->setBold(true);
        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');

        // Affichage du graphique
        return $this->render('don/index1.html.twig', [
            'chart' => $chart,
        ]);
    }
}
