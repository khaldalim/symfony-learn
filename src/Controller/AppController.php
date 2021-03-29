<?php

namespace App\Controller;


use App\Entity\Message;
use App\Entity\Topic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AppController extends AbstractController
{

    #[Route('/', name: 'home', priority: 1)]
    public function messages(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $topics = $em->getRepository(Topic::class)->findBy([],['id' => 'DESC'],3);
        return $this->render('app/index.html.twig',
            ['topics' => $topics]);
    }



}
