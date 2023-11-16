<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\TrickFormType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/trick')]
class TrickController extends AbstractController
{


    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository, ManagerRegistry $doctrine): Response
    {
        $tricks = $trickRepository->findAll();
        $images = null;
        foreach ($tricks as $trick){
            $manager = $doctrine->getManager();
            $image = $manager->getRepository(Image::class);
            $img = $image->findOneBy(['idTrick' => $trick->getId()]);
            //images =
    }


        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserInterface $user): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setIdUser($this->getUser());
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'trick_show')]
    public function show(?Trick $trick, ManagerRegistry $doctrine): Response
    {
        if (!$trick) {
            return $this->redirectToRoute('app_home');
        }
        $manager = $doctrine->getManager();
        $image = $manager->getRepository(Image::class);
        $img = $image->findOneBy(['idTrick' => $trick->getId()]);

        $parameters = [
            'trick' => $trick,
            'image' => $img->getImage()
        ];


        return $this->render('home/trick.html.twig', $parameters);
    }

    #[Route('/{id}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_trick_delete')]
    public function deleteTrick(Trick $trick = null, ManagerRegistry $doctrine): RedirectResponse
    {
        //Recuperer la personne.
        if ($trick) {
            $manager = $doctrine->getManager();
            $manager->remove($trick);
            $manager->flush();
            $this->addFlash('success', "le trick est bien supprimé");


        } else {
            $this->addFlash('erreur', "le trick est inexistant");

        }
        return $this->redirectToRoute('app_home');
    }


    #[Route('/trick/add', name: 'app_trick_add')]
    public function addTrick(): Response
    {
        //Crée un "nouveau trick"
        $trick = new Trick();

        //Crée le formulaire
        $form = $this->createForm(TrickFormType::class, $trick);

        return $this->render('trick/add.html.twig', [
            'trickForm' => $form->createView()
        ]);

    }
}
