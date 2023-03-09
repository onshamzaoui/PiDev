<?php

namespace App\Controller;

use App\Service\SmsService;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->uploadImage();
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('event_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something goes wrong during file upload
                }
    
                $event->setImage($newFilename);

            }
            $eventRepository->save($event, true);

           

            // Send an SMS to notify that a new event was created
            // $smsService = new SmsService('AC3c9d8191146d6c4eea22ad16d93e4743', '2ee7de17cfc85269db3713d51ab70be3', '+15674302507');
            // $message = sprintf('A new event "%s" was created.', $event->getNom() );
            // $toNumber = '+21690086551'; // replace with the recipient's phone number
            // $smsService->sendSms($toNumber, $message);
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRepository->save($event, true);

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/like', name: 'app_event_like', methods: ['POST'])]
public function like(Event $event, EventRepository $eventRepository): Response
{
    $event->incrementLikes();
    $eventRepository->save($event, true);

    return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
}

#[Route('/{id}/dislike', name: 'app_event_dislike', methods: ['POST'])]
public function dislike(Event $event, EventRepository $eventRepository): Response
{
    $event->incrementDislikes();
    $eventRepository->save($event, true);

    return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
}

}
