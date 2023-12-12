<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\DiscussionType;
use App\Form\TrickFormType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use DateTime;
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



            return $this->redirectToRoute('app_trick_index', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/{slug}', name: 'trick_show')]
    public function show(?Trick $trick, ManagerRegistry $doctrine, EntityManagerInterface $entityManager,Request $request, ?Discussion $discussions): Response
    {
        if (!$trick) {
            return $this->redirectToRoute('app_home');
        }
        $manager = $doctrine->getManager();
        $image = $manager->getRepository(Image::class);
        $img = $image->findOneBy(['idTrick' => $trick->getId()]);

        $discussion = new Discussion();

        //Crée le formulaire
        $formDiscussion = $this->createForm(DiscussionType::class, $discussion);
        $formDiscussion->handleRequest($request);
        //$repoDiscussion = $manager->getRepository(Image::class);
        //$discussion = null;
        if ($formDiscussion->isSubmitted() && $formDiscussion->isValid()) {
            $discussions =$formDiscussion->get('content')->getData();
            $discussion->setIduser($this->getUser());
            $date = new DateTime('@'.strtotime('now'));
            $discussion->setCreationDate($date);
            //$discussions->setTrick($trick);
            //dd($discussions);


            $discussion->setTrick($trick);
            $entityManager->persist($discussion);
            $entityManager->flush();


            //$discussion = $repoDiscussion->findBy(['idTrick' => $trick->getId()]);
            //return $this->redirectToRoute('app_home', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }
        $videos = $entityManager->getRepository(Video::class)->findBy(['idTrick' => $trick->getId()]);
        //dd($trick->getDiscussions()->get(0)->getContent());
        //dd($img);
        //dd($trick->getVideos()->get(0)->getVideo());
        //dd($videos[0]->getVideo());
        $parameters = [
            'trick' => $trick,
            'image' => $img,
            'videos' => $videos,
            //'discussion' => $trick->getDiscussions(),
            'discussionForm' => $formDiscussion->createView(),
            'user' => $this->getUser()
        ];


        return $this->render('home/trick.html.twig', $parameters);
    }

    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setIdUser($this->getUser());
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'trickForm' => $form
        ]);
    }


    #[Route('/{slug}/delete', name: 'app_trick_delete')]
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

    #[Route('/trick/{slug}', name: 'app_discussion')]
    public function discussionTrick(): Response
    {
        //Crée un "nouveau trick"
        $discussion = new Discussion();

        //Crée le formulaire
        $form = $this->createForm(DiscussionType::class, $discussion);

        return $this->render('trick/discussion.html.twig', [
            'discussionForm' => $form->createView()
        ]);

    }



}
