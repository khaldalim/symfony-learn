<?php


namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/tag')]
/**
 * @IsGranted("ROLE_ADMIN")
 */
class TagAdminController extends AbstractController
{
    #[Route('/new', name: 'tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $illustration = $form->get('illustration')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($illustration) {
                $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $illustration->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $illustration->move(
                        $this->getParameter('upload-tag'),
                        $newFilename,

                    );
                    $entityManager->persist($tag);
                    $entityManager->flush();
                    $this->addFlash('success', "Tag crée !");
                } catch (FileException $e) {
                    $this->addFlash('danger', "Formulaire non valide !");
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $tag->setIllustration($newFilename);
            }
            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}/edit', name: 'tag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $illustration = $form->get('illustration')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($illustration) {
                $originalFilename = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $illustration->guessExtension();

                try {
                    $illustration->move(
                        $this->getParameter('upload-tag'),
                        $newFilename
                    );
                    $tag->setIllustration($newFilename);
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success', "Tag modifié !");
                } catch (FileException $e) {
                    $this->addFlash('danger', "Formulaire non valide !");
                }


                return $this->redirectToRoute('tag_index');
            }
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}/delete', name: 'tag_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tag_index');
    }


}
