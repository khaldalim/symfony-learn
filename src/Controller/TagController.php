<?php

namespace App\Controller;


use App\Entity\Tag;
use App\Entity\Topic;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag')]
class TagController extends AbstractController
{
    #[Route('/', name: 'tag_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    #[Route('/{slug}', name: 'topic_tag', methods: ['GET'])]
    public function tag(Tag $tag): Response
    {
        $em = $this->getDoctrine()->getManager();
        $topics = $em->getRepository(Topic::class)->findByTag($tag);
        return $this->render('topic/topicsByTag.html.twig', [
            'topics' => $topics,
            'tag' => $tag
        ]);
    }

}



