<?php

namespace App\Controller;

use App\Entity\Recyclage;
use App\Form\RecyclageType;
use App\Repository\RecyclageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recyclage')]
class RecyclageController extends AbstractController
{
    #[Route('/', name: 'app_recyclage_index', methods: ['GET'])]
    public function index(RecyclageRepository $recyclageRepository): Response
    {
        return $this->render('recyclage/index.html.twig', [
            'recyclages' => $recyclageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recyclage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecyclageRepository $recyclageRepository): Response
    {
        $recyclage = new Recyclage();
        $form = $this->createForm(RecyclageType::class, $recyclage);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $don = $recyclage->getDon();
    
            if ($don->isEstTraite()) {
                // Si le don est déjà traité, on affiche un message d'erreur
                $this->addFlash('error', 'Le don est déjà traité');
                return $this->redirectToRoute('app_recyclage_new');
            }
    
            // Sinon, on met à jour l'objet Don et on enregistre les données
            $don->setEstTraite(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recyclage);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le recyclage a été enregistré');
    
            return $this->redirectToRoute('app_recyclage_index');
        }
    
        return $this->render('recyclage/new.html.twig', [
            'recyclage' => $recyclage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recyclage_show', methods: ['GET'])]
    public function show(Recyclage $recyclage): Response
    {
        return $this->render('recyclage/show.html.twig', [
            'recyclage' => $recyclage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recyclage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recyclage $recyclage, RecyclageRepository $recyclageRepository): Response
    {
        $form = $this->createForm(RecyclageType::class, $recyclage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recyclageRepository->save($recyclage, true);

            return $this->redirectToRoute('app_recyclage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recyclage/edit.html.twig', [
            'recyclage' => $recyclage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recyclage_delete', methods: ['POST'])]
    public function delete(Request $request, Recyclage $recyclage, RecyclageRepository $recyclageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recyclage->getId(), $request->request->get('_token'))) {
            $recyclageRepository->remove($recyclage, true);
        }

        return $this->redirectToRoute('app_recyclage_index', [], Response::HTTP_SEE_OTHER);
    }
}
