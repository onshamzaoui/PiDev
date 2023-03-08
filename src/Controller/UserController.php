<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
     
    #[Route('/disable/{id}', name: 'disable')]

    public function disable(Request $request,$id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $user->setdisable(true);
        $rep->flush();
        return $this->redirectToRoute('app_user_index');
}
#[Route('/enable/{id}', name: 'enable')]

    public function enable($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $user->setdisable(false);
        $rep->flush();
        return $this->redirectToRoute('app_user_index');
}
#[Route('/upgrade/{id}', name: 'upgrade')]

    public function makeadmin($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $roles[] = 'ROLE_CLIENT';
        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);
        $rep->flush();
        return $this->redirectToRoute('app_user_index');
}
#[Route('/downUpgrade/{id}', name: 'DownUpgrade')]

    public function unmakeadmin($id){

        $rep = $this->getDoctrine()->getManager();
        $user = $rep->getRepository(User::class)->find($id);
        $roles[] = 'ROLE_CLIENT';
        $user->setRoles($roles);
        $rep->flush();
        return $this->redirectToRoute('app_user_index');
}
}
