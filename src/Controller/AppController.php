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

    #[Route('/app/{id}', name: 'app')]
    public function index(Request $request, $id = null): Response
    {
        //dump($request);
        //$id = $request->query->get('id');
        //  dump($id);

        $range = range(5, 15);

        $message = new Message();
        $message->setCreatedAt(new \DateTime());
        $message->setDescription("Balbalbalbala");


        return $this->render('app/indexId.html.twig.html.twig',
            [
                'controller_name' => 'AppController',
                'id' => $id,
                'random' => rand(0, 50),
                'range' => $range,
                'message' => $message
            ]);
    }

    #[Route('/', name: 'listeArticles', priority: 1)]
    public function messages(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $topics = $em->getRepository(Topic::class)->findBy([],['id' => 'DESC'],3);
        return $this->render('app/index.html.twig',
            ['topics' => $topics]);
    }


    #[Route('/articles/{year}/{month}/{day}', name: 'articles',
        requirements: [
            'year' => '^\d{4}$',
            'month' => '^\d{1,2}$',
            'day' => '^\d{1,2}$'
        ])]
    public function articles($year = null, $month = null, $day = null): Response
    {

        return $this->json([
            'year' => $year,
            'month' => $month,
            'day' => $day,
        ]);
    }

}
