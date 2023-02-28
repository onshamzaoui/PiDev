<?php

namespace App\Controller;

use App\Entity\TypeDechet;
use App\Form\TypeDechetType;
use App\Repository\TypeDechetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/dechet')]
class TypeDechetController extends AbstractController
{
    #[Route('/', name: 'app_type_dechet_index', methods: ['GET'])]
    public function index(TypeDechetRepository $typeDechetRepository): Response
    {
        return $this->render('type_dechet/index.html.twig', [
            'type_dechets' => $typeDechetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_dechet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeDechetRepository $typeDechetRepository): Response
    {
        $typeDechet = new TypeDechet();
        $form = $this->createForm(TypeDechetType::class, $typeDechet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeDechetRepository->save($typeDechet, true);

            return $this->redirectToRoute('app_type_dechet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_dechet/new.html.twig', [
            'type_dechet' => $typeDechet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_dechet_show', methods: ['GET'])]
    public function show(TypeDechet $typeDechet): Response
    {
        return $this->render('type_dechet/show.html.twig', [
            'type_dechet' => $typeDechet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_dechet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeDechet $typeDechet, TypeDechetRepository $typeDechetRepository): Response
    {
        $form = $this->createForm(TypeDechetType::class, $typeDechet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeDechetRepository->save($typeDechet, true);

            return $this->redirectToRoute('app_type_dechet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_dechet/edit.html.twig', [
            'type_dechet' => $typeDechet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_dechet_delete', methods: ['POST'])]
    public function delete(Request $request, TypeDechet $typeDechet, TypeDechetRepository $typeDechetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeDechet->getId(), $request->request->get('_token'))) {
            $typeDechetRepository->remove($typeDechet, true);
        }

        return $this->redirectToRoute('app_type_dechet_index', [], Response::HTTP_SEE_OTHER);
    }
}
