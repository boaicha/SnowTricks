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
use App\Services\PictureService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/trick')]
class TrickController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/{slug}', name: 'trick_show')]
    public function show(?Trick $trick, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, Request $request, ?Discussion $discussions): Response
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

        if ($formDiscussion->isSubmitted() && $formDiscussion->isValid()) {
            $discussions = $formDiscussion->get('content')->getData();
            $discussion->setIduser($this->getUser());
            $date = new DateTime('@' . strtotime('now'));

            $discussion->setCreationDate($date);
            $discussion->setTrick($trick);
            $entityManager->persist($discussion);
            $entityManager->flush();
        }
        $videos = $entityManager->getRepository(Video::class)->findBy(['idTrick' => $trick->getId()]);
        $images = $entityManager->getRepository(Image::class)->findBy(['idTrick' => $trick->getId()]);

        $parameters = [
            'trick' => $trick,
            'image' => $img,
            'videos' => $videos,
            'images' => $images,
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


    #[Route('/trick/add', name: 'app_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, PictureService $pictureService, SessionInterface $session): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        $date = new DateTime('@'.strtotime('now'));

        try {
            // perform some task
            if ($form->isSubmitted() && $form->isValid()) {
                $images = $form->get('images')->getData();
                $videos = $form->get('videos')->getData();

                $trick->setCreationDate($date);
                $trick->setIdUser($this->getUser());

                foreach ($images as $image) {
                    $folder = 'tricks';
                    $img = new Image();
                    $file = $pictureService->add($image, $folder, 300, 300);

                    $img->setImage($file);
                    $trick->addImage($img);
                }

                if (is_array($videos) || $videos instanceof \Traversable) {
                    foreach ($videos as $videoData) {
                        $video = new Video();
                        $video->setVideo($videoData->getVideo());
                        $trick->addVideo($video);
                    }
                } else {
                    $video = new Video();
                    $video->setVideo($videos);
                    $trick->addVideo($video);
                }
                dd($trick);
                $entityManager->persist($trick);
                $entityManager->flush();

                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        } catch (Exception $ex) {
            $session->getFlashBag()->add('danger', 'Le nom de la figure saisie est déjà existant');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
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
    #[Route('/loadmore/{offset}', name: 'loadmore', methods: ['GET', 'POST'])]
    public function loadMore(TrickRepository $trickRepository, $offset): Response
    {
        $tricks = $trickRepository->findby([],['creationDate' => 'DESC'], 6, $offset);

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'user' => $this->getUser()
        ]);
    }



}


