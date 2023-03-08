<?php
namespace App\Controller;
use App\Form\ProfileFormType;
use App\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\UserPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // $userId = $this->getUser()->getId();
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            // $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }
        $userId = $this->getUser()->getId();

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'id' => $userId,

        ]);
    }
    
    #[Route('/profile/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }
        $userId = $this->getUser()->getId();

        return $this->renderForm('profile/index.html.twig', [
            'user' => $user,
            'form' => $form,
            'id' => $userId,
        ]);
    }
}
