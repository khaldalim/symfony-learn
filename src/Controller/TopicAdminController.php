<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Message;
use App\Entity\Tag;
use App\Entity\Topic;
use App\Event\TopicEvent;
use App\Form\CommentType;
use App\Form\TopicMessageType;
use App\Form\TopicType;

use App\Service\RandomQuote;
use App\Service\SlugService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/topic')]
/**
 * @IsGranted("ROLE_ADMIN")
 */
class TopicAdminController extends AbstractController
{


    #[Route('/new', name: 'topic_new')]
    public function new(Request $request): Response
    {
        $topic = new Topic();

        /*
          $tag = new Tag();
          $tag->setText("PHP");

          $topic->addTag($tag);
          //pas besoin de le faire dans les2 sens il est déja fait dans addTag()
          //$tag->addTopic($topic);
        */

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //création du slug
                /*
                 * à été ajouté dans le listener
                 *    $slug = $slugService->slugify($topic->getName());
                $topic->setSlug($slug);
                 */

                $topic->setUser($this->getUser());


                $em = $this->getDoctrine()->getManager();
                $em->persist($topic);
                $em->flush();

              // $dispatcher->dispatch(new TopicEvent($topic), TopicEvent::NAME);

                $this->addFlash('success', "Topic crée !");
                return $this->redirectToRoute('topic_index');
            } else {
                $this->addFlash('danger', "Formulaire non valide !");
            }
        }


        return $this->render('topic/new.html.twig', [
            'titre' => 'Ajouter',
            'form' => $form->createView()
        ]);
    }


    #[Route('/new/message/{id}', name: 'topic_message_new', methods: ['GET', 'POST'])]
    public function messageNew(Topic $topic, Request $request): Response
    {
        $message = new Message();
        $message->setUser($this->getUser());
        $message->setTopic($topic);
        $form = $this->createForm(TopicMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {


                $em = $this->getDoctrine()->getManager();
                $em->persist($message);

                $em->flush();
                $this->addFlash('success', "Message crée !");

            } else {
                $this->addFlash('danger', "Formulaire non valide !");
            }
        }

        return $this->redirectToRoute('topic_show', ['slug' => $topic->getSlug()]);
    }


    #[Route('/comment/new/{id}', name: 'topic_comment_new')]
    public function commentNew(Request $request, Message $message): Response
    {
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setMessage($message);
        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('topic_comment_new', ['id' => $message->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', "Commentaire bien créé");
            } else {
                $this->addFlash('danger', "Formulaire pas valide");
            }
            return $this->redirectToRoute('topic_show', ['slug' => $message->getTopic()->getSlug()]);
        }

        return $this->render('topic/commentForm.html.twig', [
            'formComment' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'topic_update')]
    public function update(Topic $topic): Response
    {

        /*
        $user = $this->getUser();
        if ($user->getId() != $topic->getUser()->getId()) {
            throw  new AccessDeniedException();
        }
        */

        //on passe par le voter pour verifier si l'utilisateur peut editer
        $this->denyAccessUnlessGranted("TOPIC_EDIT", $topic);


        /** @var Topic $topic */
        $topic->setName($topic->getName() . "- modifié");
        $em = $this->getDoctrine()->getManager();

        try {
            $em->flush();
            $this->addFlash('success', "Topic modifié !");
        } catch (\Exception $e) {
            $this->addFlash('danger', "Topic non modifié !");
        }


        return new Response("Topic modifié");
    }

    #[Route('/delete/{id}', name: 'topic_delete')]
    public function delete(Topic $topic): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($topic);
        try {
            $em->flush();
            $this->addFlash('success', "Topic supprimé !");
        } catch (\Exception $e) {
            $this->addFlash('danger', "Topic non supprimé !");
        }

        return $this->redirectToRoute('topic_index');
    }

    public function randomQuote(RandomQuote $randomQuote): Response
    {
        $quote = $randomQuote->getDalyQuote();
        return new  Response($quote);
    }

}
