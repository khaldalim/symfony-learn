<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Form\CommentType;
use App\Form\TopicMessageType;
use App\Form\TopicType;

use App\Service\RandomQuote;
use App\Service\SlugService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/topic')]
class TopicController extends AbstractController
{
    #[Route('/', name: 'topic_index')]
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $topics = $em->getRepository(Topic::class)->findAll();
        return $this->render('topic/index.html.twig', ['topics' => $topics]);
    }



    #[Route('/{slug}', name: 'topic_show', methods: ['GET', 'POST'])]
    public function show(Topic $topic, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(TopicMessageType::class, null, [
            'action' => $this->generateUrl('topic_message_new',
                ['id' => $topic->getId()])
        ]);

        $em = $this->getDoctrine()->getManager();
        $topicMessages = $em->getRepository(Message::class)->findBy(['topic' => $topic], ['score' => 'DESC']);

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
            'messages' => $topicMessages
        ]);
    }

}
