<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'message_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }


    #[Route('/add-point/{id}', name: 'message_add_point', methods: ['GET'])]
    public function addPoint(Request $request, Message $message, TopicRepository $topicRepository): Response
    {
        $score = $message->getScore() + 1;
        $message->setScore($score);

        $topic = $topicRepository->findOneBy(['id' => $message->getTopic()->getId()]);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('topic_show', ['slug' => $topic->getSlug()]);
    }


    #[Route('/remove-point/{id}', name: 'message_remove_point', methods: ['GET'])]
    public function removePoint(Request $request, Message $message, TopicRepository $topicRepository): Response
    {
        $score = $message->getScore() - 1;
        $message->setScore($score);

        $topic = $topicRepository->findOneBy(['id' => $message->getTopic()->getId()]);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('topic_show', ['slug' => $topic->getSlug()]);
    }
}
