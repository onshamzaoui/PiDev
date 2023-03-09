<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;


use Doctrine\ORM\EntityManagerInterface;


class CalendarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/calendar", name="calendar")
     */
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(Event::class);
        $events = $repository->findAll();

        return $this->render('calendar/index.html.twig', [
            'events' => $events,
        ]);
    }
}

