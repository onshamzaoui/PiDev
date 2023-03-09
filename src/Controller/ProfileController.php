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

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $points = $user->getPoints();

        $form = $this->createForm(ProfileFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            // $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'userId'=>$userId,

        ]);
    }

    public function getBonusPoints() {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT points FROM bonus WHERE user_id = '$user_id'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $points = $row["points"];
        } else {
            $points = 0;
        }
        
        return $this->render('profile.html.twig', ['points' => $points]);
    }

    
    
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}

