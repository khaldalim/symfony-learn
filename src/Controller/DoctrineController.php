<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/doctrine')]
class DoctrineController extends AbstractController
{
    #[Route('/', name: 'doctrine_index')]
    public function index(): Response
    {
        $message = new Message();
        $message2 = new Message();
        $messages = [$message, $message2];

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findAll();

        return $this->render('doctrine/index.html.twig', ['messages' => $messages]);
    }

    #[Route('/new', name: 'doctrine_new')]
    public function new(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $message = new Message("numéro aléatoire" . rand(0, 150));

        $topic = new Topic();
        $topic->setName("Topic du message : " . $message->getDescription());

        $message->setTopic($topic);
        //on dit à doctrine de gerer cette entité
        $em->persist($message);

        //grace au mapping cascade pas besoin de persist les 2
        //$em->persist($topic);

        $em->flush();

        return $this->json(['id' => $message->getId()]);
    }


    #[Route('/update/{id}', name: 'doctrine_update')]
    public function update($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Message $message */
        $message = $em->getRepository(Message::class)->find($id);

        //si pas de message lié a cette id -> go to 404
        if (!$message) {
            throw new NotFoundHttpException();
        }

        $message->setDescription($message->getDescription() . "-modifié");
        $em->flush();

        /*
         *  return $this->json(
            ['id' => $message->getId(),
                'message' => $message->getDescription()
            ]);
         */

        //  return $this->redirect($this->generateUrl('doctrine_index'));
        return $this->redirectToRoute('doctrine_index');
        // return new RedirectResponse(doctrine_index);
    }


    #[Route('/delete/{id}', name: 'doctrine_delete')]
    public function delete(Message $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('doctrine_index');
    }
}
